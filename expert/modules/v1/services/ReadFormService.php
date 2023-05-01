<?php

namespace expert\modules\v1\services;

use common\models\ApplicationFormMedia;
use expert\models\ExpertFormMedia;

class ReadFormService
{

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

    public static function getAttachments($user_id, $user_application_id,$module_id, $form_id)
    {
        return ExpertFormMedia::find()->where([
            'user_application_id'    => $user_application_id,
            'user_id'           => $user_id,
            'module_id'           => $module_id,
            'form_id'           => $form_id,
        ])->select(['id', 'file_name', 'file_path','file_extension'])->all();
    }
    public function getColumnByField($field = 210)
    {
        return [
            210 => 'form_200'
        ];
    }
}
