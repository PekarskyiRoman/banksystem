<?php

use app\models\Deposit;
use app\services\BankService;

/**
 * @var \omnilight\scheduling\Schedule $schedule
 * @var Deposit $deposit
 */
$schedule->call(function() {
    $bankService = new BankService();
    $deposits = Deposit::findAll();
    foreach($deposits as $deposit) {
        if($deposit->next_interest_date == date('Y-m-d', time())) {
            $bankService->calculateInterest($deposit);
        }
    }
})->dailyAt('10:00');

$schedule->call(function() {
    $bankService = new BankService();
    $deposits = Deposit::findAll();
    foreach($deposits as $deposit) {
        $bankService->calculateCommission($deposit, date('Y-m-d', time()));
    }
})->monthly()->at('11:00');