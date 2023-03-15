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

    public function sendMessage($expert_id,$data)
    {
        return $this->getFormMessage(1,14);
        $model = new ApplicationChat();
        $model->setAttributes($data);
        $model->expert_id = $expert_id;
        if(!$model->save()) {
            throw new \Exception(json_encode($model->errors));
        }
//      return  $getApplicationInfo = UserApplications::findOne($data['user_application_id']);
    }

    public function getFormMessage($form_type_id,$form_id)
    {
        $getFormType = ExpertFormList::findOne($form_type_id);
        if (Yii::createObject($getFormType->form_class) instanceof ExpertFormDecision) {
            $formModel = $getFormType->form_class::findone($form_id);
            switch ($formModel->tab_id) {
                case  1 :
                    return ExpertFormDecision::decisionTypeTabOne();
            }
        }
    }

    private function getFormClass()
    {

    }

}
