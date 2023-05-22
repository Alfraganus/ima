<?php

use yii\db\Migration;

/**
 * Class m230522_181429_update_form_industry_document_table
 */
class m230522_181429_update_form_industry_document_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_industry_example','is_main',$this->tinyInteger()->null());
        $this->addColumn('form_industry_example','language',$this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('form_industry_example','is_main');
        $this->dropColumn('form_industry_example','language');
    }
}
