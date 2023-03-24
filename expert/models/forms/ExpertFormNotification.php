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
 * This is the model class for table "expert_form_notification".
 *
 * @property int $id
 * @property int|null $expert_id
 * @property int|null $user_id
 * @property int|null $application_id
 * @property int|null $user_application_id
 * @property int|null $module_id
 * @property int|null $tab_id
 * @property int|null $notification_type
 * @property int|null $department
 * @property string|null $sent_date
 * @property string|null $application_identification
 *
 * @property Application $application
 * @property ExpertUser $expert
 * @property ExpertModules $module
 * @property ExpertTabs $tab
 * @property ImaUsers $user
 * @property UserApplications $userApplication
 */
class ExpertFormNotification extends \yii\db\ActiveRecord implements FormInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expert_form_notification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expert_id', 'user_id', 'application_id', 'user_application_id', 'module_id', 'tab_id', 'notification_type', 'department'], 'integer'],
            [['sent_date'], 'safe'],
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
            'notification_type' => Yii::t('app', 'Notification Type'),
            'department' => Yii::t('app', 'Department'),
            'sent_date' => Yii::t('app', 'Sent Date'),
            'application_identification' => Yii::t('app', 'Application Identification'),
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
            'notification_type' => function () {
                return self::notificationTabTypeList($this->tab_id, $this->notification_type);
            },
            'department' => function () {
                return self::departmentList($this->tab_id,$this->department);
            },
            'sent_date' => function () {
                return date('d-m-Y', strtotime($this->sent_date));
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

    public static function notificationTabTypeList($tab, $data_id = null)
    {
        switch ($tab) {
            case 2:
            case 1:
                $notifications = [
                    1 => 'О принятии доп.материалов (05-шакл)',
                    2 => 'О внесении изменений (06-шакл)',
                    3 => 'О продлении срока предоставления ответа (07-шакл)',
                    4 => 'О восстановлении пропущенного срока (08-шакл)',
                    5 => 'О разделении заявки (09-шакл)',
                ];
                break;
            case 4:
            case 3:
                $notifications = [
                    1 => 'О внесении изменений',
                    2 => 'О не предоставлении плат.док. за регистрацию',
                    3 => 'О не предоставлении плат.док. за продление',
                ];
                break;
        }
        return $data_id ? $notifications[$data_id] : $notifications;
    }

    public static function departmentList($tab_id, $data_id=null)
    {
        switch ($tab_id) {
            case 1:
            case 2:
                $data = [
                    1 => 'Отдел экспертизы',
                    2 => 'Госреестр',
                ];
                break;
            case 3:
            case 4:
                $data = [
                    1 => 'Госреестр',
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
