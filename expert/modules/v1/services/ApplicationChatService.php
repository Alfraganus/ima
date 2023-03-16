<?php

namespace expert\modules\v1\services;

use common\models\UserApplications;
use expert\models\ApplicationChat;
use expert\models\forms\ExpertFormDecision;
use expert\models\forms\ExpertFormList;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class ApplicationChatService extends Model
{
    public function sendMessage($expert_id, $data)
    {
        $model = new ApplicationChat();
        $model->setAttributes($data);
        $model->setMaxOrderId();
        $model->expert_id = $expert_id;
        if (!$model->save()) {
            throw new \Exception(json_encode($model->errors));
        }
        return [
            'success'=>true,
            'message'=>'Message has been successfully sent!'
        ];
    }

    public function sendUserMessage($data,$attachment)
    {
        $model = new ApplicationChat();
        $model->setAttributes($data);
        $model->setMaxOrderId();
        if ($attachment != null) {
            $fileName = time() . $attachment->getBaseName() . '.' . $attachment->getExtension();
            $fileName = 'form_uploads/' . $fileName;
            $attachment->saveAs($fileName);
            $model->user_file = $fileName;
        }
        if (!$model->save()) {
            throw new \Exception(json_encode($model->errors));
        }
        return [
          'success'=>true,
          'message'=>'Message has been successfully sent!'
        ];
    }

    public function getFormMessage($user_application_id,$expert=true)
    {
        $applicationChat = ApplicationChat::findAll(['user_application_id' => $user_application_id]);
        $result = [];
        foreach ($applicationChat as $chat) {
            if ($chat->sender_is_expert) {
                $result[] = $this->getExpertMessage($chat);
            } else {
                $result[] = $this->getUserMessage($chat);
            }
        }
        return $result;
    }

    private function getExpertMessage($chat)
    {
        $getFormType = ExpertFormList::findOne($chat->expert_form_type_id);
        if (Yii::createObject($getFormType->form_class) instanceof ExpertFormDecision) {
            return $this->expertFormDecision(
                $getFormType->form_class,
                $chat->expert_form_id,
                $chat->user_application_id,
                $chat->chat_order_number,
                $chat->sender_is_expert,
            );
        }
    }

    private function getUserMessage($chat)
    {
        return [
            'is_expert'=>$chat->sender_is_expert,
            'title'=>$chat->user_message,
            'type_application' => $chat->userApplication->application->name,
            'generated_number' => sprintf("%s/%d", $chat->userApplication->generated_id, $chat->chat_order_number),
            'date_time' => date('d-m-Y', strtotime($chat->datetime)),
            'file' => $chat->user_file,
        ];
    }


    private function expertFormDecision($getFormClass, $form_id, $user_application_id, $orderId,$is_expert)
    {
        $formModel = $getFormClass::findone(['user_application_id' => $user_application_id, 'id' => $form_id]);
        switch ($formModel->tab_id) {
            case  1 :
                return [
                    'is_expert'=>$is_expert,
                    'title' => ExpertFormDecision::decisionTypeTabOne($formModel->decision_type),
                    'type_application' => $formModel->application->name,
                    'generated_number' => sprintf("%s/%d", $formModel->userApplication->generated_id, $orderId),
                    'date_time' => date('d-m-Y', strtotime($formModel->accepted_date)),
                    'file' => $formModel->file,
                ];
        }
    }

}
