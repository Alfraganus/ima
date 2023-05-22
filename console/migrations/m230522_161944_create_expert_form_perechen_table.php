<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expert_form_perechen}}`.
 */
class m230522_161944_create_expert_form_perechen_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expert_form_perechen}}', [
            'id' => $this->primaryKey(),
            'expert_id'=>$this->integer(),
            'user_id'=>$this->integer(),
            'application_id'=>$this->integer(),
            'user_application_id'=>$this->integer(),
            'module_id'=>$this->integer(),
            'language'=>$this->integer(),
            'element'=>$this->string(200),
        ]);
        $this->addForeignKey(
            'expert_form_perechen_user_application_id',
            'expert_form_perechen',
            'user_application_id',
            'user_applications',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'expert_form_perechen_expert_id',
            'expert_form_perechen',
            'expert_id',
            'expert_user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'expert_form_perechen_user_id',
            'expert_form_perechen',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'expert_form_perechen_application_id',
            'expert_form_perechen',
            'application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'expert_form_perechen_module_id',
            'expert_form_perechen',
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
            'expert_form_perechen_user_application_id',
            'expert_form_perechen'
        );

        $this->dropForeignKey(
            'expert_form_perechen_expert_id',
            'expert_form_perechen'
        );

        $this->dropForeignKey(
            'expert_form_perechen_user_id',
            'expert_form_perechen'
        );

        $this->dropForeignKey(
            'expert_form_perechen_application_id',
            'expert_form_perechen'
        );
        $this->dropForeignKey(
            'expert_form_perechen_module_id',
            'expert_form_perechen'
        );

        $this->dropTable('{{%expert_form_perechen}}');
    }
}
