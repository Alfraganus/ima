<?php

namespace expert\modules\v1\services;

use common\models\ApplicationFormMedia;
use common\models\UserApplications;
use expert\models\ExpertFormMedia;
use expert\models\forms\ExpertFormList;
use expert\models\forms\ExpertFormDecision;
use Yii;
use yii\helpers\ArrayHelper;

class CreateFormService
{
    public static function getAllForms()
    {
        return ArrayHelper::map(
            ExpertFormList::find()
                ->asArray()
                ->all(),
            'id',
            'form_class'
        );
    }

    public function getApplicationOwner($user_application_id)
    {
        $applicationModel = UserApplications::findOne($user_application_id);
        if (!$applicationModel) {
            throw new \Exception("Application with id $user_application_id not found");
        }
        return [
            'user_id' => $applicationModel->user_id,
            'application_id' => $applicationModel->application_id
        ];
    }

    public function createForm($data, $attachment, $user_id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $forms = self::getAllForms();
            $form = new $forms[$data['form_id']];
            if (empty($forms[$data['form_id']])) {
                throw new \Exception('Form with given id not found!');
            }
            $applicationInfo = $this->getApplicationOwner($data['user_application_id']);
            $form->application_id = $applicationInfo['application_id'];
            $form->user_id = $applicationInfo['user_id'];
            $form->expert_id = $user_id;
            $form->setAttributes($data);
            $form->setAttributes($data['form_info']);
            if (!$form->save()) {
                throw new \Exception(json_encode($form->errors));
            }
            if ($attachment) {
                $this->saveAttachment($attachment, $data, $form->id, $applicationInfo);
            }
            $transaction->commit();
            return [
                'success' => true,
                'message' => 'form has been saved!',
                'data'=> (new ReadFormService())->getFormData(
                    $forms[$data['form_id']],
                    $data
                ),
            ];
        } catch (\Exception $exception) {
            $transaction->rollBack();
            Yii::$app->response->statusCode = 400;
            return [
                'success' => false,
                'message' => json_decode($exception->getMessage())
            ];
        }

    }

    public function saveAttachment($file, $data, $object_id, $applicationInfo)
    {
        $fileName = $file['name'][$data['form_id']];
        $tempName = $file['tmp_name'][$data['form_id']];
        $fileType = $file['type'][$data['form_id']];
//        throw new \Exception(json_encode($fileTypes));
        $fileTitle = time() . $fileName;
        $fileName = 'expert/web/form_uploads/' . $fileTitle;
        move_uploaded_file($tempName,  'form_uploads/' . $fileTitle);
        $mediaContent = new ExpertFormMedia();
        $mediaContent->setAttributes($data);
        $mediaContent->user_id = $applicationInfo['user_id'];
        $mediaContent->application_id = $applicationInfo['application_id'];
        $mediaContent->object_id = $object_id;
        $mediaContent->file_path = Yii::$app->request->hostInfo . '/' . $fileName;
        $mediaContent->file_name = $fileTitle;
        $mediaContent->file_extension = $fileType;
        if (!$mediaContent->save()) {
            throw new  \Exception(json_encode($mediaContent->errors));
        }
    }
}