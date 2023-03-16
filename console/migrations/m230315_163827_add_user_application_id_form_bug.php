<?php

use yii\db\Migration;

/**
 * Class m230315_163827_add_user_application_id_form_bug
 */
class m230315_163827_add_user_application_id_form_bug extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
     $this->addForeignKey(
            'fk-form_author_application_id',
            'form_author',
            'user_application_id',
            'user_applications',
            'id'
        );
        $this->addForeignKey(
            'fk-form_requester_application_id',
            'form_requester',
            'user_application_id',
            'user_applications',
            'id'
        );
        $this->addForeignKey(
            'fk-form_media-application_id',
            'application_form_media',
            'application_id',
            'user_applications',
            'id'
        );
        $this->addForeignKey(
            'fk-_form_industry_example_application_id',
            'form_industry_example',
            'user_application_id',
            'user_applications',
            'id'
        );
        $this->addForeignKey(
            'fk-form_industry_document_application_id',
            'form_industry_document',
            'user_application_id',
            'user_applications',
            'id'
        );
        $this->addForeignKey(
            'fk-product_symbol_application_id',
            'form_product_symbol',
            'user_application_id',
            'user_applications',
            'id'
        );

        $this->addForeignKey(
            'fk-form_mktu_application_id',
            'form_mktu',
            'user_application_id',
            'user_applications',
            'id'
        );
        $this->addForeignKey(
            'fk-_form_priority_application_id',
            'form_priority',
            'user_application_id',
            'user_applications',
            'id'
        );
        $this->addForeignKey(
            'fk-_form_confirmation_application_id',
            'form_confirmation',
            'user_application_id',
            'user_applications',
            'id'
        );

        $this->addForeignKey(
            'fk-_form_payment_application_id',
            'form_payment',
            'user_application_id',
            'user_applications',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230315_163827_add_user_application_id_form_bug cannot be reverted.\n";

        return false;
    }
}
