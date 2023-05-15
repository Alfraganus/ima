<?php

namespace expert\models\forms;

use common\components\FormComponent;
use common\models\Application;
use common\models\UserApplications;
use expert\interfaces\FormInterface;
use expert\models\application\ExpertModules;
use expert\models\application\ExpertTabs;
use expert\models\ExpertUser;
use frontend\models\ImaUsers;
use Yii;


/**
 * This is the model class for table "expert_form_feedback".
 *
 * @property int $id
 * @property int|null $expert_id
 * @property int|null $user_id
 * @property int|null $application_id
 * @property int|null $user_application_id
 * @property int|null $module_id
 * @property int|null $is_sent
 * @property int|null $tab_id
 * @property int|null $department
 * @property string|null $feedback_date
 * @property string|null $date_recovery
 * @property string|null $application_identification
 * @property int|null $feedback_type
 *
 * @property Application $application
 * @property ExpertUser $expert
 * @property ExpertModules $module
 * @property ExpertTabs $tab
 * @property ImaUsers $user
 * @property UserApplications $userApplication
 */
class ExpertFormFeedback extends \yii\db\ActiveRecord implements FormInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expert_form_feedback';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expert_id', 'is_sent','user_id', 'application_id', 'user_application_id', 'module_id', 'tab_id', 'department', 'feedback_type'], 'integer'],
            [['feedback_date', 'date_recovery'], 'safe'],
            [['application_identification'], 'string', 'max' => 150],
            [['application_id'], 'exist', 'skipOnError' => true, 'targetClass' => Application::class, 'targetAttribute' => ['application_id' => 'id']],
            [['expert_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpertUser::class, 'targetAttribute' => ['expert_id' => 'id']],
            [['user_application_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserApplications::class, 'targetAttribute' => ['user_application_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => ImaUsers::class, 'targetAttribute' => ['user_id' => 'id']],
            [['module_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpertModules::class, 'targetAttribute' => ['module_id' => 'id']],
            [['tab_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpertTabs::class, 'targetAttribute' => ['tab_id' => 'id']],
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
            'tab_id' => Yii::t('app', 'Tab ID'),
            'department' => Yii::t('app', 'Department'),
            'feedback_date' => Yii::t('app', 'Feedback Date'),
            'date_recovery' => Yii::t('app', 'Date Recovery'),
            'application_identification' => Yii::t('app', 'Application Identification'),
            'feedback_type' => Yii::t('app', 'Feedback Type'),
        ];
    }

    public function fields()
    {
        return [
            'id',
            'expert_id',
            'user_id',
            'application_id',
            'module_id',
            'tab_id',
            'department' => function () {
                return [
                    'id'=>$this->department,
                    'name'=>ExpertFormNotification::departmentList($this->tab_id, $this->department),
                ];
            },
            'feedback_date' => function () {
                return date('d-m-Y', strtotime($this->feedback_date));
            },
            'date_recovery' => function () {
                return date('d-m-Y', strtotime($this->date_recovery));
            },
            'feedback_type' => function () {
                return [
                    'id'=>$this->feedback_type,
                    'name'=>self::feedbackTypeListTab($this->tab_id, $this->feedback_type),
                ];
            },
            'application_identification' => function () {
                return UserApplications::getApplicationOrderNumber($this->user_application_id);
            },
            'file' => function () {
                return $this->getFile();
            }
        ];
    }

    public static function getCurrentModelFormId()
    {
        return ExpertFormList::findOne(['form_class' => get_called_class()])->id;
    }

    public function getFile()
    {
        return FormComponent::getExpertFiles(
            $this->user_application_id,
            $this->user_id,
            $this->module_id,
            self::getCurrentModelFormId(),
            $this->id
        );
    }

    public function run($queryParams = null, $orderBy = false)
    {
        $query = $this->find();
        if ($queryParams && is_array($queryParams)) {
            $query->where($queryParams);
        }
        if ($orderBy) {
            $query->orderBy('id DESC');
        }

        return $query->all();
    }


    public static function feedbackTypeListTab($tab_id, $data_id=null)
    {
        switch ($tab_id) {
            case 1:
            case 2:
                $data = [
                    1 => 'Уведомление об отзыве (10-шакл)',
                ];
                break;
        }
        return $data_id ? $data[$data_id] : $data;
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
     * Gets query for [[Tab]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTab()
    {
        return $this->hasOne(ExpertTabs::class, ['id' => 'tab_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(ImaUsers::class, ['id' => 'user_id']);
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
