<?php

namespace expert\models;

use expert\models\forms\ExpertFormList;
use expert\modules\v1\services\ApplicationChatService;
use Yii;

/**
 * This is the model class for table "application_chat".
 *
 * @property int $id
 * @property int|null $user_application_id
 * @property int|null $expert_id
 * @property int|null $sender_is_expert
 * @property int|null $expert_form_type_id
 * @property int|null $expert_form_id
 * @property string|null $datetime
 * @property int|null $chat_order_number
 *
 * @property ExpertUser $expert
 * @property ExpertFormList $expertFormType
 */
class ApplicationChat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application_chat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_application_id', 'expert_id', 'sender_is_expert', 'expert_form_type_id', 'expert_form_id', 'chat_order_number'], 'integer'],
            [['datetime'], 'safe'],
            [['expert_form_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpertFormList::class, 'targetAttribute' => ['expert_form_type_id' => 'id']],
            [['expert_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpertUser::class, 'targetAttribute' => ['expert_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_application_id' => Yii::t('app', 'User Application ID'),
            'expert_id' => Yii::t('app', 'Expert ID'),
            'sender_is_expert' => Yii::t('app', 'Sender Is Expert'),
            'expert_form_type_id' => Yii::t('app', 'Expert Form Type ID'),
            'expert_form_id' => Yii::t('app', 'Expert Form ID'),
            'datetime' => Yii::t('app', 'Datetime'),
            'chat_order_number' => Yii::t('app', 'Chat Order Number'),
        ];
    }

    public function setMaxOrderId()
    {
        $getUserMaxOrder = self::find()
            ->where(['user_application_id' => $this->user_application_id])
            ->max('chat_order_number');
        $order = 1;
        if ($getUserMaxOrder < 1) {
            $order = 1;
        } else {
            $order++;
        }
        $this->chat_order_number = $order;
    }


    public function fields()
    {
        return [
            'id',
            'user_application_id',
            'expert_id',
            'sender_is_expert',
            'expert_form_type_id',
            'expert_form_id',
            'datetime',
            'chat_order_number',
        ];
    }

    /**
     * Gets query for [[Expert]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpert()
    {
        return $this->hasOne(ExpertUser::class, ['id' => 'expert_id']);
    }

    /**
     * Gets query for [[ExpertFormType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpertFormType()
    {
        return $this->hasOne(ExpertFormList::class, ['id' => 'expert_form_type_id']);
    }
}
