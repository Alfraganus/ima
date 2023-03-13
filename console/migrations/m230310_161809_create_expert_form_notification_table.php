<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expert_form_notification}}`.
 */
class m230310_161809_create_expert_form_notification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expert_form_notification}}', [
            'id' => $this->primaryKey(),
            'expert_id'=>$this->integer(),
            'user_id'=>$this->integer(),
            'application_id'=>$this->integer(),
            'user_application_id'=>$this->integer(),
            'module_id'=>$this->integer(),
            'tab_id'=>$this->integer(),
            'notification_type'=>$this->integer()->null(),
            'department'=>$this->integer()->null(),
            'sent_date'=>$this->date()->null(),
            'application_identification'=>$this->string(150)->null(),
        ]);
        $this->addForeignKey(
            'fk-_expert_form_notification_user_application_id',
            'expert_form_notification',
            'user_application_id',
            'user_applications',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-_expert_form_notification_expert_id',
            'expert_form_notification',
            'expert_id',
            'expert_user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-_expert_form_notification_user_id',
            'expert_form_notification',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-_expert_form_notification_application_id',
            'expert_form_notification',
            'application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-expert_form_notification_module_id',
            'expert_form_notification',
            'module_id',
            'expert_modules',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-expert_form_notification_tab_id',
            'expert_form_notification',
            'tab_id',
            'expert_tabs',
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
            'fk-_expert_form_notification_expert_id',
            'expert_form_notification'
        );

        $this->dropForeignKey(
            'fk-_expert_form_notification_user_application_id',
            'expert_form_notification'
        );

        $this->dropForeignKey(
            'fk-_expert_form_notification_user_id',
            'expert_form_notification'
        );

        $this->dropForeignKey(
            'fk-_expert_form_notification_application_id',
            'expert_form_notification'
        );
        $this->dropForeignKey(
            'fk-expert_form_notification_module_id',
            'expert_form_notification'
        );
        $this->dropForeignKey(
            'fk-expert_form_notification_tab_id',
            'expert_form_notification'
        );
        $this->dropTable('{{%expert_form_notification}}');
    }
}
