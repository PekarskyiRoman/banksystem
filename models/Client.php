<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client".
 *
 * @property int $id
 * @property string $name
 * @property string $lastname
 * @property string $code
 * @property string $birth_date
 * @property int $gender_id
 *
 * @property Gender $gender
 */
class Client extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'lastname', 'code', 'birth_date', 'gender_id'], 'required'],
            [['birth_date'], 'safe'],
            [['gender_id'], 'integer'],
            [['name', 'lastname'], 'string', 'max' => 45],
            [['code'], 'string', 'max' => 11],
            [['gender_id'], 'exist', 'skipOnError' => true, 'targetClass' => Gender::className(), 'targetAttribute' => ['gender_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'lastname' => 'Lastname',
            'code' => 'Code',
            'birth_date' => 'Birth Date',
            'gender_id' => 'Gender',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGender()
    {
        return $this->hasOne(Gender::className(), ['id' => 'gender_id']);
    }

    public static function getAllClients()
    {
        $clients = self::find()->select(['id', 'name', 'lastname'])->asArray()->all();
        $result = [];
        foreach ($clients as $key => $client) {
            $result[$key]['id'] = $client['id'];
            $result[$key]['fullname'] = $client['lastname'].' '.$client['name'];
        }
        return $result;
    }
}
