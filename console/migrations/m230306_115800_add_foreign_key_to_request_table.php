<?php

use yii\db\Migration;

/**
 * Class m230306_115800_add_foreign_key_to_request_table
 */
class m230306_115800_add_foreign_key_to_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
            'fk-form_requester_application_id',
            'form_requester',
            'user_application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-form_requester_wizard_id',
            'form_requester',
            'user_application_wizard_id',
            'application_wizard',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-form_requester-user_id',
            'form_requester',
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
        echo "m230306_115800_add_foreign_key_to_request_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230306_115800_add_foreign_key_to_request_table cannot be reverted.\n";

        return false;
    }
    */
}
