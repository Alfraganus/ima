<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%application_form_field}}`.
 */
class m230228_082329_create_application_form_field_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%application_form_field}}', [
            'id' => $this->primaryKey(),
            'form_id'=>$this->integer(),
            'field_name'=>$this->string(100),
            'data_type'=>$this->string(),
            'is_compulsory'=>$this->boolean()
        ]);

        $this->addForeignKey(
            'fk-application_form_field-form_id',
            'application_form_field',
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
            'fk-wizard_form_field-form_id',
            'application_form_field'
        );

        $this->dropTable('{{%application_form_field}}');
    }
}
