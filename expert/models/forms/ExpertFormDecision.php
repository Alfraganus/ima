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
 * This is the model class for table "expert_form_decision".
 *
 * @property int $id
 * @property int|null $expert_id
 * @property int|null $user_id
 * @property int|null $application_id
 * @property int|null $user_application_id
 * @property int|null $module_id
 * @property int|null $tab_id
 * @property int|null $decision_type
 * @property string|null $application_identification
 * @property string|null $accepted_date
 * @property string|null $sent_date
 * @property string|null $expert_fullname
 *
 * @property Application $application
 * @property ExpertUser $expert
 * @property ExpertModules $module
 * @property ExpertTabs $tab
 * @property ImaUsers $user
 */
class ExpertFormDecision extends \yii\db\ActiveRecord implements FormInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expert_form_decision';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expert_id', 'user_id', 'application_id', 'module_id', 'tab_id', 'decision_type', 'user_application_id'], 'integer'],
            [['accepted_date', 'sent_date'], 'safe'],
            [['application_identification'], 'string', 'max' => 150],
            [['expert_fullname'], 'string', 'max' => 255],
            [['application_id'], 'exist', 'skipOnError' => true, 'targetClass' => Application::class, 'targetAttribute' => ['application_id' => 'id']],
            [['user_application_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserApplications::class, 'targetAttribute' => ['user_application_id' => 'id']],
            [['expert_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpertUser::class, 'targetAttribute' => ['expert_id' => 'id']],
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
            'module_id' => Yii::t('app', 'Module ID'),
            'tab_id' => Yii::t('app', 'Tab ID'),
            'decision_type' => Yii::t('app', 'Decision Type'),
            'application_identification' => Yii::t('app', 'Application Identification'),
            'accepted_date' => Yii::t('app', 'Accepted Date'),
            'sent_date' => Yii::t('app', 'Sent Date'),
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
            'tab_id',
            'expert_fullname',
            'decision_type' => function () {
               return self::decisionTypeTab($this->tab_id,$this->decision_type);
            },
            'application_identification' => function () {
             return  UserApplications::getApplicationOrderNumber($this->user_application_id);
            },
            'accepted_date' => function () {
                return date('d-m-Y', strtotime($this->accepted_date));
            },
            'sent_date' => function () {
                return date('d-m-Y', strtotime($this->sent_date));
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

    public static function decisionTypeTab($tab,$data_id=null)
    {
        switch ($tab) {
            case 1:
                $data = [
                    1 => 'Решение о принятии заявки к рассмотрению',
                    2 => 'Решение об отказе в принятии к рассмотрению заявки',
                ];
                break;
            case 2:
                $data = [
                    1 => 'Положительное (17-11-шакл)',
                    2 => 'Отрицательное (19-шакл)',
                    3 => 'Отрицательное (20-шакл)',
                    4 => 'Отрицательное (21-шакл)',
                    5 => 'Отрицательное (22-шакл)',
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

    public function getUserApplication()
    {
        return $this->hasOne(UserApplications::class, ['id' => 'user_application_id']);
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


}
