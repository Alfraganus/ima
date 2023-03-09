<?php

namespace common\models\forms;

use common\models\Application;
use common\models\ApplicationWizard;
use common\models\User;
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
            [['payment_time'], 'safe'],
            [['user_application_id'], 'exist', 'skipOnError' => true, 'targetClass' => Application::class, 'targetAttribute' => ['user_application_id' => 'id']],
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
            'payment_done' => Yii::t('app', 'Payment Done'),
            'payment_info' => Yii::t('app', 'Payment Info'),
            'payment_time' => Yii::t('app', 'Payment Time'),
        ];
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
        return $this->hasOne(Application::class, ['id' => 'user_application_id']);
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
