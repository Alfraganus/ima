<?php

namespace common\models\forms\mktu;

use common\models\forms\FormMktu;
use Yii;

/**
 * This is the model class for table "form_mktu_children".
 *
 * @property int $id
 * @property int|null $form_mktu_id
 * @property int|null $content_type_id
 * @property int|null $mktu_header_id
 * @property int|null $mktu_product_id
 *
 * @property FormMktu $formMktu
 * @property MktuHeader $mktuHeader
 * @property MktuProduct $mktuProduct
 */
class FormMktuChildren extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_mktu_children';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['form_mktu_id', 'content_type_id', 'mktu_header_id', 'mktu_product_id'], 'integer'],
            [['form_mktu_id'], 'exist', 'skipOnError' => true, 'targetClass' => FormMktu::class, 'targetAttribute' => ['form_mktu_id' => 'id']],
            [['mktu_header_id'], 'exist', 'skipOnError' => true, 'targetClass' => MktuHeader::class, 'targetAttribute' => ['mktu_header_id' => 'id']],
            [['mktu_product_id'], 'exist', 'skipOnError' => true, 'targetClass' => MktuProduct::class, 'targetAttribute' => ['mktu_product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'form_mktu_id' => Yii::t('app', 'Form Mktu ID'),
            'content_type_id' => Yii::t('app', 'Content Type ID'),
            'mktu_header_id' => Yii::t('app', 'Mktu Header ID'),
            'mktu_product_id' => Yii::t('app', 'Mktu Product ID'),
        ];
    }

    /**
     * Gets query for [[FormMktu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFormMktu()
    {
        return $this->hasOne(FormMktu::class, ['id' => 'form_mktu_id']);
    }

    /**
     * Gets query for [[MktuHeader]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMktuHeader()
    {
        return $this->hasOne(MktuHeader::class, ['id' => 'mktu_header_id']);
    }

    /**
     * Gets query for [[MktuProduct]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMktuProduct()
    {
        return $this->hasOne(MktuProduct::class, ['id' => 'mktu_product_id']);
    }
}
