<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%_form_industry_example}}`.
 */
class m230304_124459_create__form_industry_example_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%form_industry_example}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'user_application_id' => $this->integer(),
            'user_application_wizard_id' => $this->integer(),
            'title'=>$this->string(255),
            'file'=>$this->string(255),
        ]);

        $this->addForeignKey(
            'fk-_form_industry_example_application_id',
            'form_industry_example',
            'user_application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-form_industry_example_wizard_id',
            'form_industry_example',
            'user_application_wizard_id',
            'application_wizard',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-form_industry_example-user_id',
            'form_industry_example',
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
        $this->dropTable('{{%form_industry_example}}');
    }
}
