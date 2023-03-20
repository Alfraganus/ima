<?php

namespace common\models\forms;

use common\models\Application;
use common\models\ApplicationForm;
use common\models\ApplicationFormMedia;
use common\models\ApplicationWizard;
use common\models\User;
use common\models\UserApplications;
use Yii;

/**
 * This is the model class for table "form_payment".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $user_application_id
 * @property int|null $user_application_wizard_id
 * @property int|null $payment_done
 * @property string|null $payment_info
 * @property string|null $payment_time
 *
 * @property User $user
 * @property Application $userApplication
 * @property ApplicationWizard $userApplicationWizard
 */
class FormPayment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'user_application_id', 'user_application_wizard_id', 'payment_done'], 'integer'],
            [['payment_info'], 'string'],
            [['payment_time', 'generated_id'], 'safe'],
            [['user_application_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserApplications::class, 'targetAttribute' => ['user_application_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['user_application_wizard_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationWizard::class, 'targetAttribute' => ['user_application_wizard_id' => 'id']],
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $maxApplicationNumber = UserApplications::find()->max('application_number');
        $model = UserApplications::findOne([
            'user_id'=>$this->user_id,
            'id'=>$this->user_application_id
        ]);
        if ($maxApplicationNumber < 1) {
            $model->application_number = 1;
        } else {
            $model->application_number = $maxApplicationNumber + 1;
        }
        $model->generated_id = $this->formatOrderNumber(sprintf('MGU%d',date('Y')),$model->application_number);
        $model->save(false);

    }
    private function formatOrderNumber($prefix, $number) {
        $numberLength = strlen((string)$number);
        switch ($numberLength) {
            case 1:
                $formattedNumber = $prefix.'0000' . $number;
                break;
            case 2:
                $formattedNumber = $prefix.'000' . $number;
                break;
            case 3:
                $formattedNumber = $prefix.'00' . $number;
                break;
            case 4:
                $formattedNumber = $prefix.'0' . $number;
                break;
            default:
                $formattedNumber = $number;
        }

        return $formattedNumber;
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
            'payment_done' => Yii::t('app', 'Payment Done'),
            'payment_info' => Yii::t('app', 'Payment Info'),
            'payment_time' => Yii::t('app', 'Payment Time'),
        ];
    }

    public function fields()
    {
        return [
            'form_id' => function () {
                return $this->getFormId();
            },
            'id',
            'user_id',
            'user_application_id',
            'user_application_wizard_id',
            'payment_done',
            'payment_info',
            'payment_time',
            'file' => function () {
                return ApplicationFormMedia::find()->where([
                    'application_id'=>$this->user_application_id,
                    'user_id' => $this->user_id,
                    'wizard_id' => $this->user_application_wizard_id,
                    'form_id' => $this->getFormId(),
                ])->select(['id', 'file_name', 'file_path'])->all();
            }
        ];
    }

    public function getFormId()
    {
        return ApplicationForm::findOne(['form_class'=>get_called_class()])->id;
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
