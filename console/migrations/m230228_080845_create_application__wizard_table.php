<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%application__wizard}}`.
 */
class m230228_080845_create_application__wizard_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%application_wizard}}', [
            'id' => $this->primaryKey(),
            'application_id'=>$this->integer(),
            'wizard_name'=>$this->string('200'),
            'wizard_order'=>$this->integer()->null(),
            'wizard_icon'=>$this->string(200)->null()
        ]);

        $this->createIndex(
            'id_application_wizard-application_id',
            'application_wizard',
            'application_id'
        );

        $this->addForeignKey(
            'fk-application_wizard_id',
            'application_wizard',
            'application_id',
            'application',
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
            'fk-application_wizard_id',
            'application_wizard',
        );

        $this->dropTable('{{%application__wizard}}');
    }
}
