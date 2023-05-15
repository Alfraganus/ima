<?php

use yii\db\Migration;

/**
 * Class m230515_135633_update_expert_decision_table
 */
class m230515_135633_update_expert_decision_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('expert_form_decision','extra_info',$this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('expert_form_decision','extra_info');

    }
}
