<?php

use yii\db\Migration;

/**
 * Class m230309_173640_expert_media_files_table
 */
class m230309_173640_expert_media_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    { 
        $this->createTable('{{%expert_form_media}}', [
        'id' => $this->primaryKey(),
        'application_id'=>$this->integer(),
        'user_application_id'=>$this->integer(),
        'module_id'=>$this->integer()->null(),
        'tab_id'=>$this->integer()->null(),
        'form_id'=>$this->integer()->null(),
        'user_id'=>$this->integer(),
        'file_name'=>$this->string(255)->null(),
        'file_path'=>$this->string(500)->null(),
        'file_extension'=>$this->string(500)->null(),
    ]);
        $this->addForeignKey(
            'fk-_expert_form_media_user_id',
            'expert_form_media',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-_expert_form_media_application_id',
            'expert_form_media',
            'application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-expert_form_media_module_id',
            'expert_form_media',
            'module_id',
            'expert_modules',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-expert_form_media_tab_id',
            'expert_form_media',
            'tab_id',
            'expert_tabs',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-expert_form_media_form_id',
            'expert_form_media',
            'form_id',
            'expert_form_list',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-expert_form_median_user_application_id',
            'expert_form_media',
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
            'fk-_expert_form_media_user_id',
            'expert_form_media'
        );
        $this->dropForeignKey(
            'fk-_expert_form_media_application_id',
            'expert_form_media'
        );
        $this->dropForeignKey(
            'fk-expert_form_media_module_id',
            'expert_form_media'
        );
        $this->dropForeignKey(
            'fk-expert_form_media_tab_id',
            'expert_form_media'
        );
        $this->dropForeignKey(
            'fk-expert_form_media_form_id',
            'expert_form_media'
        );
        $this->dropForeignKey(
            'fk-expert_form_median_user_application_id',
            'expert_form_media'
        );
      $this->dropTable('expert_form_media');
    }
}