<?php

namespace common\models\forms;

use common\models\ApplicationForm;
use common\models\ApplicationFormMedia;
use common\models\Districts;
use common\models\Regions;
use common\models\WorldCountries;
use Yii;

/**
 * This is the model class for table "requester".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $user_application_id
 * @property int|null $user_application_wizard_id
 * @property int|null $individual_type
 * @property string|null $jshshir
 * @property string|null $stir
 * @property string|null $full_name
 * @property string|null $legal_entity_title
 * @property int|null $region
 * @property int|null $submitting_country_id
 * @property int|null $district
 * @property string|null $submitting_address
 * @property string|null $receiver_name
 * @property string|null $sms_notification_number
 * @property int|null $role_id
 */
class FormRequester extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_requester';
    }
    const ROLE_ID_OWDER=1;
    const ROLE_ID_TRUSTED=2;
    const ROLE_ID_REPRESENTATIVE=3;
    const CLASS_FORM_ID = 1;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'user_application_id','submitting_country_id', 'user_application_wizard_id', 'individual_type', 'region', 'district', 'role_id'], 'integer'],
            [['jshshir', 'full_name', 'submitting_address', 'receiver_name', 'sms_notification_number'], 'string', 'max' => 255],
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
            'individual_type' => Yii::t('app', 'Individual Type'),
            'jshshir' => Yii::t('app', 'Jshshir'),
            'stir' => Yii::t('app', 'stir'),
            'full_name' => Yii::t('app', 'Full Name'),
            'legal_entity_title' => Yii::t('app', 'legal_entity_title'),
            'region' => Yii::t('app', 'Region'),
            'district' => Yii::t('app', 'District'),
            'submitting_address' => Yii::t('app', 'Submitting Address'),
            'submitting_country_id' => Yii::t('app', 'submitting_country_id'),
            'receiver_name' => Yii::t('app', 'Receiver Name'),
            'sms_notification_number' => Yii::t('app', 'Sms Notification Number'),
            'role_id' => Yii::t('app', 'Role ID'),
        ];
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
            'individual_type',
            'jshshir',
            'stir',
            'full_name',
            'legal_entity_title',
            'district' => function () {
                $address = Districts::findOne($this->district);
             return $address ? [
                    'id' => $this->district,
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
            'submitting_address',
            'submitting_country_id' => function () {
                $submittingCountry = WorldCountries::findOne($this->submitting_country_id);
                return [
                    'id' => $this->submitting_country_id,
                    'code' => $submittingCountry->country_code,
                    'country_name' => $submittingCountry->country_name,
                ];
            },
            'receiver_name',
            'sms_notification_number',
            'role_id' => function () {
                return [
                    'id' => $this->role_id,
                    'value' =>self::$roles[$this->role_id]??null,
                ];
            },
        ];
    }

    public static function run($user_id, $application_id, $wizard_id, $form_id = null)
    {
        return self::findAll([
            'user_application_id' => $application_id,
            'user_id' => $user_id,
            'user_application_wizard_id' => $wizard_id,
        ]);
    }


    public function beforeSave($insert)
    {
        $maxId = self::find()->max('id');
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public static $roles = [
        1 => 'Huquq egasi',
        2 => 'Ichonchli vakil',
        3 => 'Patent vakili',
    ];

    /**
     * {@inheritdoc}
     * @return \common\models\query\RequesterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\RequesterQuery(get_called_class());
    }
}
