<?php

namespace expert\modules\v1\services;

class ReadFormService
{

    public function getFormData($formModel,$postContent)
    {
        return $formModel::findall([
            'user_application_id' => $postContent['user_application_id'],
            'module_id' => $postContent['module_id'],
            'tab_id' => $postContent['tab_id']
        ]);
    }

    public function getColumnByField($field = 210)
    {
        return [
            210 => 'form_200'
        ];
    }
}
