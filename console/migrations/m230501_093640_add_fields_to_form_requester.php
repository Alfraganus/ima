<?php

use yii\db\Migration;

/**
 * Class m230501_093640_add_fields_to_form_requester
 */
class m230501_093640_add_fields_to_form_requester extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_requester','submitting_country_id',$this->integer()->null()->after('full_name'));
        $this->addColumn('form_requester','stir',$this->string(255)->null()->after('jshshir'));
        $this->addColumn('form_requester','legal_entity_title',$this->string(255)->null()->after('full_name'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('form_requester','submitting_country_id');
        $this->dropColumn('form_requester','stir');
        $this->dropColumn('form_requester','legal_entity_title');
    }


}
