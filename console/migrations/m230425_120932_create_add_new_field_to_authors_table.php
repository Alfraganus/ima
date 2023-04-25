<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%add_new_field_to_authors}}`.
 */
class m230425_120932_create_add_new_field_to_authors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_author','is_anonymous',$this->boolean());
        $this->addColumn('form_author','nickname',$this->string(200));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('form_author','is_anonymous');
        $this->dropColumn('form_author','nickname');
    }
}
