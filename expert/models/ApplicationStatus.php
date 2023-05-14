<?php

namespace expert\models;

use Yii;

/**
 * This is the model class for table "application_status".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 *
 * @property ApplicationStatusManagement[] $applicationStatusLogs
 * @property UserApplications[] $userApplications
 */
class ApplicationStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 500],
            [['description'], 'string', 'max' => 255],
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
     * Gets query for [[ApplicationStatusLogs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationStatusLogs()
    {
        return $this->hasMany(ApplicationStatusManagement::class, ['status_id' => 'id']);
    }

    /**
     * Gets query for [[UserApplications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserApplications()
    {
        return $this->hasMany(UserApplications::class, ['status_id' => 'id']);
    }
}
