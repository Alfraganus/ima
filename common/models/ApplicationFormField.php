<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "application_form_field".
 *
 * @property int $id
 * @property int|null $form_id
 * @property string|null $field_name
 * @property string|null $data_type
 * @property int|null $is_compulsory
 *
 * @property ApplicationForm $form
 */
class ApplicationFormField extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application_form_field';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['form_id', 'is_compulsory'], 'integer'],
            [['field_name'], 'string', 'max' => 100],
            [['data_type'], 'string', 'max' => 255],
            [['form_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationForm::class, 'targetAttribute' => ['form_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'form_id' => Yii::t('app', 'Form ID'),
            'field_name' => Yii::t('app', 'Field Name'),
            'data_type' => Yii::t('app', 'Data Type'),
            'is_compulsory' => Yii::t('app', 'Is Compulsory'),
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
     * {@inheritdoc}
     * @return \common\models\query\ApplicationFormFieldQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\ApplicationFormFieldQuery(get_called_class());
    }
}
