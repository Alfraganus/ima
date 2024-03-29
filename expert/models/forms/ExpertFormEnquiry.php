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
 * This is the model class for table "expert_form_enquiry".
 *
 * @property int $id
 * @property int|null $expert_id
 * @property int|null $user_id
 * @property int|null $application_id
 * @property int|null $user_application_id
 * @property int|null $module_id
 * @property int|null $is_sent
 * @property int|null $tab_id
 * @property int|null $type_enquiry
 * @property int|null $department
 * @property string|null $sent_date
 * @property string|null $recommended_respond_date
 * @property string|null $application_identification
 * @property string|null $date_respond
 *
 * @property Application $application
 * @property ExpertUser $expert
 * @property ExpertModules $module
 * @property ExpertTabs $tab
 * @property ImaUsers $user
 * @property UserApplications $userApplication
 */
class ExpertFormEnquiry extends \yii\db\ActiveRecord implements FormInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expert_form_enquiry';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expert_id','is_sent', 'user_id', 'application_id', 'user_application_id', 'module_id', 'tab_id', 'type_enquiry', 'department'], 'integer'],
            [['sent_date', 'recommended_respond_date', 'date_respond'], 'safe'],
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
            'type_enquiry' => Yii::t('app', 'Type Enquiry'),
            'department' => Yii::t('app', 'Department'),
            'sent_date' => Yii::t('app', 'Sent Date'),
            'recommended_respond_date' => Yii::t('app', 'Recommended Respond Date'),
            'application_identification' => Yii::t('app', 'Application Identification'),
            'date_respond' => Yii::t('app', 'Date Respond'),
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
            'type_enquiry' => function () {
                return [
                    'id'=>$this->type_enquiry,
                    'name'=>self::enquiryListTab($this->tab_id, $this->type_enquiry),
                ];
            },
            'department' => function () {
                return [
                    'id'=>$this->department,
                    'name'=>ExpertFormNotification::departmentList($this->tab_id, $this->department),
                ];
            },
            'sent_date' => function () {
                return date('d-m-Y', strtotime($this->sent_date));
            },
            'recommended_respond_date' => function () {
                return date('d-m-Y', strtotime($this->recommended_respond_date));
            },
            'date_respond' => function () {
                return date('d-m-Y', strtotime($this->date_respond));
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

    public static function enquiryListTab($tab_id, $data_id = null)
    {
        switch ($tab_id) {
            case 1:
                $data = [
                    1 => '§2 Правил (03-шакл)',
                    2 => 'п. 16 Правил (03-шакл)',
                    3 => 'п. 20 Правил (03-шакл)',
                    4 => 'п. 25 Правил (03-шакл)',
                    5 => 'п. 26 Правил (03-шакл)',
                    6 => 'п. 9 Правил (03-шакл)',
                    7 => 'п. 7 Правил (03-шакл)',
                    8 => 'п. 86 и пп. «а» п. 12 Правил (03-шакл)',
                    9 => 'п. 13 Правил (03-шакл)"',
                ];
                break;
            case 2:
                $data = [
                    1 => 'ч. 1 ст. 9 Закона (11-шакл)',
                    2 => 'пп 1-4 ст. 10 Закона (12-шакл)',
                    3 => 'пп 5-8 ст. 10 Закона (13-шакл)',
                    4 => 'пп 9-12 ст. 10 Закона (14-шакл)',
                    5 => 'пп 13 ст. 10 Закона (15-шакл)',
                    6 => 'п. 4 Правил (16-шакл)',
                    7 => 'п. 93 Правил (17-шакл)',
                    8 => 'п. 97 Правил (18-шакл)',
                ];
                break;
            case 3:
            case 4:
                $data = [
                    1 => 'Запрос',
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
