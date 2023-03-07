<?php

namespace common\models\forms\mktu;

use Yii;

/**
 * This is the model class for table "mktu_header".
 *
 * @property int $id
 * @property int|null $class_id
 * @property string|null $name
 *
 * @property MktuClass $class
 * @property FormMktuChildren[] $formMktuChildrens
 */
class MktuHeader extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mktu_header';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['class_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['class_id'], 'exist', 'skipOnError' => true, 'targetClass' => MktuClass::class, 'targetAttribute' => ['class_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'class_id' => Yii::t('app', 'Class ID'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * Gets query for [[Class]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClass()
    {
        return $this->hasOne(MktuClass::class, ['id' => 'class_id']);
    }

    /**
     * Gets query for [[FormMktuChildrens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFormMktuChildrens()
    {
        return $this->hasMany(FormMktuChildren::class, ['mktu_header_id' => 'id']);
    }
}
