<?php

namespace expert\models\forms;

use common\models\Application;
use common\models\ApplicationForm;
use common\models\User;
use common\models\UserApplications;
use expert\models\application\ExpertModules;
use expert\models\application\ExpertTabs;
use expert\models\ExpertUser;
use expert\modules\v1\services\ReadFormService;
use Yii;

/**
 * This is the model class for table "expert_form_10".
 *
 * @property int $id
 * @property int|null $expert_id
 * @property int|null $user_id
 * @property int|null $application_id
 * @property int|null $user_application_id
 * @property int|null $module_id
 * @property string|null $column_11
 * @property string|null $column_19
 * @property string|null $column_15
 * @property string|null $column_18
 *
 * @property Application $application
 * @property ExpertUser $expert
 * @property ExpertModules $module
 * @property User $user
 * @property UserApplications $userApplication
 */
class ExpertForm10 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expert_form_10';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expert_id', 'user_id', 'application_id', 'user_application_id', 'module_id'], 'integer'],
            [['column_15', 'column_18'], 'safe'],
            [['column_11'], 'string', 'max' => 50],
            [['column_19'], 'string', 'max' => 20],
            [['application_id'], 'exist', 'skipOnError' => true, 'targetClass' => Application::class, 'targetAttribute' => ['application_id' => 'id']],
            [['expert_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpertUser::class, 'targetAttribute' => ['expert_id' => 'id']],
            [['module_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpertModules::class, 'targetAttribute' => ['module_id' => 'id']],
            [['user_application_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserApplications::class, 'targetAttribute' => ['user_application_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'expert_id' => Yii::t('app', 'Expert ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'application_id' => Yii::t('app', 'Application ID'),
            'user_application_id' => Yii::t('app', 'User Application ID'),
            'module_id' => Yii::t('app', 'Module ID'),
            'column_11' => Yii::t('app', 'Column 11'),
            'column_19' => Yii::t('app', 'Column 19'),
            'column_15' => Yii::t('app', 'Column 15'),
            'column_18' => Yii::t('app', 'Column 18'),
        ];
    }

    public function fields()
    {
        return [
            'id',
            'expert_id',
            'user_id',
            'application_id',
            'user_application_id',
            'module_id',
            'column_11',
            'column_19',
            'column_15',
            'column_18',
            'attachments' => function () {
                return ReadFormService::getAttachments(
                    $this->user_id,
                    $this->user_application_id,
                    $this->module_id,
                    $this->getFormId()
                );
            }
        ];
    }

    public function getFormId()
    {
        return ExpertFormList::findOne(['form_class' => get_called_class()])->id;
    }

    /**
     * Gets query for [[Application]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplication()
    {
        return $this->hasOne(Application::class, ['id' => 'application_id']);
    }

    /**
     * Gets query for [[Expert]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpert()
    {
        return $this->hasOne(ExpertUser::class, ['id' => 'expert_id']);
    }

    /**
     * Gets query for [[Module]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModule()
    {
        return $this->hasOne(ExpertModules::class, ['id' => 'module_id']);
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
}
