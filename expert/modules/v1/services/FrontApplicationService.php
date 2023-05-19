<?php

namespace expert\modules\v1\services;

use common\models\ApplicationForm;
use common\models\ApplicationWizard;
use common\models\UserApplications;
use common\models\WizardFormField;
use Yii;
use yii\base\Model;


class FrontApplicationService extends Model
{

    public function getFromClass($form_id): string
    {
        $form = ApplicationForm::findOne($form_id);
        if ($form) return $form->form_class;
    }

    public function getUserApplication($user_application_id)
    {
        return UserApplications::findOne($user_application_id);
    }

    public function getUserApplicationWizardForm($application_id,$form_id)
    {
        return WizardFormField::findOne([
            'application_id'=>$application_id,
            'form_id'=>$form_id
        ])->form_id;
    }


    public function getForm($user_application_id, $form_id)
    {
        return $this->getFromClass($form_id)::find()
            ->where([
                'user_application_id' => $user_application_id
            ]);
    }

    public function getFrontForm($user_application_id, $form_id)
    {
        $form = $this->getForm($user_application_id, $form_id);
        if ($form->count() > 1) {
            return $form->all();
        }
        return $form->one();
    }

    public function createForm($data)
    {
        try {
                $userApplication = $this->getUserApplication($data['user_application_id']);
                $class = $this->getFromClass($data['form_id']);
                $formModel = new $class;
                $formModel->user_id = $userApplication->user_id;
                $formModel->user_application_wizard_id = $this->getUserApplicationWizardForm(
                    $userApplication->application_id,
                    $data['form_id']
                );
                $formModel->setAttributes($data);
                $formModel->save();
            return [
                'success' => true,
                'data' => $formModel
            ];
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    public function updateForm($user_application_id, $form_id, $postData)
    {
        $formModel = [];
        try {
            foreach ($postData['forminfo'] as $data) {
                $formModel = $this->getFromClass($form_id)::findone(['id' => $data['id']]);
                unset($data['id']);
                $formModel->setAttributes($data);
                $formModel->save(false);
            }
            return [
                'success' => true,
                'data' => $formModel
            ];
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

    }

}
