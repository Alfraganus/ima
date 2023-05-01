<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expert_form_10}}`.
 */
class m230501_174326_create_expert_form_10_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expert_form_10}}', [
            'id' => $this->primaryKey(),
            'expert_id'=>$this->integer(),
            'user_id'=>$this->integer(),
            'application_id'=>$this->integer(),
            'user_application_id'=>$this->integer(),
            'module_id'=>$this->integer(),
            'column_11'=>$this->string(50)->null(),
            'column_19'=>$this->string(20)->null(),
            'column_15'=>$this->date()->null(),
            'column_18'=>$this->date()->null(),
        ]);
        $this->addForeignKey(
            'fk-expert_form_10_user_application_id',
            'expert_form_10',
            'user_application_id',
            'user_applications',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-expert_form_10_expert_id',
            'expert_form_10',
            'expert_id',
            'expert_user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-expert_form_10_user_id',
            'expert_form_10',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-expert_form_10_application_id',
            'expert_form_10',
            'application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-expert_form_10_module_id',
            'expert_form_10',
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
            'fk-expert_form_10_expert_id',
            'expert_form_10'
        );

        $this->dropForeignKey(
            'fk-expert_form_10_user_id',
            'expert_form_10'
        );

        $this->dropForeignKey(
            'fk-expert_form_10_application_id',
            'expert_form_10'
        );
        $this->dropForeignKey(
            'fk-expert_form_10_module_id',
            'expert_form_10'
        );
        $this->dropTable('{{%expert_form_10}}');
    }
}
