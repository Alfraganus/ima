<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\FormAuthor]].
 *
 * @see \common\models\forms\FormAuthor
 */
class AuthorApplicationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\forms\FormAuthor[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\forms\FormAuthor|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
