<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expert_form_enquiry}}`.
 */
class m230310_170234_create_expert_form_enquiry_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expert_form_enquiry}}', [
            'id' => $this->primaryKey(),
            'expert_id'=>$this->integer(),
            'user_id'=>$this->integer(),
            'application_id'=>$this->integer(),
            'user_application_id'=>$this->integer(),
            'module_id'=>$this->integer(),
            'tab_id'=>$this->integer(),
            'type_enquiry'=>$this->integer(),
            'department'=>$this->integer()->null(),
            'sent_date'=>$this->date()->null(),
            'recommended_respond_date'=>$this->date()->null(),
            'application_identification'=>$this->string(150)->null(),
            'date_respond'=>$this->date(),
        ]);
        $this->addForeignKey(
            'fk-_expert_form_enquiry_user_application_id',
            'expert_form_enquiry',
            'user_application_id',
            'user_applications',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-_expert_form_enquiry_expert_id',
            'expert_form_enquiry',
            'expert_id',
            'expert_user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-_expert_form_enquiry_user_id',
            'expert_form_enquiry',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-_expert_form_enquiry_application_id',
            'expert_form_enquiry',
            'application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-expert_form_enquiry_module_id',
            'expert_form_enquiry',
            'module_id',
            'expert_modules',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-expert_form_enquiry_tab_id',
            'expert_form_enquiry',
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
            'fk-_expert_form_enquiry_expert_id',
            'expert_form_enquiry'
        );
        $this->dropForeignKey(
            'fk-_expert_form_enquiry_user_application_id',
            'expert_form_enquiry'
        );
        $this->dropForeignKey(
            'fk-_expert_form_enquiry_user_id',
            'expert_form_enquiry'
        );
        $this->dropForeignKey(
            'fk-_expert_form_enquiry_application_id',
            'expert_form_enquiry'
        );
        $this->dropForeignKey(
            'fk-expert_form_enquiry_module_id',
            'expert_form_enquiry'
        );
        $this->dropForeignKey(
            'fk-expert_form_enquiry_tab_id',
            'expert_form_enquiry'
        );
        $this->dropTable('{{%expert_form_enquiry}}');
    }
}
