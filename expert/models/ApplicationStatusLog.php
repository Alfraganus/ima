<?php

namespace expert\models;

use common\models\UserApplications;
use Yii;

/**
 * This is the model class for table "application_status_log".
 *
 * @property int $id
 * @property int|null $user_application_id
 * @property int|null $status_id
 * @property string|null $log
 *
 * @property ApplicationStatus $status
 * @property UserApplications $userApplication
 */
class ApplicationStatusLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application_status_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_application_id', 'status_id'], 'integer'],
            [['log'], 'string', 'max' => 500],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationStatus::class, 'targetAttribute' => ['status_id' => 'id']],
            [['user_application_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserApplications::class, 'targetAttribute' => ['user_application_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_application_id' => Yii::t('app', 'User Application ID'),
            'status_id' => Yii::t('app', 'Status ID'),
            'log' => Yii::t('app', 'Log'),
        ];
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(ApplicationStatus::class, ['id' => 'status_id']);
    }

    /**
     * Gets query for [[UserApplication]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserApplication()
    {
        return $this->hasOne(UserApplications::class, ['id' => 'user_application_id']);
    }
}
