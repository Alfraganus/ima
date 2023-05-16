<?php

namespace expert\models\forms;

use common\models\Application;
use common\models\User;
use common\models\UserApplications;
use expert\models\application\ExpertModules;
use expert\models\ExpertUser;
use frontend\models\ImaUsers;
use Yii;

/**
 * This is the model class for table "expert_form_40".
 *
 * @property int $id
 * @property int|null $expert_id
 * @property int|null $user_id
 * @property int|null $application_id
 * @property int|null $user_application_id
 * @property int|null $module_id
 * @property string|null $column_46
 * @property string|null $nbull
 *
 * @property Application $application
 * @property ExpertUser $expert
 * @property ExpertModules $module
 * @property User $user
 * @property UserApplications $userApplication
 */
class ExpertForm40 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expert_form_40';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expert_id', 'user_id', 'application_id', 'user_application_id', 'module_id'], 'integer'],
            [['column_46'], 'safe'],
            [['nbull'], 'string', 'max' => 255],
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
            'user_application_id' => Yii::t('app', 'User Application ID'),
            'module_id' => Yii::t('app', 'Module ID'),
            'column_46' => Yii::t('app', 'Column 46'),
            'nbull' => Yii::t('app', 'Nbull'),
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
