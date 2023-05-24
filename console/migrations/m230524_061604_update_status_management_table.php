<?php

use yii\db\Migration;

/**
 * Class m230524_061604_update_status_management_table
 */
class m230524_061604_update_status_management_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('application_status_management','is_answer_required',$this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('application_status_management','is_answer_required');

    }
}
