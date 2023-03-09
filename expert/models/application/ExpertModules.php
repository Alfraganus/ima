<?php

namespace expert\models\application;

use Yii;

/**
 * This is the model class for table "expert_modules".
 *
 * @property int $id
 * @property string|null $module_name
 *
 * @property ExpertApplicationModulesTabs[] $expertApplicationModulesTabs
 */
class ExpertModules extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expert_modules';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['module_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'module_name' => Yii::t('app', 'Module Name'),
        ];
    }

    /**
     * Gets query for [[ExpertApplicationModulesTabs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpertApplicationModulesTabs()
    {
        return $this->hasMany(ExpertApplicationModulesTabs::class, ['module_id' => 'id']);
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        // TODO: Implement toArray() method.
    }

    public static function instance($refresh = false)
    {
        // TODO: Implement instance() method.
    }
}
