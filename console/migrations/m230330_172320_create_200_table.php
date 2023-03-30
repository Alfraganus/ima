<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%200}}`.
 */
class m230330_172320_create_200_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expert_form_200}}', [
            'id' => $this->primaryKey(),
            'expert_id'=>$this->integer(),
            'user_id'=>$this->integer(),
            'application_id'=>$this->integer(),
            'user_application_id'=>$this->integer(),
            'module_id'=>$this->integer(),
            'column_210'=>$this->string(50)->null(),
            'column_220'=>$this->date()->null(),
            'column_230'=>$this->date()->null(),
        ]);
        $this->addForeignKey(
            'fk-expert_form_200_user_application_id',
            'expert_form_200',
            'user_application_id',
            'user_applications',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-expert_form_200_expert_id',
            'expert_form_200',
            'expert_id',
            'expert_user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-expert_form_200_user_id',
            'expert_form_200',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-expert_form_200_application_id',
            'expert_form_200',
            'application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-expert_form_200_module_id',
            'expert_form_200',
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
            'fk-expert_form_200_user_application_id',
            'expert_form_200'
        );

        $this->dropForeignKey(
            'fk-expert_form_200_expert_id',
            'expert_form_200'
        );

        $this->dropForeignKey(
            'fk-expert_form_200_user_id',
            'expert_form_200'
        );

        $this->dropForeignKey(
            'fk-expert_form_200_application_id',
            'expert_form_200'
        );
        $this->dropForeignKey(
            'fk-expert_form_200_module_id',
            'expert_form_200'
        );

        $this->dropTable('{{%expert_form_200}}');
    }
}
