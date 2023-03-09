<?php

namespace expert\models\application;

use Yii;

/**
 * This is the model class for table "expert_tabs".
 *
 * @property int $id
 * @property string|null $tab_name
 *
 * @property ExpertApplicationModulesTabs[] $expertApplicationModulesTabs
 */
class ExpertTabs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expert_tabs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tab_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tab_name' => Yii::t('app', 'Tab Name'),
        ];
    }

    /**
     * Gets query for [[ExpertApplicationModulesTabs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpertApplicationModulesTabs()
    {
        return $this->hasMany(ExpertApplicationModulesTabs::class, ['tab_id' => 'id']);
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
