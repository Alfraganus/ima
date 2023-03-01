<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_application_content}}`.
 */
class m230228_182311_create_author_application_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%author_application}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'user_application_id' => $this->integer(),
            'user_application_wizard_id' => $this->integer(),
            'author_country_code' => $this->integer(),
            'jshshir' => $this->string(255),
            'full_name' => $this->string(255),
            'region' => $this->integer(),
            'district' => $this->integer(),
            'address' => $this->string(255),
            'workplace' => $this->string(255),
            'position' => $this->string(255),
            'academic_degree' => $this->string(150),

        ]);
    }
/*
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
            'CASCADE'*/


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      /*  $this->dropForeignKey(
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
        $this->dropTable('{{%user_application_content}}');*/
    }
}
