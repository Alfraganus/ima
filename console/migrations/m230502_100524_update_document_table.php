<?php

use yii\db\Migration;

/**
 * Class m230502_100524_update_document_table
 */
class m230502_100524_update_document_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('form_industry_document', 'form_document');
        $this->addColumn('form_document', 'form_id', $this->integer());
        $this->addForeignKey(
            'fk-form_document-form_id',
            'form_document',
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
        $this->dropColumn('form_document', 'form_id');
        $this->dropForeignKey(
            'fk-form_document-form_id',
            'form_document'
        );
        $this->renameTable('form_document', 'form_industry_document');

    }
}
