<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%application_chat}}`.
 */
class m230314_161327_create_application_chat_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%application_chat}}', [
            'id' => $this->primaryKey(),
            'user_application_id'=>$this->integer(),
            'expert_id'=>$this->integer()->null(),
            'sender_is_expert'=>$this->boolean(),
            'expert_form_type_id'=>$this->integer(),
            'expert_form_id'=>$this->integer()->null(),
            'datetime'=> $this->dateTime()->defaultExpression('NOW()'),
            'chat_order_number'=>$this->integer()
        ]);
        $this->addForeignKey(
            'fk-application_chat_expert_id',
            'application_chat',
            'expert_id',
            'expert_user',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-application_chat_expert_form_type_id',
            'application_chat',
            'expert_form_type_id',
            'expert_form_list',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-application_chat_expert_id',
            'application_chat'
        );
        $this->dropForeignKey(
            'fk-application_chat_expert_form_type_id',
            'application_chat'
        );
        $this->dropTable('{{%application_chat}}');
    }
}
