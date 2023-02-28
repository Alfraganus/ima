<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "application".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 *
 * @property ApplicationWizard[] $applicationWizards
 */
class Application extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['name'], 'string', 'max' => 200],
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
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * Gets query for [[ApplicationWizards]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ApplicationWizardQuery
     */
    public function getApplicationWizards()
    {
        return $this->hasMany(ApplicationWizard::class, ['application_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\ApplicationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\ApplicationQuery(get_called_class());
    }
}
