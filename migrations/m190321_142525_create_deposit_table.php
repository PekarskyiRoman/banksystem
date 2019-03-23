<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%deposit}}`.
 */
class m190321_142525_create_deposit_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%deposit}}', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer()->notNull(),
            'balance' => $this->money(10, 2)->notNull(),
            'interest_rate' => $this->double(),
            'creation_date' => $this->date()->notNull(),
            'last_interest_date' => $this->date()->notNull(),
            'next_interest_date' => $this->date()->notNull()
        ]);
        $this->addForeignKey('fk_client', 'deposit', 'client_id', 'client', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%deposit}}');
    }
}