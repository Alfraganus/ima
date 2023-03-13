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
            [['expert_id', 'user_id', 'application_id', 'user_application_id', 'module_id', 'tab_id', 'type_enquiry', 'department'], 'integer'],
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
            'type_enquiry'=>  function() {
                return self::enquiryList($this->type_enquiry);
            },
            'department'=> function() {
                return self::departmentList($this->department);
            },
            'sent_date' => function() {
                return date('d-m-Y',strtotime($this->sent_date));
            },
            'recommended_respond_date' => function() {
                return date('d-m-Y',strtotime($this->recommended_respond_date));
            },
            'date_respond' => function() {
                return date('d-m-Y',strtotime($this->date_respond));
            },
            'application_identification',
            'file' => function () {
                return FormComponent::getExpertFiles(
                    $this->user_id,
                    $this->module_id,
                    $this->tab_id,
                    $this->id
                );
            }
        ];
    }

    public function run($queryParams=null,$orderBy=false)
    {
        $query = $this->find();
        if($queryParams && is_array($queryParams)) {
            $query->where($queryParams);
        }
        if($orderBy) {
            $query->orderBy('id DESC');
        }

        return $query->all();
    }

    public function enquiryList($data_id)
    {
        $data = [
            1 => 'enquiry type 1',
            2 => 'enquiry type 2',
            3 => 'enquiry type 3',
            4 => 'enquiry type 4',
            5 => 'enquiry type 5',
        ];
        return  $data_id ? $data[$data_id] : $data;
    }

    public function departmentList($data_id)
    {
        $data = [
            1 => 'Otdek ekspertiza',
            2 => 'Gosrestr',
        ];
        return  $data_id ? $data[$data_id] : $data;
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
