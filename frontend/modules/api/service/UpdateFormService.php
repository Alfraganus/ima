<?php

namespace frontend\modules\api\service;

use common\models\ApplicationForm;
use common\models\ApplicationFormMedia;
use common\models\forms\FormAuthor;
use common\models\forms\FormRequester;
use common\models\UserApplications;
use common\models\WizardFormField;
use Yii;

class UpdateFormService
{

    public $setForm;

    public function __construct()
    {
        $this->setForm = [
            1 => new FormRequester(),
            2 => new FormAuthor(),
        ];

    }

    private function getFormClass($form_id)
    {
        $form = ApplicationForm::findOne($form_id);
        return $form->form_class;
    }

    public function updateWizard($user_id,$postContent, $files = null)
    {
        foreach ($postContent['forms'] as $form) {
             $formClass = $this->getFormClass($form['form_id']);
             $model = $formClass::findOne($form['id']);
             unset($form['form_id']);
             $model->updateAttributes($form);
        }
        $this->updateFiles($files,$postContent,$user_id);
        return [
            'success' => true,
            'message' => 'Operation is successfull'
        ];
    }

    public function updateFiles($files, $postContent, $user_id)
    {
        $fileNames = $files['forms']['name'];
        $tempNames2 = $files['forms']['tmp_name'];
        $fileTypes = $files['forms']['type'];
        for ($i = 0; $i < sizeof($fileNames); $i++) {
            /*validatsiya yozish kerak file nomi form id bolishini tekshirgani*/
            $fileIndentification = array_keys($fileNames[$i])[0];
            $fileName = 'form_uploads/' . $fileNames[$i][$fileIndentification];
            move_uploaded_file($tempNames2[$i][$fileIndentification], $fileName);
             ApplicationFormMedia::deleteAll([
                'application_id' => $postContent['application_id'],
                'wizard_id' => $postContent['wizard_id'],
                'form_id' =>$fileIndentification,
                'user_id' => $user_id,
            ]);
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
            if ($files) $this->saveFiles($files, $postContent, $user_id);
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




}