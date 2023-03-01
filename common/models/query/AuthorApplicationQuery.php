<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\AuthorApplication]].
 *
 * @see \common\models\AuthorApplication
 */
class AuthorApplicationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\AuthorApplication[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\AuthorApplication|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
