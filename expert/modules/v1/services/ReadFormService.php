<?php

namespace expert\modules\v1\services;

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

    public function getColumnByField($field = 210)
    {
        return [
            210 => 'form_200'
        ];
    }
}
