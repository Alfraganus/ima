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
 * This is the model class for table "expert_form_payment".
 *
 * @property int $id
 * @property int|null $expert_id
 * @property int|null $user_id
 * @property int|null $application_id
 * @property int|null $user_application_id
 * @property int|null $module_id
 * @property int|null $tab_id
 * @property int|null $payment_purpose_id
 * @property string|null $payment_date
 * @property string|null $currency
 * @property string|null $amount
 *
 * @property Application $application
 * @property ExpertUser $expert
 * @property ExpertModules $module
 * @property ExpertTabs $tab
 * @property ImaUsers $user
 * @property UserApplications $userApplication
 */
class ExpertFormPayment extends \yii\db\ActiveRecord implements FormInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expert_form_payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expert_id', 'user_id', 'application_id', 'user_application_id', 'module_id', 'tab_id', 'payment_purpose_id'], 'integer'],
            [['currency', 'amount'], 'safe'],
            [['payment_date'], 'string', 'max' => 150],
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
            'payment_purpose_id' => Yii::t('app', 'Payment Purpose ID'),
            'payment_date' => Yii::t('app', 'Payment Date'),
            'currency' => Yii::t('app', 'Currency'),
            'amount' => Yii::t('app', 'Amount'),
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
            'payment_purpose_id' => function () {
                return [
                    'id'=>$this->payment_purpose_id,
                    'name'=>$this->paymentPurposeListTab($this->tab_id,$this->payment_purpose_id),
                ];
            },
            'payment_date' => function () {
                return date('d-m-Y', strtotime($this->payment_date));
            },
            'currency' => function () {
                return [
                    'id'=>$this->currency,
                    'name'=>self::currencyList($this->currency),
                ];
            },
            'amount',
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


    public static function currencyList($currency_id = null)
    {
        $currencies = [
            1 => 'UZS',
            2 => 'USD',
            3 => 'RUB',
            4 => 'EUR',
            5 => 'CHF',
        ];
        return $currency_id ? $currencies[$currency_id] : $currencies;
    }

    public static function paymentPurposeListTab($tab_id, $payment_id = null)
    {
        switch ($tab_id) {
            case 1:
                $paymentTypes = [
                    1 => 'За подачу заявки',
                    2 => 'За внесение изменений',
                    3 => 'Доплата за подачу заявки',
                ];
                break;
            case 2:
                $paymentTypes = [
                    1 => 'За внесение изменений',
                ];
                break;
            case 3:
                $paymentTypes = [
                    1 => 'За подачу заявки',
                    2 => 'Доплата за внесение изменений',
                    3 => 'За регистрацию и выд. св.ва',
                    4 => 'Доплата за регистрацию',
                ];
                break;
            case 4:
                $paymentTypes = [
                    1 => 'Продление регистрации',
                    2 => 'Доплата за продление регистрации',
                ];
                break;
        }
        return $payment_id ? $paymentTypes[$payment_id] : $paymentTypes;
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
