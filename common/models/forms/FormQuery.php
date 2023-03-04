<?php

namespace common\models\forms;

/**
 * This is the ActiveQuery class for [[FormIndustryExample]].
 *
 * @see FormIndustryExample
 */
class FormQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return FormIndustryExample[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return FormIndustryExample|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
