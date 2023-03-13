<?php

use yii\db\Migration;

/**
 * Class m230309_181625_add_object_id_to_expert_form_media_table
 */
class m230309_181625_add_object_id_to_expert_form_media_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('expert_form_media', 'object_id', $this->integer()->after('user_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('expert_form_media', 'object_id');
    }


}
