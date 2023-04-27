<?php

namespace common\models\forms;

use common\models\Application;
use common\models\ApplicationForm;
use common\models\ApplicationFormMedia;
use common\models\ApplicationWizard;
use common\models\User;
use common\models\UserApplications;
use Yii;

/**
 * This is the model class for table "form_product_symbol".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $user_application_id
 * @property int|null $user_application_wizard_id
 * @property int|null $type_product_symbol
 * @property string|null $symbol_description
 * @property string|null $color_harmony
 * @property string|null $character_transliteration
 * @property int|null $is_community_symbol
 *
 * @property User $user
 * @property Application $userApplication
 * @property ApplicationWizard $userApplicationWizard
 */
class FormProductSymbol extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_product_symbol';
    }
    const CLASS_FORM_ID = 5;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'user_application_id', 'user_application_wizard_id', 'type_product_symbol', 'is_community_symbol'], 'integer'],
            [['symbol_description', 'color_harmony', 'character_transliteration'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['user_application_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserApplications::class, 'targetAttribute' => ['user_application_id' => 'id']],
            [['user_application_wizard_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationWizard::class, 'targetAttribute' => ['user_application_wizard_id' => 'id']],
        ];
    }

     public static function run($user_id, $application_id, $wizard_id,$form_id=null)
    {
        return self::findAll([
            'user_application_id'=>$application_id,
            'user_id' => $user_id,
            'user_application_wizard_id' =>$wizard_id,
        ]);
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
            'type_product_symbol' => Yii::t('app', 'Type Product Symbol'),
            'symbol_description' => Yii::t('app', 'Symbol Description'),
            'color_harmony' => Yii::t('app', 'Color Harmony'),
            'character_transliteration' => Yii::t('app', 'Character Transliteration'),
            'is_community_symbol' => Yii::t('app', 'Is Community Symbol'),
        ];
    }

    public function fields()
    {
        return [
            'form_id' => function () {
                return $this->getFormId();
            },
            'id',
            'type_product_symbol',
            'symbol_description',
            'color_harmony',
            'character_transliteration',
            'file' => function () {
                return ApplicationFormMedia::find()->where([
                    'application_id'=>$this->user_application_id,
                    'user_id' => $this->user_id,
                    'wizard_id' => $this->user_application_wizard_id,
                    'form_id' => $this->getFormId(),
                ])->select(['id', 'file_name', 'file_path'])->all();
            }
        ];
    }

    public function getFormId()
    {
        return ApplicationForm::findOne(['form_class'=>get_called_class()])->id;
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
     * Gets query for [[UserApplication]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ApplicationQuery
     */
    public function getUserApplication()
    {
        return $this->hasOne(UserApplications::class, ['id' => 'user_application_id']);
    }

    /**
     * Gets query for [[UserApplicationWizard]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ApplicationWizardQuery
     */
    public function getUserApplicationWizard()
    {
        return $this->hasOne(ApplicationWizard::class, ['id' => 'user_application_wizard_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\FormProductSymbolQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\FormProductSymbolQuery(get_called_class());
    }
}
