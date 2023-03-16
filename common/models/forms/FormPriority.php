<?php

namespace common\models\forms;

use common\models\Application;
use common\models\ApplicationWizard;
use common\models\User;
use common\models\UserApplications;
use Yii;

/**
 * This is the model class for table "form_priority".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $user_application_id
 * @property int|null $user_application_wizard_id
 * @property int|null $priority_type
 * @property string|null $questionnaire_number
 * @property string|null $requested_data
 * @property int|null $country_id
 *
 * @property User $user
 * @property Application $userApplication
 * @property ApplicationWizard $userApplicationWizard
 */
class FormPriority extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_priority';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'user_application_id', 'user_application_wizard_id', 'priority_type', 'country_id'], 'integer'],
            [['requested_data'], 'safe'],
            [['questionnaire_number'], 'string', 'max' => 255],
            [['user_application_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserApplications::class, 'targetAttribute' => ['user_application_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['user_application_wizard_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationWizard::class, 'targetAttribute' => ['user_application_wizard_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'user_application_id' => Yii::t('app', 'User Application ID'),
            'user_application_wizard_id' => Yii::t('app', 'User Application Wizard ID'),
            'priority_type' => Yii::t('app', 'Priority Type'),
            'questionnaire_number' => Yii::t('app', 'Questionnaire Number'),
            'requested_data' => Yii::t('app', 'Requested Data'),
            'country_id' => Yii::t('app', 'Country ID'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
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

    /**
     * Gets query for [[UserApplicationWizard]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserApplicationWizard()
    {
        return $this->hasOne(ApplicationWizard::class, ['id' => 'user_application_wizard_id']);
    }
}
