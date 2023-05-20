<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%new_50_classification}}`.
 */
class m230520_165835_create_new_50_classification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expert_form_classification}}', [
            'id' => $this->primaryKey(),
            'expert_id'=>$this->integer(),
            'user_id'=>$this->integer(),
            'application_id'=>$this->integer(),
            'module_id'=>$this->integer(),
            'user_application_id'=>$this->integer(),
            'class'=>$this->string(200),
            'sub_class'=>$this->string(200),
        ]);
        $this->addForeignKey(
            'expert_form_classification_user_application_id',
            'expert_form_classification',
            'user_application_id',
            'user_applications',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'expert_form_classification_expert_id',
            'expert_form_classification',
            'expert_id',
            'expert_user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'expert_form_classification_user_id',
            'expert_form_classification',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'expert_form_classification_application_id',
            'expert_form_classification',
            'application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'expert_form_classification_module_id',
            'expert_form_classification',
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
            'expert_form_classification_user_application_id',
            'expert_form_classification'
        );

        $this->dropForeignKey(
            'expert_form_classification_expert_id',
            'expert_form_classification'
        );

        $this->dropForeignKey(
            'expert_form_classification_user_id',
            'expert_form_classification'
        );

        $this->dropForeignKey(
            'expert_form_classification_application_id',
            'expert_form_classification'
        );
        $this->dropForeignKey(
            'expert_form_classification_module_id',
            'expert_form_classification'
        );

        $this->dropTable('{{%expert_form_classification}}');
    }
}
