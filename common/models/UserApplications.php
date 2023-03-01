<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_applications".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $application_id
 * @property int|null $is_finished
 * @property int|null $payment_done
 * @property int|null $current_wizard_id
 * @property int|null $date_submitted
 *
 * @property Application $application
 * @property ApplicationWizard $currentWizard
 * @property User $user
 */
class UserApplications extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_applications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'application_id', 'is_finished', 'payment_done', 'current_wizard_id', 'date_submitted'], 'integer'],
            [['application_id'], 'exist', 'skipOnError' => true, 'targetClass' => Application::class, 'targetAttribute' => ['application_id' => 'id']],
            [['current_wizard_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationWizard::class, 'targetAttribute' => ['current_wizard_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'application_id' => Yii::t('app', 'Application ID'),
            'is_finished' => Yii::t('app', 'Is Finished'),
            'payment_done' => Yii::t('app', 'Payment Done'),
            'current_wizard_id' => Yii::t('app', 'Current Wizard ID'),
            'date_submitted' => Yii::t('app', 'Date Submitted'),
        ];
    }

    /**
     * Gets query for [[Application]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ApplicationQuery
     */
    public function getApplication()
    {
        return $this->hasOne(Application::class, ['id' => 'application_id']);
    }

    /**
     * Gets query for [[CurrentWizard]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ApplicationWizardQuery
     */
    public function getCurrentWizard()
    {
        return $this->hasOne(ApplicationWizard::class, ['id' => 'current_wizard_id']);
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
     * {@inheritdoc}
     * @return \common\models\query\UserApplicationsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\UserApplicationsQuery(get_called_class());
    }
}