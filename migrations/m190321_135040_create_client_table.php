<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%client}}`.
 */
class m190321_135040_create_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%client}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(45)->notNull(),
            'lastname' => $this->string(45)->notNull(),
            'code' => $this->string(11)->notNull(),
            'birth_date' => $this->date()->notNull(),
            'gender_id' => $this->integer()->notNull()
        ]);
        $this->addForeignKey('fk_gender', 'client', 'gender_id', 'gender', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%client}}');
    }
}
