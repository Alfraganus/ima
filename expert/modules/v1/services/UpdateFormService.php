<?php

namespace expert\modules\v1\services;

use expert\models\ExpertFormMedia;
use Yii;
use yii\web\UploadedFile;

class UpdateFormService
{
    private $createFormService;

    public function __construct()
    {
        $this->createFormService = new CreateFormService();
    }

    public function updateExpertForm($formContent, $file = null)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $formList = CreateFormService::getAllForms();
            $formClass = $formList[$formContent['form_id']];
            $model = $formClass::findone($formContent['id']);
            if (!$model) {
                throw new \Exception('Form with given id not found or deleted earlier!');
            }
            $model->setAttributes($formContent['form_info']);
            if (!$model->save()) {
                throw new \Exception(json_encode($model->errors));
            }
            if ($file) {
                if (!empty($model->file)) {
                    $path = sprintf('form_uploads/%s', $model->file[0]['file_name']);
                    ExpertFormMedia::findOne(['file_name' => $model->file[0]['file_name']])->delete();
                    unlink($path);
                }
                $this->createFormService->saveAttachment(
                    $file['form_info'],
                    $formContent,
                    $model->id,
                    $this->createFormService->getApplicationOwner($formContent['user_application_id'])
                );
            }
            $transaction->commit();
            return [
                'message'=>'Data has been successfully updated!',
                'data' => (new ReadFormService())->getFormData(
                    $formList[$formContent['form_id']],
                    $formContent
                ),
            ];

        } catch (\Exception $exception) {
            $transaction->rollBack();
            Yii::$app->response->statusCode = 400;
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }

    }
}