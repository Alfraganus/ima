<?php

use yii\db\Migration;

/**
 * Class m230302_163514_add_form_class_to_application_form_table
 */
class m230302_163514_add_form_class_to_application_form_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('application_form','form_class',$this->string(200));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('application_form','form_class');
    }
}
