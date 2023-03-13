<?php

use yii\db\Migration;

/**
 * Class m230309_165649_add_user_application_id_table
 */
class m230309_165649_add_user_application_id_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('expert_form_decision', 'user_application_id',$this->integer()->after('application_id'));

        $this->addForeignKey(
            'fk-_expert_form_decision_user_application_id',
            'expert_form_decision',
            'user_application_id',
            'user_applications',
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
            'fk-_expert_form_decision_user_application_id',
            'expert_form_decision'
        );

        $this->dropColumn('expert_form_decision', 'user_application_id');
    }

}
