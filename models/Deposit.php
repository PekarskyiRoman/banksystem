<?php

namespace app\models;

use DateInterval;
use DateTime;

/**
 * This is the model class for table "deposit".
 *
 * @property int $id
 * @property int $client_id
 * @property double $balance
 * @property double $interest_rate
 * @property string $creation_date
 * @property string $last_interest_date
 * @property string $next_interest_date
 *
 * @property Client $client
 */
class Deposit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deposit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'balance', 'creation_date', 'last_interest_date', 'next_interest_date'], 'required'],
            [['client_id'], 'integer'],
            [['balance', 'interest_rate'], 'number'],
            [['creation_date', 'last_interest_date', 'next_interest_date'], 'safe'],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client',
            'balance' => 'Balance',
            'interest_rate' => 'Interest Rate %',
            'creation_date' => 'Creation Date',
            'last_interest_date' => 'Last Interest Date',
            'next_interest_date' => 'Next Interest Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    public function getClientFullName()
    {
        $client = $this->getClient()->one();
        return $client->name.' '.$client->lastname;
    }

    /**
     * @param $depositDate string
     * @return string
     * @throws \Exception
     */
    public function getNextInterestDate($depositDate)
    {
        $date = new DateTime($depositDate);
        $interval = new DateInterval($this->getDateInterval($depositDate));
        $date->add($interval);
        return $date->format('Y-m-d');
    }

    private function getDateInterval($depositDate)
    {
        $exploded = explode('-', $depositDate);
        if($exploded[2] == 31 && $exploded[1] != 7 && $exploded[1] != 12) {
            if($exploded[1] == 1) {
                return 'P28D';
            }
            return 'P30D';
        } elseif($exploded[2] == 30 && $exploded[1] == 1) {
            return 'P29D';
        }
        return 'P1M';
    }
}
