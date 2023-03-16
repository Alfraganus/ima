<?php

namespace frontend\modules\api\service;

use common\models\forms\FormMktu;
use Yii;
use common\models\ApplicationForm;
use common\models\ApplicationFormMedia;
use common\models\ApplicationWizard;
use common\models\forms\FormAuthor;
use common\models\forms\FormIndustryDocument;
use common\models\forms\FormIndustryExample;
use common\models\forms\FormRequester;
use common\models\UserApplications;
use common\models\WizardFormField;
use common\models\forms\FormProductSymbol;

class FormReadService
{

    public static function getUserFormData($user_id, $application_id, $form_id, $wizard_id)
    {
        $form = ApplicationForm::findOne($form_id);
        return $form->form_class::findAll([
            'user_application_id' => $application_id,
            'user_id' => $user_id,
            'user_application_wizard_id' => $wizard_id
        ]);
    }

    public function getWizardContent($application_id,$wizard_id,$user_id)
    {
        $result = [];
        $wizardForms = WizardFormField::findAll(['wizard_id' => $wizard_id]);
        foreach ($wizardForms as $form) { // $form->form->form_name
            $result[] =[
                'form_name'=>$form->form->form_name,
                'form_id'=>$form->form->id,
                'form_content'=>$this->getUserFormData(
                    $user_id,
                    $application_id,
                    $form->form_id,
                    $form->wizard_id
                )
            ];
        }
        return $result;
    }

    public function getApplicationContent($application_id)
    {
        $getUserApplication = UserApplications::findOne($application_id);
        $applicationWizard = ApplicationWizard::findAll(['application_id'=>$getUserApplication->application_id]);
        foreach ($applicationWizard as $wizard) {
            $result[] = [
              'wizard_id'=>$wizard->id,
              'wizard_name'=>$wizard->wizard_name,
              'wizard_forms'=>$this->getForms($getUserApplication->id,$wizard->id,$getUserApplication->user_id)
            ];
        }
        return $result;
    }

    private function getForms($application_id,$wizard_id,$user_id)
    {
        $getWizardForms = WizardFormField::findAll(['wizard_id'=>$wizard_id]);
        $forms = [];
        foreach ($getWizardForms as $form) {
            $forms[] = $this->getUserFormData(
                $user_id,
                $application_id,
                $form->form_id,
                $form->wizard_id
            );
        }
        return $forms;
    }


}