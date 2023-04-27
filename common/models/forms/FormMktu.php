<?php

namespace common\models\forms;

use common\models\Application;
use common\models\ApplicationWizard;
use common\models\forms\mktu\FormMktuChildren;
use common\models\forms\mktu\MktuClass;
use common\models\query\ApplicationQuery;
use common\models\query\ApplicationWizardQuery;
use common\models\query\FormMktuQuery;
use common\models\User;
use Yii;

/**
 * This is the model class for table "form_mktu".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $user_application_id
 * @property int|null $user_application_wizard_id
 * @property int|null $class_id
 * @property int|null $mktu_content_type
 *
 * @property MktuClass $class
 * @property FormMktuChildren[] $formMktuChildrens
 * @property User $user
 * @property Application $userApplication
 * @property ApplicationWizard $userApplicationWizard
 */
class FormMktu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_mktu';
    }
    const MKTU_CONTENT_TYPE_HEADER = 1;
    const MKTU_CONTENT_TYPE_PRODUCT = 2;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'user_application_id', 'user_application_wizard_id', 'class_id', 'mktu_content_type'], 'integer'],
            [['class_id'], 'exist', 'skipOnError' => true, 'targetClass' => MktuClass::class, 'targetAttribute' => ['class_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['user_application_id'], 'exist', 'skipOnError' => true, 'targetClass' => Application::class, 'targetAttribute' => ['user_application_id' => 'id']],
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
            'class_id' => Yii::t('app', 'Class ID'),
            'mktu_content_type' => Yii::t('app', 'Mktu Content Type'),
        ];
    }

     public static function run($user_id, $application_id, $wizard_id,$form_id=null)
    {
        return self::findAll([
            'user_application_id'=>$application_id,
            'user_id' => $user_id,
            'user_application_wizard_id' =>$wizard_id,
        ]);
    }

    /**
     * Gets query for [[Class]].
     *
     * @return \yii\db\ActiveQuery|MktuClassQuery
     */
    public function getClass()
    {
        return $this->hasOne(MktuClass::class, ['id' => 'class_id']);
    }

    /**
     * Gets query for [[FormMktuChildrens]].
     *
     * @return \yii\db\ActiveQuery|FormMktuChildrenQuery
     */
    public function getFormMktuChildrens()
    {
        return $this->hasMany(FormMktuChildren::class, ['form_mktu_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[UserApplication]].
     *
     * @return \yii\db\ActiveQuery|ApplicationQuery
     */
    public function getUserApplication()
    {
        return $this->hasOne(Application::class, ['id' => 'user_application_id']);
    }

    /**
     * Gets query for [[UserApplicationWizard]].
     *
     * @return \yii\db\ActiveQuery|ApplicationWizardQuery
     */
    public function getUserApplicationWizard()
    {
        return $this->hasOne(ApplicationWizard::class, ['id' => 'user_application_wizard_id']);
    }

    /**
     * {@inheritdoc}
     * @return FormMktuQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FormMktuQuery(get_called_class());
    }
}
