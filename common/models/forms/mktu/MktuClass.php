<?php

namespace common\models\forms\mktu;

use common\models\forms\FormMktu;
use Yii;

/**
 * This is the model class for table "mktu_class".
 *
 * @property int $id
 * @property string|null $name
 *
 * @property FormMktu[] $formMktus
 * @property MktuHeader[] $mktuHeaders
 * @property MktuProduct[] $mktuProducts
 */
class MktuClass extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mktu_class';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * Gets query for [[FormMktus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFormMktus()
    {
        return $this->hasMany(FormMktu::class, ['class_id' => 'id']);
    }

    /**
     * Gets query for [[MktuHeaders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMktuHeaders()
    {
        return $this->hasMany(MktuHeader::class, ['class_id' => 'id']);
    }

    /**
     * Gets query for [[MktuProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMktuProducts()
    {
        return $this->hasMany(MktuProduct::class, ['class_id' => 'id']);
    }
}
