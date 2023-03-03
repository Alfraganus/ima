<?php

namespace common\models\forms;

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
 * @property string|null $full_name
 * @property int|null $region
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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'user_application_id', 'user_application_wizard_id', 'individual_type', 'region', 'district', 'role_id'], 'integer'],
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
            'full_name' => Yii::t('app', 'Full Name'),
            'region' => Yii::t('app', 'Region'),
            'district' => Yii::t('app', 'District'),
            'submitting_address' => Yii::t('app', 'Submitting Address'),
            'receiver_name' => Yii::t('app', 'Receiver Name'),
            'sms_notification_number' => Yii::t('app', 'Sms Notification Number'),
            'role_id' => Yii::t('app', 'Role ID'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\RequesterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\RequesterQuery(get_called_class());
    }
}
