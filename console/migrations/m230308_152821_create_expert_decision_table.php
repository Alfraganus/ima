<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expert_decision}}`.
 */
class m230308_152821_create_expert_decision_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expert_form_decision}}', [
            'id' => $this->primaryKey(),
            'expert_id'=>$this->integer(),
            'user_id'=>$this->integer(),
            'application_id'=>$this->integer(),
            'module_id'=>$this->integer(),
            'tab_id'=>$this->integer(),
            'decision_type'=>$this->integer(),
            'application_identification'=>$this->string(150)->null(),
            'accepted_date'=>$this->date()->null(),
            'sent_date'=>$this->date()->null(),
            'expert_fullname'=>$this->string(255)->null(),
        ]);

        $this->addForeignKey(
            'fk-_expert_form_decision_expert_id',
            'expert_form_decision',
            'expert_id',
            'expert_user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-_expert_form_decision_user_id',
            'expert_form_decision',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-_expert_form_decision_application_id',
            'expert_form_decision',
            'application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-expert_form_decision_module_id',
            'expert_form_decision',
            'module_id',
            'expert_modules',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-expert_form_decision_tab_id',
            'expert_form_decision',
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
            'fk-_expert_form_decision_expert_id',
            'expert_form_decision'
        );

        $this->dropForeignKey(
            'fk-_expert_form_decision_user_id',
            'expert_form_decision'
        );

        $this->dropForeignKey(
            'fk-_expert_form_decision_application_id',
            'expert_form_decision'
        );
        $this->dropForeignKey(
            'fk-expert_form_decision_module_id',
            'expert_form_decision'
        );
        $this->dropForeignKey(
            'fk-expert_form_decision_tab_id',
            'expert_form_decision'
        );

        $this->dropTable('{{%expert_form_decision}}');
    }
}
