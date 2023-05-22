<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expert_form_doverenniy_litso}}`.
 */
class m230522_162007_create_expert_form_doverenniy_litso_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expert_form_doverenniy_litso}}', [
            'id' => $this->primaryKey(),
            'expert_id'=>$this->integer(),
            'user_id'=>$this->integer(),
            'application_id'=>$this->integer(),
            'module_id'=>$this->integer(),
            'user_application_id'=>$this->integer(),
            'individual_type' => $this->integer(),
            'jshshir' => $this->string(255),
            'full_name' => $this->string(255),
            'country_id' => $this->integer(),
            'region' => $this->integer(),
            'district' => $this->integer(),
            'address' => $this->text(),
        ]);
        $this->addForeignKey(
            'expert_form_doverenniy_litso_user_application_id',
            'expert_form_doverenniy_litso',
            'user_application_id',
            'user_applications',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'expert_form_doverenniy_litso_expert_id',
            'expert_form_doverenniy_litso',
            'expert_id',
            'expert_user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'expert_form_doverenniy_litso_user_id',
            'expert_form_doverenniy_litso',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'expert_form_doverenniy_litso_application_id',
            'expert_form_doverenniy_litso',
            'application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'expert_form_doverenniy_litso_module_id',
            'expert_form_doverenniy_litso',
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
            'expert_form_doverenniy_litso_user_application_id',
            'expert_form_doverenniy_litso'
        );

        $this->dropForeignKey(
            'expert_form_doverenniy_litso_expert_id',
            'expert_form_doverenniy_litso'
        );

        $this->dropForeignKey(
            'expert_form_doverenniy_litso_user_id',
            'expert_form_doverenniy_litso'
        );

        $this->dropForeignKey(
            'expert_form_doverenniy_litso_application_id',
            'expert_form_doverenniy_litso'
        );
        $this->dropForeignKey(
            'expert_form_doverenniy_litso_module_id',
            'expert_form_doverenniy_litso'
        );

        $this->dropTable('{{%expert_form_doverenniy_litso}}');
    }
}
