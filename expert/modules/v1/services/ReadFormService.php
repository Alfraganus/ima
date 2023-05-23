<?php

namespace expert\modules\v1\services;

use common\models\ApplicationFormMedia;
use common\models\forms\FormIndustryExample;
use expert\models\ExpertFormMedia;
use frontend\modules\api\service\FormReadService;

class ReadFormService
{

    public function getForm55($user_application_id)
    {
        $industryExampleModel = FormIndustryExample::findOne(['user_application_id' => $user_application_id]);
        $getMoreExamples = ApplicationFormMedia::find()->select(['file_path'])->where([
            'application_id' => $user_application_id,
            'form_id' => FormReadService::getFormIdByClass('common\models\forms\FormDocument'),
        ]);
        $model = $getMoreExamples->one();
        $index = 1;
        $result[] = [
            'id'=>$industryExampleModel->id,
            'index'=>$index,
            'title' => $industryExampleModel->title,
            'image' =>$model ? $model->file_path : null,
            'is_main' => $industryExampleModel->is_main,
            'language' => $industryExampleModel->language,
        ];
        foreach ($getMoreExamples->all() as $example) {
            $index++;
            $result[] = [
                'index'=>$index,
                'title' => null,
                'image' => $example->file_path,
                'is_main' => null,
                'language' => null,
            ];
        }
        return $result;
    }

    public function getFormData($formModel, $postContent)
    {
        $queryFilter = [
            'user_application_id' => $postContent['user_application_id'],
            'module_id' => $postContent['module_id'],
            'tab_id' => $postContent['tab_id']
        ];
        if (!$postContent['tab_id']) unset($queryFilter['tab_id']);

        return $formModel::findall($queryFilter);
    }

    public static function getAttachments($user_id, $user_application_id, $module_id, $form_id)
    {
        return ExpertFormMedia::find()->where([
            'user_application_id' => $user_application_id,
            'user_id' => $user_id,
            'module_id' => $module_id,
            'form_id' => $form_id,
        ])->select(['id', 'file_name', 'file_path', 'file_extension'])->all();
    }

    public function getColumnByField($field = 210)
    {
        return [
            210 => 'form_200'
        ];
    }
}
