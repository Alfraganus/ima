<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%20}}`.
 */
class m230508_090006_create_20_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expert_form_20}}', [
            'id' => $this->primaryKey(),
            'expert_id'=>$this->integer(),
            'user_id'=>$this->integer(),
            'application_id'=>$this->integer(),
            'user_application_id'=>$this->integer(),
            'module_id'=>$this->integer(),
            'column_21'=>$this->string(50)->null(),
            'column_22'=>$this->date()->null(),
        ]);
        $this->addForeignKey(
            'fk-expert_form_20_user_application_id',
            'expert_form_20',
            'user_application_id',
            'user_applications',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-expert_form_20_expert_id',
            'expert_form_20',
            'expert_id',
            'expert_user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-expert_form_20_user_id',
            'expert_form_20',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-expert_form_20_application_id',
            'expert_form_20',
            'application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-expert_form_20_module_id',
            'expert_form_20',
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
            'fk-expert_form_20_user_application_id',
            'expert_form_20'
        );

        $this->dropForeignKey(
            'fk-expert_form_20_expert_id',
            'expert_form_20'
        );

        $this->dropForeignKey(
            'fk-expert_form_20_user_id',
            'expert_form_20'
        );

        $this->dropForeignKey(
            'fk-expert_form_20_application_id',
            'expert_form_20'
        );
        $this->dropForeignKey(
            'fk-expert_form_20_module_id',
            'expert_form_20'
        );
        $this->dropTable('{{%expert_form_20}}');
    }
}
