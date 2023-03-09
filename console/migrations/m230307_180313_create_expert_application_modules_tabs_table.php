<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expert_application_modules_tabs}}`.
 */
class m230307_180313_create_expert_application_modules_tabs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expert_application_modules_tabs}}', [
            'id' => $this->primaryKey(),
            'application_id'=>$this->integer(),
            'module_id'=>$this->integer(),
            'tab_id'=>$this->integer(),
        ]);
        $this->addForeignKey(
            'fk-expert_application_modules_tabs_application_id',
            'expert_application_modules_tabs',
            'application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-expert_application_modules_tabs_module_id',
            'expert_application_modules_tabs',
            'module_id',
            'expert_modules',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-expert_application_modules_tabs_tab_id',
            'expert_application_modules_tabs',
            'tab_id',
            'expert_tabs',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%expert_application_modules_tabs}}');
    }
}
