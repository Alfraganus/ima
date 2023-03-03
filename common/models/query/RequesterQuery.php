<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\FormRequester]].
 *
 * @see \common\models\forms\FormRequester
 */
class RequesterQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\forms\FormRequester[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\forms\FormRequester|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
