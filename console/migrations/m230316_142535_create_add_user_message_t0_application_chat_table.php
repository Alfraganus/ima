<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%add_user_message_t0_application_chat}}`.
 */
class m230316_142535_create_add_user_message_t0_application_chat_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('application_chat','user_message',$this->string(255)->null());
        $this->addColumn('application_chat','user_file',$this->string(255)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('application_chat','user_message');
        $this->dropColumn('application_chat','user_file');

    }
}
