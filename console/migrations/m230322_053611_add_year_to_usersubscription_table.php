<?php

use yii\db\Migration;

/**
 * Class m230322_053611_add_year_to_usersubscription_table
 */
class m230322_053611_add_year_to_usersubscription_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user_applications','year',$this->integer());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user_applications','year');
    }

}
