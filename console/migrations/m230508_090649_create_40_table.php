<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%40}}`.
 */
class m230508_090649_create_40_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expert_form_40}}', [
            'id' => $this->primaryKey(),
            'expert_id'=>$this->integer(),
            'user_id'=>$this->integer(),
            'application_id'=>$this->integer(),
            'user_application_id'=>$this->integer(),
            'module_id'=>$this->integer(),
            'column_46'=>$this->date(),
            'nbull'=>$this->string(255)->null(),
        ]);
        $this->addForeignKey(
            'expert_form_40_user_application_id',
            'expert_form_40',
            'user_application_id',
            'user_applications',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'expert_form_40_expert_id',
            'expert_form_40',
            'expert_id',
            'expert_user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'expert_form_40_user_id',
            'expert_form_40',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'expert_form_40_application_id',
            'expert_form_40',
            'application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'expert_form_40_module_id',
            'expert_form_40',
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
            'expert_form_40_user_application_id',
            'expert_form_40'
        );

        $this->dropForeignKey(
            'expert_form_40_expert_id',
            'expert_form_40'
        );

        $this->dropForeignKey(
            'expert_form_40_user_id',
            'expert_form_40'
        );

        $this->dropForeignKey(
            'expert_form_40_application_id',
            'expert_form_40'
        );
        $this->dropForeignKey(
            'expert_form_40_module_id',
            'expert_form_40'
        );
        
        $this->dropTable('{{%expert_form_40}}');
    }
}
