<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "world_countries".
 *
 * @property int $id
 * @property string|null $country_code
 * @property string|null $country_name
 * @property string|null $region
 */
class WorldCountries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'world_countries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_code'], 'string', 'max' => 10],
            [['country_name', 'region'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'country_code' => Yii::t('app', 'Country Code'),
            'country_name' => Yii::t('app', 'Country Name'),
            'region' => Yii::t('app', 'Region'),
        ];
    }
}
