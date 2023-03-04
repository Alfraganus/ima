<?php

namespace frontend\modules\api\service;

use common\models\Application;
use common\models\ApplicationForm;
use common\models\ApplicationFormMedia;
use common\models\ApplicationWizard;
use common\models\forms\FormAuthor;
use common\models\forms\FormIndustryDocument;
use common\models\forms\FormIndustryExample;
use common\models\forms\FormRequester;
use common\models\UserApplications;
use common\models\WizardFormField;
use Yii;

class ApiService
{

    private $setForm;

    public function __construct()
    {
        $this->setForm = [
            1 => new FormRequester(),
            2 => new FormAuthor(),
            3 => new FormIndustryExample(),
            4 => new FormIndustryDocument(),
        ];

    }

    public function saveData($user_id, $postContent, $files = null)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
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

            if ($postContent['forms']) {
                foreach ($postContent['forms'] as $form) {
                    if (!in_array($form['form_id'], array_keys($this->setForm))) {
                        throw new \Exception(sprintf("Form with form_id %d does not exist, please check it again", $form["form_id"]));
                    }
                    $formModel = new $this->setForm[$form['form_id']];
                    $formModel->user_application_id = $postContent['application_id'];
                    $formModel->user_application_wizard_id = $postContent['wizard_id'];
                    $formModel->user_id = 1;
                    $formModel->setAttributes($form);
                    if (!$formModel->save()) {
                        throw new \Exception(json_encode($formModel->errors));
                    }
                }
            }
            if ($files)  $this->saveFiles($files, $postContent, $user_id);
            $transaction->commit();

            return [
                'success' => true,
                'message' => 'Operation is successfull'
            ];
        } catch (\Exception $exception) {
            $transaction->rollBack();
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }

    }

    public function saveFiles($files, $postContent, $user_id)
    {
//        return $postContent;
        $fileNames = $files['forms']['name'];
        $tempNames2 = $files['forms']['tmp_name'];
        $fileTypes = $files['forms']['type'];
        for ($i = 0; $i < sizeof($fileNames); $i++) {
            /*validatsiya yozish kerak file nomi form id bolishini tekshirgani*/
            $fileIndentification = array_keys($fileNames[$i])[0];
            $fileName = 'form_uploads/' . $fileNames[$i][$fileIndentification];
            move_uploaded_file($tempNames2[$i][$fileIndentification], $fileName);
            $mediaContent = new ApplicationFormMedia();
            $mediaContent->application_id = $postContent['application_id'];
            $mediaContent->wizard_id = $postContent['wizard_id'];
            $mediaContent->form_id = $fileIndentification;
            $mediaContent->user_id = $user_id;
            $mediaContent->file_path = Yii::$app->request->hostInfo . '/' . $fileName;
            $mediaContent->file_name = $fileNames[$i][$fileIndentification];
            $mediaContent->file_extension = $fileTypes[$i][$fileIndentification];
            if (!$mediaContent->save()) {
                throw new  \Exception(json_encode($mediaContent->errors));
            }
        }
    }


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


    public function getApplicationContent($application_id,$user_id)
    {
        $applicationWizard = ApplicationWizard::findAll(['application_id'=>$application_id]);
        foreach ($applicationWizard as $wizard) {
            $result[] = [
              'wizard_id'=>$wizard->id,
              'wizard_name'=>$wizard->wizard_name,
              'wizard_forms'=>$this->getForms($wizard->application_id,$wizard->id,$user_id)
            ];
        }
        return $result;
    }

    private function getForms($application_id,$wizard_id,$user_id)
    {
        $getWizardForms = WizardFormField::findAll(['wizard_id'=>$wizard_id]);
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