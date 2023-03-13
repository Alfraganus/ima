<?php

namespace expert\models;

use common\models\Application;
use common\models\User;
use common\models\UserApplications;
use expert\models\application\ExpertModules;
use expert\models\application\ExpertTabs;
use expert\models\forms\ExpertFormList;
use frontend\models\ImaUsers;
use Yii;

/**
 * This is the model class for table "expert_form_media".
 *
 * @property int $id
 * @property int|null $application_id
 * @property int|null $user_application_id
 * @property int|null $module_id
 * @property int|null $tab_id
 * @property int|null $form_id
 * @property int|null $user_id
 * @property int|null $object_id
 * @property string|null $file_name
 * @property string|null $file_path
 * @property string|null $file_extension
 *
 * @property Application $application
 * @property ExpertFormList $form
 * @property ExpertModules $module
 * @property ExpertTabs $tab
 * @property ImaUsers $user
 * @property UserApplications $userApplication
 */
class ExpertFormMedia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expert_form_media';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['application_id', 'user_application_id', 'module_id', 'tab_id', 'form_id', 'user_id','object_id'], 'integer'],
            [['file_name'], 'string', 'max' => 255],
            [['file_path', 'file_extension'], 'string', 'max' => 500],
            [['application_id'], 'exist', 'skipOnError' => true, 'targetClass' => Application::class, 'targetAttribute' => ['application_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => ImaUsers::class, 'targetAttribute' => ['user_id' => 'id']],
            [['form_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpertFormList::class, 'targetAttribute' => ['form_id' => 'id']],
            [['module_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpertModules::class, 'targetAttribute' => ['module_id' => 'id']],
            [['tab_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpertTabs::class, 'targetAttribute' => ['tab_id' => 'id']],
            [['user_application_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserApplications::class, 'targetAttribute' => ['user_application_id' => 'id']],
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
            'user_application_id' => Yii::t('app', 'User Application ID'),
            'module_id' => Yii::t('app', 'Module ID'),
            'tab_id' => Yii::t('app', 'Tab ID'),
            'form_id' => Yii::t('app', 'Form ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'file_name' => Yii::t('app', 'File Name'),
            'file_path' => Yii::t('app', 'File Path'),
            'file_extension' => Yii::t('app', 'File Extension'),
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
     * Gets query for [[Form]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getForm()
    {
        return $this->hasOne(ExpertFormList::class, ['id' => 'form_id']);
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
}
