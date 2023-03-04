<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%form_industry_document}}`.
 */
class m230304_143008_create__form_industry_document_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%form_industry_document}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'user_application_id' => $this->integer(),
            'user_application_wizard_id' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-form_industry_document_application_id',
            'form_industry_document',
            'user_application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-form_industry_document_wizard_id',
            'form_industry_document',
            'user_application_wizard_id',
            'application_wizard',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-form_industry_document-user_id',
            'form_industry_document',
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
            'fk-form_industry_document_application_id',
            'form_industry_document'
        );
        $this->dropForeignKey(
            'fk-form_industry_document_wizard_id',
            'form_industry_document'
        );

        $this->dropForeignKey(
            'fk-form_industry_document-user_id',
            'form_industry_document'
        );
        $this->dropTable('{{%form_industry_document}}');
    }
}
