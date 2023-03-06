<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\FormProductSymbol]].
 *
 * @see \common\models\FormProductSymbol
 */
class FormProductSymbolQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\FormProductSymbol[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\FormProductSymbol|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
