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
        $this->createTable('{{%form_author}}', [
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
        $this->addForeignKey(
            'fk-form_author_application_id',
            'form_author',
            'user_application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-form_author_wizard_id',
            'form_author',
            'user_application_wizard_id',
            'application_wizard',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-form_author-user_id',
            'form_author',
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
            'fk-form_author_application_id',
            'form_author'
        );

        $this->dropForeignKey(
            'fk-form_author_wizard_id',
            'form_author'
        );

        $this->dropForeignKey(
            'fk-form_author-user_id',
            'form_author'
        );
        $this->dropTable('{{%form_author}}');
    }
}
