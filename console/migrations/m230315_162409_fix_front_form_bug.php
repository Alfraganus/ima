<?php

use yii\db\Migration;

/**
 * Class m230315_162409_fix_front_form_bug
 */
class m230315_162409_fix_front_form_bug extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->dropForeignKey(
            'fk-form_author_application_id',
            'form_author'
        );
        $this->dropForeignKey(
            'fk-form_requester_application_id',
            'form_requester'
        );
        $this->dropForeignKey(
            'fk-form_media-application_id',
            'application_form_media'
        );
        $this->dropForeignKey(
            'fk-_form_industry_example_application_id',
            'form_industry_example'
        );
        $this->dropForeignKey(
            'fk-form_industry_document_application_id',
            'form_industry_document'
        );
        $this->dropForeignKey(
            'fk-product_symbol_application_id',
            'form_product_symbol'
        );
        $this->dropForeignKey(
            'fk-form_mktu_application_id',
            'form_mktu'
        );
        $this->dropForeignKey(
            'fk-_form_priority_application_id',
            'form_priority'
        );
        $this->dropForeignKey(
            'fk-_form_confirmation_application_id',
            'form_confirmation'
        );
        $this->dropForeignKey(
            'fk-_form_payment_application_id',
            'form_payment'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230315_162409_fix_front_form_bug cannot be reverted.\n";

        return false;
    }

}
