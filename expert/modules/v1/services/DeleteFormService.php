<?php

namespace expert\modules\v1\services;

use expert\models\ExpertFormMedia;

class DeleteFormService
{
    public function deleteExpertForm($formContent)
    {
        $formList = CreateFormService::getAllForms();
        $formClass = $formList[$formContent['form_id']];
        $model = $formClass::findone($formContent['id']);
        if(!$model) {
            throw new \Exception('With with given id not found or deleted earlier!');
        }
        if (!empty($model->file)) {
            $path = sprintf('form_uploads/%s', $model->file[0]['file_name']);
            ExpertFormMedia::findOne(['file_name' => $model->file[0]['file_name']])->delete();
            unlink($path);
        }
        $model->delete();
        return [
            'success' => true,
            'message' => 'Form has been deleted!'
        ];
    }
}