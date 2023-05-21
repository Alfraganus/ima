<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vladeles}}`.
 */
class m230521_045747_create_vladeles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expert_form_vladeles}}', [
            'id' => $this->primaryKey(),
            'expert_id'=>$this->integer(),
            'user_id'=>$this->integer(),
            'application_id'=>$this->integer(),
            'module_id'=>$this->integer(),
            'user_application_id'=>$this->integer(),
            'individual_type' => $this->integer(),
            'jshshir' => $this->string(255),
            'full_name' => $this->string(255),
            'country_id' => $this->integer(),
            'region' => $this->integer(),
            'district' => $this->integer(),
            'address' => $this->text(),
            'sms_notification_number' => $this->string(255),
        ]);
        $this->addForeignKey(
            'expert_form_vladeles_user_application_id',
            'expert_form_vladeles',
            'user_application_id',
            'user_applications',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'expert_form_vladeles_expert_id',
            'expert_form_vladeles',
            'expert_id',
            'expert_user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'expert_form_vladeles_user_id',
            'expert_form_vladeles',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'expert_form_vladeles_application_id',
            'expert_form_vladeles',
            'application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'expert_form_vladeles_module_id',
            'expert_form_vladeles',
            'module_id',
            'expert_modules',
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
            'expert_form_vladeles_user_application_id',
            'expert_form_vladeles'
        );

        $this->dropForeignKey(
            'expert_form_vladeles_expert_id',
            'expert_form_vladeles'
        );

        $this->dropForeignKey(
            'expert_form_vladeles_user_id',
            'expert_form_vladeles'
        );

        $this->dropForeignKey(
            'expert_form_vladeles_application_id',
            'expert_form_vladeles'
        );
        $this->dropForeignKey(
            'expert_form_vladeles_module_id',
            'expert_form_vladeles'
        );

        $this->dropTable('{{%expert_form_vladeles}}');
    }
}
