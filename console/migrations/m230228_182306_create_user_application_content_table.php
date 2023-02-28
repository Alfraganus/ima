<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_application_content}}`.
 */
class m230228_182306_create_user_application_content_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_application_content}}', [
            'id' => $this->primaryKey(),
            'user_application_id'=>$this->integer(),
            'user_application_wizard_id'=>$this->integer(),
            'user_application_form_id'=>$this->integer(),
            'user_application_form_field_id'=>$this->integer(),
            'user_application_form_field_key'=>$this->string(255),
            'user_application_form_field_value'=>$this->text(),
        ]);

        $this->addForeignKey(
            'fk-user_application_content-wizard_id',
            'user_application_content',
            'user_application_wizard_id',
            'application_wizard',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-user_application_content-form_id',
            'user_application_content',
            'user_application_form_id',
            'application_form',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-user_application_content-form_field_id',
            'user_application_content',
            'user_application_form_id',
            'application_form_field',
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
            'fk-user_application_content-wizard_id',
            'user_application_content'
        );
        $this->dropForeignKey(
            'fk-user_application_content-form_id',
            'user_application_content'
        );

        $this->dropForeignKey(
            'fk-user_application_content-form_field_id',
            'user_application_content'
        );
        $this->dropTable('{{%user_application_content}}');
    }
}
