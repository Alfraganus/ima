<?php

namespace common\models\forms;

use common\models\ApplicationForm;
use common\models\ApplicationFormMedia;
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
class FormIndustryDocument extends \yii\db\ActiveRecord
{

    const CLASS_FORM_ID = 4;

    public static function tableName()
    {
        return 'form_industry_document';
    }

    /**
     * {@inheritdoc}
     */


    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'documents' => Yii::t('app', 'documents'),
        ];
    }

    public function fields()
    {
        return [
            'form_id' => function () {
                return ApplicationForm::findOne(['form_class'=>get_called_class()])->id;
            },
            'id',
            'files' => function () {
                return ApplicationFormMedia::find()->where([
                    'user_id' => $this->user_id,
                    'wizard_id' => $this->user_application_wizard_id,
                    'form_id' => self::CLASS_FORM_ID,
                ])->select(['id', 'file_name', 'file_path'])->all();
            }
        ];
    }

}
