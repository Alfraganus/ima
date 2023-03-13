<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expert_form_payment}}`.
 */
class m230310_152407_create_expert_form_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expert_form_payment}}', [
            'id' => $this->primaryKey(),
            'expert_id'=>$this->integer(),
            'user_id'=>$this->integer(),
            'application_id'=>$this->integer(),
            'user_application_id'=>$this->integer(),
            'module_id'=>$this->integer(),
            'tab_id'=>$this->integer(),
            'payment_purpose_id'=>$this->integer()->null(),
            'payment_date'=>$this->string(150)->null(),
            'currency'=>$this->integer()->null(),
            'amount'=>$this->double()->null(),
        ]);

        $this->addForeignKey(
            'fk-_expert_form_payment_user_application_id',
            'expert_form_payment',
            'user_application_id',
            'user_applications',
            'id',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-_expert_form_payment_expert_id',
            'expert_form_payment',
            'expert_id',
            'expert_user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-_expert_form_payment_user_id',
            'expert_form_payment',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-_expert_form_payment_application_id',
            'expert_form_payment',
            'application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-expert_form_payment_module_id',
            'expert_form_payment',
            'module_id',
            'expert_modules',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-expert_form_payment_tab_id',
            'expert_form_payment',
            'tab_id',
            'expert_tabs',
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
            'fk-_expert_form_payment_expert_id',
            'expert_form_payment'
        );

        $this->dropForeignKey(
            'fk-_expert_form_payment_user_application_id',
            'expert_form_payment'
        );
        
        $this->dropForeignKey(
            'fk-_expert_form_payment_user_id',
            'expert_form_payment'
        );

        $this->dropForeignKey(
            'fk-_expert_form_payment_application_id',
            'expert_form_payment'
        );
        $this->dropForeignKey(
            'fk-expert_form_payment_module_id',
            'expert_form_payment'
        );
        $this->dropForeignKey(
            'fk-expert_form_payment_tab_id',
            'expert_form_payment'
        );
        $this->dropTable('{{%expert_form_payment}}');
    }
}
