<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%requester}}`.
 */
class m230301_171830_create_requester_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%form_requester}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'user_application_id' => $this->integer(),
            'user_application_wizard_id' => $this->integer(),
            'individual_type' => $this->integer(),
            'jshshir' => $this->string(255),
            'full_name' => $this->string(255),
            'region' => $this->integer(),
            'district' => $this->integer(),
            'submitting_address' => $this->string(255),
            'receiver_name' => $this->string(255),
            'sms_notification_number' => $this->string(255),
            'role_id' => $this->integer(),

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%form_requester}}');
    }
}
