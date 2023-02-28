<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_applications}}`.
 */
class m230228_182300_create_user_applications_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_applications}}', [
            'id' => $this->primaryKey(),
            'user_id'=>$this->integer(),
            'application_id'=>$this->integer(),
            'is_finished'=>$this->boolean()->null(),
            'payment_done'=>$this->boolean()->null(),
            'current_wizard_id'=>$this->integer()->null(),
            'date_submitted'=>$this->integer()->null()
        ]);

        $this->addForeignKey(
            'fk-user_applications-user_id',
            'user_applications',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-user_applications-application_id',
            'user_applications',
            'application_id',
            'application',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-user_applications-current_wizard_id',
            'user_applications',
            'current_wizard_id',
            'application_wizard',
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
            'fk-user_applications-user_id',
            'user_applications'
        );

        $this->dropForeignKey(
            'fk-user_applications-application_id',
            'user_applications'
        );

        $this->dropForeignKey(
            'fk-user_applications-current_wizard_id',
            'user_applications'
        );

        $this->dropTable('{{%user_applications}}');
    }
}
