<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "application_form_media".
 *
 * @property int $id
 * @property int|null $application_id
 * @property int|null $wizard_id
 * @property int|null $form_id
 * @property int|null $user_id
 * @property string|null $file_name
 * @property string|null $file_path
 * @property string|null $file_extension
 *
 * @property Application $application
 * @property ApplicationForm $form
 * @property User $user
 * @property ApplicationWizard $wizard
 */
class ApplicationFormMedia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application_form_media';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['application_id', 'wizard_id', 'form_id', 'user_id'], 'integer'],
            [['file_name'], 'string', 'max' => 255],
            [['file_path', 'file_extension'], 'string', 'max' => 500],
            [['application_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserApplications::class, 'targetAttribute' => ['application_id' => 'id']],
            [['form_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationForm::class, 'targetAttribute' => ['form_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['wizard_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationWizard::class, 'targetAttribute' => ['wizard_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'application_id' => Yii::t('app', 'Application ID'),
            'wizard_id' => Yii::t('app', 'Wizard ID'),
            'form_id' => Yii::t('app', 'Form ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'file_name' => Yii::t('app', 'File Name'),
            'file_path' => Yii::t('app', 'File Path'),
            'file_extension' => Yii::t('app', 'File Extension'),
        ];
    }

    public function fields()
    {

        return [
            'wizard_id'=>function() {
                return $this->wizard->wizard_name;
            },
            'form_id'=>function() {
                return $this->form->form_name;
            },
            'file_name',
            'file_path',
            'file_extension',
        ];

    }

    /**
     * Gets query for [[Application]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ApplicationQuery
     */
    public function getApplication()
    {
        return $this->hasOne(UserApplications::class, ['id' => 'application_id']);
    }

    /**
     * Gets query for [[Form]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ApplicationFormQuery
     */
    public function getForm()
    {
        return $this->hasOne(ApplicationForm::class, ['id' => 'form_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Wizard]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ApplicationWizardQuery
     */
    public function getWizard()
    {
        return $this->hasOne(ApplicationWizard::class, ['id' => 'wizard_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\ApplicationFormMediaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\ApplicationFormMediaQuery(get_called_class());
    }
}
