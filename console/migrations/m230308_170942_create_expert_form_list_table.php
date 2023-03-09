<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expert_form_list}}`.
 */
class m230308_170942_create_expert_form_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expert_form_list}}', [
            'id' => $this->primaryKey(),
            'form_name'=>$this->string(200),
            'form_class'=>$this->string(200),
            'operation_function'=>$this->string(100)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%expert_form_list}}');
    }
}
