<?php

namespace expert\modules\v1\services;

use common\models\ApplicationFormMedia;
use common\models\forms\FormIndustryExample;
use expert\models\ExpertFormMedia;
use frontend\modules\api\service\FormReadService;
use yii\helpers\ArrayHelper;

class ReadFormService
{

    public function getForm55($user_application_id)
    {
        $industryDocumentModel = FormIndustryExample::find()->where(['user_application_id' => $user_application_id]);

        $industryExampleModel = ArrayHelper::getColumn(
            $industryDocumentModel->asArray()->all(),
            'file'
        );
        $formMedia =  ArrayHelper::getColumn(
            ApplicationFormMedia::find()->where([
                'application_id' => $user_application_id,
                'form_id' => FormReadService::getFormIdByClass('common\models\forms\FormDocument'),
            ])->asArray()->all(),
            'file_path'
        );
        $result = [];
        foreach ($formMedia as $element) {
            if (in_array($element, $industryExampleModel)) {
                continue;
            }
            $result[] = [
                'title'=>null,
                'is_main'=>false,
                'file'=>$element,
            ];
        }

        return array_merge(
            $industryDocumentModel->select(['id','title','is_main' ,'file'])->asArray()->all(),
            $result
        );
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
