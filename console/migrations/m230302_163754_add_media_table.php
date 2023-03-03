<?php

use yii\db\Migration;

/**
 * Class m230302_163754_add_media_table
 */
class m230302_163754_add_media_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%application_form_media}}', [
            'id' => $this->primaryKey(),
            'application_id'=>$this->integer(),
            'wizard_id'=>$this->integer()->null(),
            'form_id'=>$this->integer()->null(),
            'user_id'=>$this->integer(),
            'file_name'=>$this->string(255)->null(),
            'file_path'=>$this->string(500)->null(),
            'file_extension'=>$this->string(500)->null(),
        ]);

        $this->addForeignKey(
            'fk-form_media-application_id',
            'application_form_media',
            'application_id',
            'application',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-form_media-wizard_id',
            'application_form_media',
            'wizard_id',
            'application_wizard',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-form_media-form_id',
            'application_form_media',
            'form_id',
            'application_form',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-form_media-user_id',
            'application_form_media',
            'user_id',
            'user',
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
            'fk-form_media-application_id',
            'application_form_media'
        );

        $this->dropForeignKey(
            'fk-form_media-wizard_id',
            'application_form_media'
        );

        $this->dropForeignKey(
            'fk-form_media-form_id',
            'application_form_media'
        );

        $this->dropForeignKey(
            'fk-form_media-user_id',
            'application_form_media'
        );
        $this->dropTable('application_form_media');
    }
}
