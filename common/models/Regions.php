<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "regions".
 *
 * @property int $id
 * @property string|null $name_uz
 * @property string|null $name_oz
 * @property string|null $name_ru
 */
class Regions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'regions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_uz', 'name_oz', 'name_ru'], 'string', 'max' => 60],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'name_uz',
            'name_oz',
            'name_ru',
            'district' => function () {
                return Districts::findAll(['region_id'=>$this->id]);
            }
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name_uz' => Yii::t('app', 'Name Uz'),
            'name_oz' => Yii::t('app', 'Name Oz'),
            'name_ru' => Yii::t('app', 'Name Ru'),
        ];
    }

    public function getDistricts()
    {
        return $this->hasMany(Districts::class, ['region_id' => 'id']);
    }
}
