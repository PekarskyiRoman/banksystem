<?php

namespace app\services;
use app\models\Deposit;
use app\models\OperationLog;
use DateTime;

class BankService
{
    const COMMISSION_OPERATION = 1;
    const INTEREST_OPERATION = 2;
    /**
     * @param Deposit $deposit
     * @throws \Exception
     */
    public function calculateInterest(Deposit $deposit)
    {
        $daysInterval = $this->getPeriodDays($deposit->last_interest_date, $deposit->next_interest_date);
        $interestMoney = $this->calculatePeriodMoney($deposit->balance, $deposit->interest_rate, $daysInterval);
        $deposit->balance += $interestMoney;
        $deposit->last_interest_date = $deposit->next_interest_date;
        $deposit->next_interest_date = $deposit->getNextInterestDate($deposit->last_interest_date);
        $deposit->save();
        BankService::addLogOperation($deposit->client_id, BankService::INTEREST_OPERATION, $interestMoney);
    }

    public function calculateCommission(Deposit $deposit, $commissionDate)
    {
        $commissionMoney = $this->getCommissionAmount($deposit->balance);
        $commissionDays = $this->getPeriodDays($deposit->creation_date, $commissionDate);
        if($commissionDays <= 30) {
            $commissionMoney = ($commissionMoney * $commissionDays)/31;
        }
        $deposit->balance -= $commissionMoney;
        $deposit->save();
        BankService::addLogOperation($deposit->client_id, BankService::COMMISSION_OPERATION, $commissionMoney);
    }

    private function getCommissionAmount($depositMoney)
    {
        if($depositMoney <= 1000) {
            $sum = $depositMoney * 0.05;
            if($sum < 50){
                return 50;
            }
            return $sum;
        }
        elseif($depositMoney > 1000 && $depositMoney < 10000) {
            return $depositMoney * 0.06;
        } else {
            $sum = $depositMoney * 0.07;
            if($sum > 7000) {
                return 7000;
            }
            return $sum;
        }
    }

    /**
     * @param $money
     * @param $rate
     * @param $days
     * @return float
     */
    private function calculatePeriodMoney($money, $rate, $days)
    {
        $rate = $rate/100;
        return ($money * $rate * $days)/365;
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return bool|\DateInterval
     * @throws \Exception
     */
    private function getPeriodDays($startDate, $endDate)
    {
        $periodEnd = new DateTime($endDate);
        $periodStart = new DateTime($startDate);
        return ($periodStart->diff($periodEnd))->days;
    }

    /**
     * @param int $client_id
     * @param int $operation
     * @param float $money
     */
    public static function addLogOperation($client_id, $operation, $money)
    {
        $log = new OperationLog();
        $log->client_id = $client_id;
        $log->operation_id = $operation;
        $log->amount = $money;
        $log->date = date('Y-m-d', time());
        $log->save();
    }
}