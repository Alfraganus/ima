<?php

use yii\db\Migration;

/**
 * Class m230524_100720_add_phone_number_to_doverenniy_litso
 */
class m230524_100720_add_phone_number_to_doverenniy_litso extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('expert_form_doverenniy_litso', 'phone_number', $this->string()->null());
        $this->addColumn('expert_form_doverenniy_litso', 'stir', $this->string()->null());
        $this->addColumn('expert_form_vladeles', 'phone_number', $this->string()->null());
        $this->addColumn('expert_form_vladeles', 'stir', $this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('expert_form_doverenniy_litso', 'phone_number');
        $this->dropColumn('expert_form_doverenniy_litso', 'stir');
        $this->dropColumn('expert_form_vladeles', 'phone_number');
        $this->dropColumn('expert_form_vladeles', 'stir');
    }
}
