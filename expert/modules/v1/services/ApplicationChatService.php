<?php

namespace expert\modules\v1\services;

use common\models\UserApplications;
use expert\models\ApplicationChat;
use expert\models\forms\ExpertFormDecision;
use expert\models\forms\ExpertFormList;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class ApplicationChatService extends Model
{

    public function sendMessage($expert_id, $data)
    {
        return $this->getFormMessage(4);
        $model = new ApplicationChat();
        $model->setAttributes($data);
        $model->setMaxOrderId();
        $model->expert_id = $expert_id;
        if (!$model->save()) {
            throw new \Exception(json_encode($model->errors));
        }
    }

    public function getFormMessage($user_application_id)
    {
        $applicationChat = ApplicationChat::findAll(['user_application_id' => $user_application_id]);
        $result = [];
        foreach ($applicationChat as $chat) {
            if($chat->sender_is_expert) {
                $getFormType = ExpertFormList::findOne($chat->expert_form_type_id);
                if (Yii::createObject($getFormType->form_class) instanceof ExpertFormDecision) {
                    $result[] = $this->expertFormDecision(
                        $getFormType->form_class,
                        $chat->expert_form_id,
                        $chat->user_application_id,
                        $chat->chat_order_number
                    );
                }
            }

        }
        return $result;
    }

    private function expertFormDecision($getFormClass,$form_id,$user_application_id,$orderId)
    {
       $formModel = $getFormClass::findone(['user_application_id'=>$user_application_id,'id'=>$form_id]);
        switch ($formModel->tab_id) {
            case  1 :
                return [
                    'title' => ExpertFormDecision::decisionTypeTabOne($formModel->decision_type),
                    'type_application' => $formModel->application->name,
                    'generated_number' => sprintf("%s/%d",$formModel->userApplication->generated_id,$orderId),
                    'date_time' => date('d-m-Y', strtotime($formModel->accepted_date)),
                    'file' => $formModel->file,
                ];
        }
    }

}
