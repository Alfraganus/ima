<?php

use yii\db\Migration;

/**
 * Class m230306_120936_add_index_to_applications_table
 */
class m230306_120936_add_index_to_applications_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex(
            'idx-form_requester-user_id',
            'form_requester',
            'user_id'
        );
        $this->createIndex(
            'idx-form_author-user_id',
            'form_author',
            'user_id'
        );
        $this->createIndex(
            'idx-user_applications-user_id',
            'user_applications',
            'user_id'
        );
        $this->createIndex(
            'idx-form_industry_example-user_id',
            'form_industry_example',
            'user_id'
        );
         $this->createIndex(
             'idx-form_product_symbol-user_id',
             'form_product_symbol',
             'user_id'
         );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-form_requester-user_id',
            'form_requester'
        );
        $this->dropIndex(
            'idx-form_author-user_id',
            'form_author'
        );
        $this->dropIndex(
            'idx-user_applications-user_id',
            'user_applications'
        );
        $this->dropIndex(
            'idx-form_industry_example-user_id',
            'form_industry_example'
        );
        $this->dropIndex(
            'idx-form_product_symbol-user_id',
            'form_product_symbol'
        );

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230306_120936_add_index_to_applications_table cannot be reverted.\n";

        return false;
    }
    */
}
