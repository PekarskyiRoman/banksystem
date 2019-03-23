<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%operation_log}}`.
 */
class m190321_144659_create_operation_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%operation_log}}', [
            'id' => $this->primaryKey(),
            'operation_id' => $this->integer()->notNull(),
            'client_id' => $this->integer()->notNull(),
            'amount' => $this->money(10, 2)->notNull(),
            'date' => $this->date()
        ]);

        $this->addForeignKey('fk_operation', 'operation_log', 'operation_id', 'operation', 'id');
        $this->addForeignKey('fk_log_client', 'operation_log', 'client_id', 'client', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%operation_log}}');
    }
}
