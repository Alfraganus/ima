<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "application_form".
 *
 * @property int $id
 * @property string|null $form_name
 * @property int|null $can_be_multiple
 *
 * @property WizardFormField[] $wizardFormFields
 */
class ApplicationForm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application_form';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['can_be_multiple'], 'integer'],
            [['form_name'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'form_name' => Yii::t('app', 'Form Name'),
            'can_be_multiple' => Yii::t('app', 'Can Be Multiple'),
        ];
    }

    /**
     * Gets query for [[WizardFormFields]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\WizardFormFieldQuery
     */
    public function getWizardFormFields()
    {
        return $this->hasMany(WizardFormField::class, ['form_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\ApplicationFormQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\ApplicationFormQuery(get_called_class());
    }
}
