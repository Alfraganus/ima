<?php

namespace common\models\forms;

use common\models\Application;
use common\models\ApplicationForm;
use common\models\ApplicationFormMedia;
use common\models\ApplicationWizard;
use common\models\User;
use common\models\UserApplications;
use frontend\models\ImaUsers;
use frontend\modules\api\service\FormReadService;
use Yii;

/**
 * This is the model class for table "form_confirmation".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $user_application_id
 * @property int|null $user_application_wizard_id
 * @property int|null $is_confirmed
 * @property string|null $confirmed_date
 *
 * @property User $user
 * @property Application $userApplication
 * @property ApplicationWizard $userApplicationWizard
 */
class FormConfirmation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_confirmation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'user_application_id', 'user_application_wizard_id', 'is_confirmed'], 'integer'],
            [['confirmed_date'], 'safe'],
            [['user_application_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserApplications::class, 'targetAttribute' => ['user_application_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => ImaUsers::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'is_confirmed' => Yii::t('app', 'Is Confirmed'),
            'confirmed_date' => Yii::t('app', 'Confirmed Date'),
        ];
    }


    public function beforeSave($insert)
    {
        $formRequester = FormRequester::findOne([
            'user_id' => $this->user_id,
            'user_application_id' => $this->user_application_id
        ]);
        $paymentForm = ApplicationFormMedia::findOne([
            'user_application_id' => $this->user_application_id,
            'form_id' => FormReadService::getFormIdByClass('common\models\forms\FormPayment'),
        ]);

        if ($formRequester->individual_type == 2 && $paymentForm) {
            (new FormPayment())->finishApplication($this->user_id, $this->user_application_id);
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public static function run($user_id, $application_id, $wizard_id, $form_id = null)
    {
        return self::findAll([
            'user_application_id' => $application_id,
            'user_id' => $user_id,
            'user_application_wizard_id' => $wizard_id,
        ]);
    }

    public function fields()
    {
        return [
            'form_id' => function () {
                return ApplicationForm::findOne(['form_class' => get_called_class()])->id;
            },
            'id',
            'user_id',
            'user_application_id',
            'user_application_wizard_id',
            'is_confirmed',
            'confirmed_date',
        ];
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
