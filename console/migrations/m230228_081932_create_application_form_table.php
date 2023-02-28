<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%application_form}}`.
 */
class m230228_081932_create_application_form_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%application_form}}', [
            'id' => $this->primaryKey(),
            'form_name'=>$this->string(150),
            'can_be_multiple'=>$this->boolean()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%application_form}}');
    }
}
