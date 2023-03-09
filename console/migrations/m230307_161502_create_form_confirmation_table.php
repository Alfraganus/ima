<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%form_confirmation}}`.
 */
class m230307_161502_create_form_confirmation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%form_confirmation}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'user_application_id' => $this->integer(),
            'user_application_wizard_id' => $this->integer(),
            'is_confirmed'=>$this->boolean(),
            'confirmed_date'=> $this->dateTime()->defaultExpression('NOW()')
        ]);
        $this->addForeignKey(
            'fk-_form_confirmation_application_id',
            'form_confirmation',
            'user_application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-form_confirmation_wizard_id',
            'form_confirmation',
            'user_application_wizard_id',
            'application_wizard',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-form_confirmation-user_id',
            'form_confirmation',
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
            'fk-_form_confirmation_application_id',
            'form_confirmation'
        );
        $this->dropForeignKey(
            'fk-form_confirmation_wizard_id',
            'form_confirmation'
        );

        $this->dropForeignKey(
            'fk-form_confirmation-user_id',
            'form_confirmation'
        );
        $this->dropTable('{{%form_confirmation}}');
    }
}
