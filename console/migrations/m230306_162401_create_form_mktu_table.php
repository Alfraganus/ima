<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%form_mktu}}`.
 */
class m230306_162401_create_form_mktu_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('{{%mktu_class}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(),
        ]);

        $this->createTable('{{%mktu_header}}', [
            'id' => $this->primaryKey(),
            'class_id'=>$this->integer(),
            'name'=>$this->string()
        ]);

        $this->createTable('{{%mktu_product}}', [
            'id' => $this->primaryKey(),
            'class_id'=>$this->integer(),
            'name'=>$this->string()
        ]);


        $this->createTable('{{%form_mktu}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'user_application_id' => $this->integer(),
            'user_application_wizard_id' => $this->integer(),
            'class_id'=>$this->integer(),
            'mktu_content_type'=>$this->integer()->null(),
        ]);

        $this->createTable('{{%form_mktu_children}}', [
            'id' => $this->primaryKey(),
            'form_mktu_id' => $this->integer(),
            'content_type_id' => $this->integer()->null(),
            'mktu_header_id' => $this->integer()->null(),
            'mktu_product_id'=>$this->integer()->null(),
        ]);

        $this->addForeignKey(
            'fkform_mktu_children-form_mktu_id',
            'form_mktu_children',
            'form_mktu_id',
            'form_mktu',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fkform_mktu_children-mktu_product_id',
            'form_mktu_children',
            'mktu_product_id',
            'mktu_product',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fkform_mktu_children-mktu_header_id',
            'form_mktu_children',
            'mktu_header_id',
            'mktu_header',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-mktu_header-class_id',
            'mktu_header',
            'class_id',
            'mktu_class',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-mktu_product-class_id',
            'mktu_product',
            'class_id',
            'mktu_class',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-form_mktu-class_id',
            'form_mktu',
            'class_id',
            'mktu_class',
            'id',
            'CASCADE'
        );

        /*form ozi uchun*/
        $this->addForeignKey(
            'fk-form_mktu_application_id',
            'form_mktu',
            'user_application_id',
            'application',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-form_mktu_wizard_id',
            'form_mktu',
            'user_application_wizard_id',
            'application_wizard',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-form_mktu-user_id',
            'form_mktu',
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
            'fkform_mktu_children-form_mktu_id',
            'form_mktu_children'
        );

        $this->dropForeignKey(
            'fkform_mktu_children-mktu_product_id',
            'form_mktu_children'
        );

        $this->dropForeignKey(
            'fkform_mktu_children-mktu_header_id',
            'form_mktu_children'
        );

        $this->dropForeignKey(
            'fk-mktu_header-class_id',
            'mktu_header'
        );
        $this->dropForeignKey(
            'fk-mktu_product-class_id',
            'mktu_product'
        );
        $this->dropForeignKey(
            'fk-form_mktu-class_id',
            'form_mktu'
        );

        $this->dropTable('{{%form_mktu}}');
        $this->dropTable('{{%mktu_class}}');
        $this->dropTable('{{%mktu_header}}');
        $this->dropTable('{{%mktu_product}}');
        $this->dropTable('{{%form_mktu_children}}');
    }
}
