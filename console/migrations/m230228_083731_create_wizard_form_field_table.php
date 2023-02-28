<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%wizard_form_field}}`.
 */
class m230228_083731_create_wizard_form_field_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%wizard_form_field}}', [
            'id' => $this->primaryKey(),
            'wizard_id'=>$this->integer(),
            'form_id'=>$this->integer(),
            'order_id'=>$this->integer()
        ]);

        $this->addForeignKey(
            'fk-wizard_form_field-wizard_id',
            'wizard_form_field',
            'wizard_id',
            'application_wizard',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-wizard_form_field-form_id',
            'wizard_form_field',
            'form_id',
            'application_form',
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
            'fk-wizard_form_field-wizard_id',
            'wizard_form_field'
        );

        $this->dropForeignKey(
            'fk-wizard_form_field-form_id',
            'wizard_form_field'
        );
        $this->dropTable('{{%wizard_form_field}}');
    }
}
