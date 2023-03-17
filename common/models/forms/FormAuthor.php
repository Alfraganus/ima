<?php

namespace common\models\forms;

use common\models\ApplicationForm;
use Yii;

/**
 * This is the model class for table "author_application".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $user_application_id
 * @property int|null $user_application_wizard_id
 * @property int|null $author_country_code
 * @property string|null $jshshir
 * @property string|null $full_name
 * @property int|null $region
 * @property int|null $district
 * @property string|null $address
 * @property string|null $workplace
 * @property string|null $position
 * @property string|null $academic_degree
 */
class FormAuthor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_author';
    }
    const CLASS_FORM_ID = 2;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'user_application_id', 'user_application_wizard_id', 'author_country_code', 'region', 'district'], 'integer'],
            [['jshshir', 'full_name', 'address', 'workplace', 'position'], 'string', 'max' => 255],
            [['academic_degree'], 'string', 'max' => 150],
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
            'user_application_id' => Yii::t('app', 'User Application ID'),
            'user_application_wizard_id' => Yii::t('app', 'User Application Wizard ID'),
            'author_country_code' => Yii::t('app', 'Author Country Code'),
            'jshshir' => Yii::t('app', 'Jshshir'),
            'full_name' => Yii::t('app', 'Full Name'),
            'region' => Yii::t('app', 'Region'),
            'district' => Yii::t('app', 'District'),
            'address' => Yii::t('app', 'Address'),
            'workplace' => Yii::t('app', 'Workplace'),
            'position' => Yii::t('app', 'Position'),
            'academic_degree' => Yii::t('app', 'Academic Degree'),
        ];
    }

    public function fields()
    {
        return [
            'form_id' => function () {
                return ApplicationForm::findOne(['form_class'=>get_called_class()])->id;
            },
            'id',
            'user_id',
            'user_application_id',
            'user_application_wizard_id',
            'author_country_code',
            'jshshir',
            'full_name',
            'region',
            'district',
            'address',
            'workplace',
            'position',
            'academic_degree',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\AuthorApplicationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\AuthorApplicationQuery(get_called_class());
    }
}
