<?php

use yii\db\Migration;

/**
 * Class m230304_163938_add_application_id_to_wizard_form_table
 */
class m230304_163938_add_application_id_to_wizard_form_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('wizard_form_field','application_id',$this->integer()->after('id'));
        $this->addForeignKey(
            'fk-wizard_form_field-application_id',
            'wizard_form_field',
            'application_id',
            'application',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-wizard_form_field-application_id',
            'wizard_form_field'
        );
        $this->dropColumn('wizard_form_field','application_id');
    }

}
