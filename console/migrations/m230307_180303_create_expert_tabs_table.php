<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expert_tabs}}`.
 */
class m230307_180303_create_expert_tabs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expert_tabs}}', [
            'id' => $this->primaryKey(),
            'tab_name'=>$this->string(200)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%expert_tabs}}');
    }
}
