<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%priority}}`.
 */
class m230307_051532_create_priority_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%form_priority}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'user_application_id' => $this->integer(),
            'user_application_wizard_id' => $this->integer(),
            'priority_type' => $this->integer(),
            'questionnaire_number' => $this->string(),
            'requested_data'=>$this->date(),
            'country_id'=>$this->integer()
        ]);
        $this->addForeignKey(
            'fk-_form_priority_application_id',
            'form_priority',
            'user_application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-form_priority_wizard_id',
            'form_priority',
            'user_application_wizard_id',
            'application_wizard',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-form_priority-user_id',
            'form_priority',
            'user_id',
            'user',
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
            'fk-_form_priority_application_id',
            'form_priority'
        );
        $this->dropForeignKey(
            'fk-form_priority_wizard_id',
            'form_priority'
        );

        $this->dropForeignKey(
            'fk-form_priority-user_id',
            'form_priority'
        );
        $this->dropTable('{{%form_priority}}');
    }
}
