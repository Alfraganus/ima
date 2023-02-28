<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "wizard_form_field".
 *
 * @property int $id
 * @property int|null $wizard_id
 * @property int|null $form_id
 * @property int|null $order_id
 *
 * @property ApplicationForm $form
 * @property ApplicationWizard $wizard
 */
class WizardFormField extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wizard_form_field';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['wizard_id', 'form_id', 'order_id'], 'integer'],
            [['form_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationForm::class, 'targetAttribute' => ['form_id' => 'id']],
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
            'wizard_id' => Yii::t('app', 'Wizard ID'),
            'form_id' => Yii::t('app', 'Form ID'),
            'order_id' => Yii::t('app', 'Order ID'),
        ];
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
     * @return \common\models\query\WizardFormFieldQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\WizardFormFieldQuery(get_called_class());
    }
}
