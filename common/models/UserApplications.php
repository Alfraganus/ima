<?php

namespace common\models;

use frontend\models\ImaUsers;
use Yii;

/**
 * This is the model class for table "user_applications".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $application_id
 * @property int|null $is_finished
 * @property int|null $payment_done
 * @property int|null $current_wizard_id
 * @property int|null $date_submitted
 * @property int|null $year
 * @property string|null $generated_id
 * @property int|null $application_number
 * @property Application $application
 * @property ApplicationWizard $currentWizard
 * @property User $user
 */
class UserApplications extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_applications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['generated_id'], 'string'],
            [['user_id', 'year','application_id', 'is_finished', 'payment_done', 'current_wizard_id', 'date_submitted'], 'integer'],
            [['application_id'], 'exist', 'skipOnError' => true, 'targetClass' => Application::class, 'targetAttribute' => ['application_id' => 'id']],
            [['current_wizard_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationWizard::class, 'targetAttribute' => ['current_wizard_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => ImaUsers::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'user_id',
            'application_id',
            'application_type'=>function() {
             return Application::findOne($this->application_id)->name;
            },
            'date'=>function() {
                return date('d-m-Y',$this->date_submitted);
            },
            'is_finished',
            'payment_done',
            'current_wizard_id',
            'date_submitted',
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
            'application_id' => Yii::t('app', 'Application ID'),
            'is_finished' => Yii::t('app', 'Is Finished'),
            'payment_done' => Yii::t('app', 'Payment Done'),
            'current_wizard_id' => Yii::t('app', 'Current Wizard ID'),
            'date_submitted' => Yii::t('app', 'Date Submitted'),
        ];
    }

    public function beforeSave($insert) {
        $this->year = date('Y');
        return parent::beforeSave($insert);
    }


    public static function getApplicationOrderNumber($user_application_id)
    {
      $userApplication =  UserApplications::findOne($user_application_id);
      return $userApplication ? $userApplication->generated_id : null;
    }

    public function getApplication()
    {
        return $this->hasOne(Application::class, ['id' => 'application_id']);
    }

    /**
     * Gets query for [[CurrentWizard]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ApplicationWizardQuery
     */
    public function getCurrentWizard()
    {
        return $this->hasOne(ApplicationWizard::class, ['id' => 'current_wizard_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(ImaUsers::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\UserApplicationsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\UserApplicationsQuery(get_called_class());
    }
}
