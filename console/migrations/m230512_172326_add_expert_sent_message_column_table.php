<?php

use yii\db\Migration;

/**
 * Class m230512_172326_add_expert_sent_message_column_table
 */
class m230512_172326_add_expert_sent_message_column_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('expert_form_decision','is_sent',$this->boolean()->defaultValue(false));
        $this->addColumn('expert_form_feedback','is_sent',$this->boolean()->defaultValue(false));
        $this->addColumn('expert_form_notification','is_sent',$this->boolean()->defaultValue(false));
        $this->addColumn('expert_form_enquiry','is_sent',$this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('expert_form_decision','is_sent');
        $this->dropColumn('expert_form_feedback','is_sent');
        $this->dropColumn('expert_form_notification','is_sent');
        $this->dropColumn('expert_form_enquiry','is_sent');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230512_172326_add_expert_sent_message_column_table cannot be reverted.\n";

        return false;
    }
    */
}
