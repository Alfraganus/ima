<?php
namespace frontend\modules\api\service;

use common\models\ApplicationForm;
use common\models\AuthorApplication;
use common\models\Requester;
use common\models\UserApplications;
use common\models\WizardFormField;

class ApiService
{

    public $setForm;

    public function __construct()
    {
        $this->setForm = [
            1 => new Requester(),
            2 => new AuthorApplication(),
        ];

    }


    public function saveData($user_id, $postContent) : void
    {
        $application = UserApplications::findOne([
            'user_id' => $user_id,
            'application_id' => $postContent['application_id']
        ]);
        if (!$application) {
            $application = new UserApplications();
            $application->application_id = $postContent['application_id'];
            $application->user_id = $user_id;
            $application->save();
        }

        foreach ($postContent['forms'] as $form) {
            $formModel = new $this->setForm[$form['form_id']];
            $formModel->user_application_id = $postContent['application_id'];
            $formModel->user_application_wizard_id = $postContent['wizard_id'];
            $formModel->user_id = 1;
            if($form['image']) {
                $result = base64_decode($form['image']);
                $base64_data = str_replace('data:image/jpeg;base64,', '', $result);
                file_put_contents('test.png',$base64_data);
            } else {
                $formModel->setAttributes($form);
            }
            $formModel->save();
//            return $this->saveForms($postContent['application_id'],$postContent['wizard_id'],$form['form_id'],$postContent['forms']);

        }
    }

    public static function getUserFormData($user_id,$application_id,$form_id,$wizard_id)
    {
        $form = ApplicationForm::findOne($form_id);
        return $form->form_class::findAll([
            'user_application_id'=>$application_id,
            'user_id'=>$user_id,
            'user_application_wizard_id'=>$wizard_id
        ]);
    }

    public function getWizardContent($wizard_id)
    {
        $wizardForms = WizardFormField::findAll(['wizard_id'=>$wizard_id]);
        foreach ($wizardForms as $form) {
            $result[$form->form->form_name] =  $this->getUserFormData(
                1,
                1,
                $form->form_id,
                $form->wizard_id
            );
        }
        return $result;

    }


}