<?php

use yii\db\Migration;

/**
 * Class m230317_174723_add_module_types
 */
class m230317_174723_add_module_types extends Migration
{
    /**
     * {@inheritdoc}
     */



    public function safeUp()
    {
      /*  $this->addColumn('application','expert_module_type',$this->string());
        $this->addColumn('expert_modules','type',$this->string(15));
        $this->addColumn('expert_tabs','module_id',$this->integer());
        $this->addColumn('expert_form_list','module_id',$this->integer());
        $this->addColumn('expert_form_list','id_tabs',$this->text());

        $this->addForeignKey(
            'fk-post-author_id',
            'post',
            'module_id',
            'expert_modules',
            'id',
            'CASCADE'
        );*/
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      /*  $this->dropColumn('application','expert_module_type');
        $this->dropColumn('expert_modules','type');
        $this->dropColumn('expert_tabs','module_id');
        $this->dropColumn('expert_form_list','module_id');
        $this->dropColumn('expert_form_list','id_tabs');*/
    }


}
