<?php

namespace expert\models\forms;

use common\models\Application;
use common\models\Districts;
use common\models\Regions;
use common\models\UserApplications;
use common\models\WorldCountries;
use expert\models\application\ExpertModules;
use expert\models\ExpertUser;
use frontend\models\ImaUsers;
use Yii;

/**
 * This is the model class for table "expert_form_doverenniy_litso".
 *
 * @property int $id
 * @property int|null $expert_id
 * @property int|null $user_id
 * @property int|null $application_id
 * @property int|null $module_id
 * @property int|null $user_application_id
 * @property int|null $individual_type
 * @property string|null $jshshir
 * @property string|null $full_name
 * @property int|null $country_id
 * @property int|null $region
 * @property int|null $district
 * @property string|null $address
 *
 * @property Application $application
 * @property ExpertUser $expert
 * @property ExpertModules $module
 * @property UserApplications $userApplication
 */
class ExpertFormDoverenniyLitso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expert_form_doverenniy_litso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expert_id', 'user_id', 'application_id', 'module_id', 'user_application_id', 'individual_type', 'country_id', 'region', 'district'], 'integer'],
            [['address'], 'string'],
            [['jshshir', 'full_name'], 'string', 'max' => 255],
            [['application_id'], 'exist', 'skipOnError' => true, 'targetClass' => Application::class, 'targetAttribute' => ['application_id' => 'id']],
            [['expert_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpertUser::class, 'targetAttribute' => ['expert_id' => 'id']],
            [['module_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpertModules::class, 'targetAttribute' => ['module_id' => 'id']],
            [['user_application_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserApplications::class, 'targetAttribute' => ['user_application_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => ImaUsers::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'user_application_id' => Yii::t('app', 'User Application ID'),
            'individual_type' => Yii::t('app', 'Individual Type'),
            'jshshir' => Yii::t('app', 'Jshshir'),
            'full_name' => Yii::t('app', 'Full Name'),
            'country_id' => Yii::t('app', 'Country ID'),
            'address' => Yii::t('app', 'Address'),
        ];
    }

    public function fields()
    {
        return [
            'module_id',
            'user_application_id',
            'individual_type',
            'district' => function () {
                $address = Districts::findOne($this->district);
                return $address ? [
                    'id' => $this->region,
                    'name' => $address->name_uz,
                ] : [];
            },
            'region' => function () {
                $address = Regions::findOne($this->region);
                return $address ? [
                    'id' => $this->region,
                    'name' => $address->name_uz,
                ] : [];
            },
            'jshshir',
            'full_name',
            'country_id' => function () {
                $submittingCountry = WorldCountries::findOne($this->submitting_country_id);
                return [
                    'id' => $this->submitting_country_id,
                    'code' => $submittingCountry->country_code,
                    'country_name' => $submittingCountry->country_name,
                ];
            },
            'address',
        ];
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
