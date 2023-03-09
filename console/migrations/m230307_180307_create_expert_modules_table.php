<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expert_modules}}`.
 */
class m230307_180307_create_expert_modules_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expert_modules}}', [
            'id' => $this->primaryKey(),
            'module_name'=>$this->string(200)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%expert_modules}}');
    }
}
