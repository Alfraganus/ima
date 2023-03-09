<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%form_payment}}`.
 */
class m230307_161510_create_form_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%form_payment}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'user_application_id' => $this->integer(),
            'user_application_wizard_id' => $this->integer(),
            'payment_done'=>$this->tinyInteger()->null(),
            'payment_info'=>$this->text()->null(),
            'payment_time'=>$this->dateTime()->defaultExpression('NOW()')
        ]);

        $this->addForeignKey(
            'fk-_form_payment_application_id',
            'form_payment',
            'user_application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-form_payment_wizard_id',
            'form_payment',
            'user_application_wizard_id',
            'application_wizard',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-form_payment-user_id',
            'form_payment',
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
            'fk-_form_payment_application_id',
            'form_payment'
        );
        $this->dropForeignKey(
            'fk-form_payment_wizard_id',
            'form_payment'
        );

        $this->dropForeignKey(
            'fk-form_payment-user_id',
            'form_payment'
        );
        $this->dropTable('{{%form_payment}}');
    }
}
