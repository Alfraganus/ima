<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%application}}`.
 */
class m230329_164223_add_payment_column_to_application_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('wizard_form_field','title',$this->string(30)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('wizard_form_field','title');

    }
}
