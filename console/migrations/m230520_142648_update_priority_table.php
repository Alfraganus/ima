<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%priority}}`.
 */
class m230520_142648_update_priority_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('form_priority','requested_data');
        $this->dropColumn('form_priority','questionnaire_number');
        $this->renameColumn('form_priority','country_id','country_code_id');
        $this->addColumn('form_priority','application_number',$this->string()->null());
        $this->addColumn('form_priority','submitted_application_date',$this->date()->null());
        $this->addColumn('form_priority','application_type',$this->string()->null());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('form_priority','country_code_id','country_id');
        $this->dropColumn('form_priority','application_number');
        $this->dropColumn('form_priority','submitted_application_date');
        $this->dropColumn('form_priority','application_type');
    }
}
