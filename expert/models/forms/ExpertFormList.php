<?php

namespace expert\models\forms;

use Yii;

/**
 * This is the model class for table "expert_form_list".
 *
 * @property int $id
 * @property string|null $form_name
 * @property string|null $form_class
 * @property string|null $operation_function
 */
class ExpertFormList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expert_form_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['form_name', 'form_class'], 'string', 'max' => 200],
            [['operation_function'], 'string', 'max' => 100],
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
            'form_class' => Yii::t('app', 'Form Class'),
            'operation_function' => Yii::t('app', 'Operation Function'),
        ];
    }


}
