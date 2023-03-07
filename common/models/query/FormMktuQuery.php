<?php

namespace common\models\query;

use common\models\forms\FormMktu;

/**
 * This is the ActiveQuery class for [[\common\models\forms\FormMktu]].
 *
 * @see \common\models\forms\FormMktu
 */
class FormMktuQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\forms\FormMktu[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    public static function listMktuContentType()
    {
        return [
            FormMktu::MKTU_CONTENT_TYPE_HEADER => 'Content header',
            FormMktu::MKTU_CONTENT_TYPE_PRODUCT => 'Content product',
        ];
    }

    public static function getMktuContentType($contentype)
    {
        return self::listMktuContentType()[$contentype] ?? null;
    }

    /**
     * {@inheritdoc}
     * @return \common\models\forms\FormMktu|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
