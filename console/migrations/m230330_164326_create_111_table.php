<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%111}}`.
 */
class m230330_164326_create_111_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expert_form_100}}', [
            'id' => $this->primaryKey(),
            'expert_id'=>$this->integer(),
            'user_id'=>$this->integer(),
            'application_id'=>$this->integer(),
            'user_application_id'=>$this->integer(),
            'module_id'=>$this->integer(),
            'column_111'=>$this->string(50)->null(),
            'column_190'=>$this->string(20)->null(),
            'column_151'=>$this->date()->null(),
            'column_181'=>$this->date()->null(),
        ]);
        $this->addForeignKey(
            'fk-expert_form_100_user_application_id',
            'expert_form_100',
            'user_application_id',
            'user_applications',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-expert_form_100_expert_id',
            'expert_form_100',
            'expert_id',
            'expert_user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-expert_form_100_user_id',
            'expert_form_100',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-expert_form_100_application_id',
            'expert_form_100',
            'application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-expert_form_100_module_id',
            'expert_form_100',
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
            'fk-expert_form_100_user_application_id',
            'expert_form_100'
        );

        $this->dropForeignKey(
            'fk-expert_form_100_expert_id',
            'expert_form_100'
        );

        $this->dropForeignKey(
            'fk-expert_form_100_user_id',
            'expert_form_100'
        );

        $this->dropForeignKey(
            'fk-expert_form_100_application_id',
            'expert_form_100'
        );
        $this->dropForeignKey(
            'fk-expert_form_100_module_id',
            'expert_form_100'
        );

        $this->dropTable('{{%expert_form_100}}');
    }
}
