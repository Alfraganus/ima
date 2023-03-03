<?php
namespace frontend\modules\api\service;

use common\models\ApplicationForm;
use common\models\ApplicationFormMedia;
use common\models\AuthorApplication;
use common\models\Requester;
use common\models\UserApplications;
use common\models\WizardFormField;
use Yii;

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



    public function saveData($user_id, $postContent,$files = null)
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
            $formModel->setAttributes($form);
            $formModel->save();
        }
        if($files)  $this->saveFiles($files,$postContent,$user_id);
    }

    public function saveFiles($files,$postContent,$user_id)
    {
        $fileNames = $files['forms']['name'];
        $tempNames2 = $files['forms']['tmp_name'];
        $fileTypes = $files['forms']['type'];
        for ($i = 0; $i < sizeof($fileNames);$i++) {
            $fileIndentification = array_keys($fileNames[$i])[0];
            $fileName = 'form_uploads/'.$fileNames[$i][$fileIndentification];
            move_uploaded_file($tempNames2[$i][$fileIndentification],$fileName);
            $mediaContent = new ApplicationFormMedia();
            $mediaContent->application_id = $postContent['application_id'];
            $mediaContent->wizard_id = $postContent['wizard_id'];
            $mediaContent->form_id = $fileIndentification;
            $mediaContent->user_id =$user_id;
            $mediaContent->file_path = Yii::$app->request->hostInfo.'/'.$fileName;
            $mediaContent->file_name = $fileNames[$i][$fileIndentification];
            $mediaContent->file_extension = $fileTypes[$i][$fileIndentification];
            if(!$mediaContent->save()) {
                throw new  \Exception(json_encode($mediaContent->errors));
            }
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