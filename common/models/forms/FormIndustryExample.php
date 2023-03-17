<?php

namespace common\models\forms;

use common\models\Application;
use common\models\ApplicationForm;
use common\models\ApplicationFormMedia;
use common\models\ApplicationWizard;
use common\models\query\ApplicationQuery;
use common\models\query\ApplicationWizardQuery;
use common\models\User;
use common\models\UserApplications;
use Yii;

/**
 * This is the model class for table "form_industry_example".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $user_application_id
 * @property int|null $user_application_wizard_id
 * @property string|null $title
 * @property string|null $file
 * @property User $user
 * @property Application $userApplication
 * @property ApplicationWizard $userApplicationWizard
 */
class FormIndustryExample extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_industry_example';
    }

    const CLASS_FORM_ID = 3;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'user_application_id', 'user_application_wizard_id'], 'integer'],
            [['title', 'file'], 'string', 'max' => 255],
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
            'title' => Yii::t('app', 'Title'),
            'file' => Yii::t('app', 'File'),
        ];
    }

    public function fields()
    {
        return [
            'form_id' => function () {
                return ApplicationForm::findOne(['form_class'=>get_called_class()])->id;
            },
            'title',
            'file' => function () {
                return ApplicationFormMedia::find()->where([
                    'user_id' => $this->user_id,
                    'wizard_id' => $this->user_application_wizard_id,
                    'form_id' => self::CLASS_FORM_ID,
                ])->select(['id', 'file_name', 'file_path'])->all();
            }
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[UserApplication]].
     *
     * @return \yii\db\ActiveQuery|ApplicationQuery
     */
    public function getUserApplication()
    {
        return $this->hasOne(Application::class, ['id' => 'user_application_id']);
    }

    /**
     * Gets query for [[UserApplicationWizard]].
     *
     * @return \yii\db\ActiveQuery|ApplicationWizardQuery
     */
    public function getUserApplicationWizard()
    {
        return $this->hasOne(ApplicationWizard::class, ['id' => 'user_application_wizard_id']);
    }

    /**
     * {@inheritdoc}
     * @return FormQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FormQuery(get_called_class());
    }
}
