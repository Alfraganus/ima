<?php

namespace expert\modules\v1\services;

use common\models\ApplicationFormMedia;
use common\models\UserApplications;
use expert\models\ExpertFormMedia;
use expert\models\forms\ExpertFormList;
use Yii;
use yii\helpers\ArrayHelper;
class CreateFormService
{
    private static function getAllForms()
    {
     return ArrayHelper::map(ExpertFormList::find()->asArray()->all(),'id','form_class');
    }

    private function getApplicationOwner($user_application_id)
    {
       $applicationModel = UserApplications::findOne($user_application_id);
        if(!$applicationModel) {
            throw new \Exception("Application with id $user_application_id not found");
        }
       return [
          'user_id'=>$applicationModel->user_id,
          'application_id'=>$applicationModel->application_id
        ];
    }

    public function createForm($data,$attachment, $user_id)
    {
        $forms = self::getAllForms();
        if(empty($forms[$data['form_id']])) {
            throw new \Exception('Form with given id not found!');
        }
        $form = new $forms[$data['form_id']];
        $applicationInfo = $this->getApplicationOwner($data['user_application_id']);
        $form->application_id = $applicationInfo['application_id'];
        $form->user_id = $applicationInfo['user_id'];
        $form->expert_id = $user_id;
        $form->setAttributes($data['form_info']);
        $form->setAttributes($data);
        if(!$form->save()) {
            throw new \Exception(json_encode($form->errors));
        }
        if($attachment) {
            $this->saveAttachment($attachment,$data,$form->id,$applicationInfo);
        }
        return [
          'success'=>true,
          'message'=>'form has been saved!'
        ];
    }

    private function saveAttachment($file, $data, $object_id,$applicationInfo)
    {
        $fileNames = $file['name'];
        $tempNames2 = $file['tmp_name'];
        $fileTypes = $file['type'];
        $fileIndentification = array_keys($fileNames)[0];
        $fileTitle = time().$fileNames[$fileIndentification];
        $fileName = 'form_uploads/' . $fileTitle;
        move_uploaded_file($tempNames2[$fileIndentification], $fileName);
        $mediaContent = new ExpertFormMedia();
        $mediaContent->setAttributes($data);
        $mediaContent->user_id = $applicationInfo['user_id'];
        $mediaContent->application_id = $applicationInfo['application_id'];
        $mediaContent->object_id = $object_id;
        $mediaContent->file_path = Yii::$app->request->hostInfo . '/' . $fileName;
        $mediaContent->file_name = $fileTitle;
        $mediaContent->file_extension = $fileTypes[$fileIndentification];
        if (!$mediaContent->save()) {
            throw new  \Exception(json_encode($mediaContent->errors));
        }
    }


}
