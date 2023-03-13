<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expert_form_feedback}}`.
 */
class m230311_092530_create_expert_form_feedback_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expert_form_feedback}}', [
            'id' => $this->primaryKey(),
            'expert_id'=>$this->integer(),
            'user_id'=>$this->integer(),
            'application_id'=>$this->integer(),
            'user_application_id'=>$this->integer(),
            'module_id'=>$this->integer(),
            'tab_id'=>$this->integer(),
            'department'=>$this->integer()->null(),
            'feedback_date'=>$this->date()->null(),
            'date_recovery'=>$this->date(),
            'application_identification'=>$this->string(150)->null(),
            'feedback_type'=>$this->integer(),
        ]);
        $this->addForeignKey(
            'fk-_expert_form_feedback_user_application_id',
            'expert_form_feedback',
            'user_application_id',
            'user_applications',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-_expert_form_feedback_expert_id',
            'expert_form_feedback',
            'expert_id',
            'expert_user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-_expert_form_feedback_user_id',
            'expert_form_feedback',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-_expert_form_feedback_application_id',
            'expert_form_feedback',
            'application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-expert_form_feedback_module_id',
            'expert_form_feedback',
            'module_id',
            'expert_modules',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-expert_form_feedback_tab_id',
            'expert_form_feedback',
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
            'fk-_expert_form_feedback_expert_id',
            'expert_form_feedback'
        );
        $this->dropForeignKey(
            'fk-_expert_form_feedback_user_application_id',
            'expert_form_feedback'
        );
        $this->dropForeignKey(
            'fk-_expert_form_feedback_user_id',
            'expert_form_feedback'
        );
        $this->dropForeignKey(
            'fk-_expert_form_feedback_application_id',
            'expert_form_feedback'
        );
        $this->dropForeignKey(
            'fk-expert_form_feedback_module_id',
            'expert_form_feedback'
        );
        $this->dropForeignKey(
            'fk-expert_form_feedback_tab_id',
            'expert_form_feedback'
        );
        $this->dropTable('{{%expert_form_feedback}}');
    }
}
