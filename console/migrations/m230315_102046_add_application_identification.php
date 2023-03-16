<?php

use yii\db\Migration;

/**
 * Class m230315_102046_add_application_identification
 */
class m230315_102046_add_application_identification extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user_applications','generated_id',$this->string('50')->after('user_id')->null());
        $this->addColumn('user_applications','application_number',$this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user_applications','generated_id');
        $this->dropColumn('user_applications','application_number');
    }

}
