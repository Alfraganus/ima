<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_application_content".
 *
 * @property int $id
 * @property int|null $user_application_id
 * @property int|null $user_application_wizard_id
 * @property int|null $user_application_form_id
 * @property int|null $user_application_form_field_id
 * @property string|null $user_application_form_field_key
 * @property string|null $user_application_form_field_value
 *
 * @property ApplicationFormField $userApplicationForm
 * @property ApplicationForm $userApplicationForm0
 * @property ApplicationWizard $userApplicationWizard
 */
class UserApplicationContent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_application_content';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_application_id', 'user_application_wizard_id', 'user_application_form_id', 'user_application_form_field_id'], 'integer'],
            [['user_application_form_field_value'], 'safe'],
            [['user_application_form_field_key'], 'string', 'max' => 255],
            [['user_application_form_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationFormField::class, 'targetAttribute' => ['user_application_form_id' => 'id']],
            [['user_application_form_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationForm::class, 'targetAttribute' => ['user_application_form_id' => 'id']],
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
            'user_application_id' => Yii::t('app', 'User Application ID'),
            'user_application_wizard_id' => Yii::t('app', 'User Application Wizard ID'),
            'user_application_form_id' => Yii::t('app', 'User Application Form ID'),
            'user_application_form_field_id' => Yii::t('app', 'User Application Form Field ID'),
            'user_application_form_field_key' => Yii::t('app', 'User Application Form Field Key'),
            'user_application_form_field_value' => Yii::t('app', 'User Application Form Field Value'),
        ];
    }

    /**
     * Gets query for [[UserApplicationForm]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ApplicationFormFieldQuery
     */
    public function getUserApplicationForm()
    {
        return $this->hasOne(ApplicationFormField::class, ['id' => 'user_application_form_id']);
    }

    /**
     * Gets query for [[UserApplicationForm0]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ApplicationFormQuery
     */
    public function getUserApplicationForm0()
    {
        return $this->hasOne(ApplicationForm::class, ['id' => 'user_application_form_id']);
    }

    /**
     * Gets query for [[UserApplicationWizard]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ApplicationWizardQuery
     */
    public function getUserApplicationWizard()
    {
        return $this->hasOne(ApplicationWizard::class, ['id' => 'user_application_wizard_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\UserApplicationContentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\UserApplicationContentQuery(get_called_class());
    }
}
