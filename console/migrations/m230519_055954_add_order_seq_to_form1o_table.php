<?php

use yii\db\Migration;

/**
 * Class m230519_055954_add_order_seq_to_form1o_table
 */
class m230519_055954_add_order_seq_to_form1o_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('expert_form_10','order_seq',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('expert_form_10','order_seq',);
    }

}
