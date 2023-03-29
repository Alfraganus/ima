<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payment}}`.
 */
class m230216_145036_create_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payments}}', [
            'id' => $this->primaryKey(),
            'user_application_id' => $this->integer(),
            'invoice_request_id' => $this->string(32),
            'invoice_serial' => $this->string(32),
            'invoice_amount' => $this->integer(),
            'invoice_status' => $this->string(32),
            'invoice_note' => $this->string(),
            'invoice_created_at'=>$this->dateTime()->defaultExpression('NOW()'),
            'invoice_expire_date' => $this->dateTime(),
            'payment_taxid' => $this->integer(),
            'online_license' => $this->string(20),
            'invoice_json' => $this->text(),
            'billing_request_id' => $this->string(32),
            'billing_invoice_serial' => $this->string(32),
            'billing_amount' => $this->integer(),
            'billing_status' => $this->string(32),
            'billing_note' => $this->string(),
            'billing_created_at' => $this->dateTime(),
            'billing_json' => $this->text(),
            'billing_ip' => $this->string(32),
            'payment_status' => $this->boolean(),
            'document' => $this->text(),
            'created_at' => $this->dateTime()->defaultExpression('NOW()'),
        ]);
        $this->addForeignKey(
            'fk-payment_user_application_id',
            'payments',
            'user_application_id',
            'user_applications',
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
            'fk-payment_user_application_id',
            'payments'
        );
        $this->dropTable('{{%payment}}');
    }
}
