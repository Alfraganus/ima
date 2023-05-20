<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%new_50_links}}`.
 */
class m230520_170540_create_new_50_links_table extends Migration
{
    /**
     * {@inheritdoc}
     */
     public function safeUp()
     {
         $this->createTable('{{%expert_form_links}}', [
             'id' => $this->primaryKey(),
             'expert_id'=>$this->integer(),
             'user_id'=>$this->integer(),
             'application_id'=>$this->integer(),
             'module_id'=>$this->integer(),
             'user_application_id'=>$this->integer(),
             'is_perechen'=>$this->boolean(),
             'language'=>$this->integer(),
             'element'=>$this->string(200),
         ]);
         $this->addForeignKey(
             'expert_form_links_user_application_id',
             'expert_form_links',
             'user_application_id',
             'user_applications',
             'id',
             'CASCADE'
         );

         $this->addForeignKey(
             'expert_form_links_expert_id',
             'expert_form_links',
             'expert_id',
             'expert_user',
             'id',
             'CASCADE'
         );

         $this->addForeignKey(
             'expert_form_links_user_id',
             'expert_form_links',
             'user_id',
             'user',
             'id',
             'CASCADE'
         );

         $this->addForeignKey(
             'expert_form_links_application_id',
             'expert_form_links',
             'application_id',
             'application',
             'id',
             'CASCADE'
         );
         $this->addForeignKey(
             'expert_form_links_module_id',
             'expert_form_links',
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
            'expert_form_links_user_application_id',
            'expert_form_links'
        );

        $this->dropForeignKey(
            'expert_form_links_expert_id',
            'expert_form_links'
        );

        $this->dropForeignKey(
            'expert_form_links_user_id',
            'expert_form_links'
        );

        $this->dropForeignKey(
            'expert_form_links_application_id',
            'expert_form_links'
        );
        $this->dropForeignKey(
            'expert_form_links_module_id',
            'expert_form_links'
        );

        $this->dropTable('{{%expert_form_links}}');
    }
}
