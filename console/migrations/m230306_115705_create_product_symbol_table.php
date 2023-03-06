<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%form_product_symbol}}`.
 */
class m230306_115705_create_product_symbol_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%form_product_symbol}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'user_application_id' => $this->integer(),
            'user_application_wizard_id' => $this->integer(),
            'type_form_product_symbol'=>$this->integer(),
            'symbol_description'=>$this->text(),
            'color_harmony'=>$this->text(),
            'character_transliteration'=>$this->text(),
            'is_community_symbol'=>$this->boolean(),
        ]);

        $this->addForeignKey(
            'fk-form_product_symbol_application_id',
            'form_product_symbol',
            'user_application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-form_product_symbol_wizard_id',
            'form_product_symbol',
            'user_application_wizard_id',
            'application_wizard',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-form_product_symbol-user_id',
            'form_product_symbol',
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
            'fk-form_product_symbol_application_id',
            'form_product_symbol'
        );
        $this->dropForeignKey(
            'fk-form_product_symbol_wizard_id',
            'form_product_symbol'
        );

        $this->dropForeignKey(
            'fk-form_product_symbol-user_id',
            'form_product_symbol'
        );
        $this->dropTable('{{%form_product_symbol}}');
    }
}
