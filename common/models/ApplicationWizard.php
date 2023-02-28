<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "application_wizard".
 *
 * @property int $id
 * @property int|null $application_id
 * @property string|null $wizard_name
 * @property int|null $wizard_order
 * @property string|null $wizard_icon
 *
 * @property Application $application
 * @property WizardFormField[] $wizardFormFields
 */
class ApplicationWizard extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application_wizard';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['application_id', 'wizard_order'], 'integer'],
            [['wizard_name', 'wizard_icon'], 'string', 'max' => 200],
            [['application_id'], 'exist', 'skipOnError' => true, 'targetClass' => Application::class, 'targetAttribute' => ['application_id' => 'id']],
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
            'wizard_name' => Yii::t('app', 'Wizard Name'),
            'wizard_order' => Yii::t('app', 'Wizard Order'),
            'wizard_icon' => Yii::t('app', 'Wizard Icon'),
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
     * Gets query for [[WizardFormFields]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\WizardFormFieldQuery
     */
    public function getWizardFormFields()
    {
        return $this->hasMany(WizardFormField::class, ['wizard_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\ApplicationWizardQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\ApplicationWizardQuery(get_called_class());
    }
}
