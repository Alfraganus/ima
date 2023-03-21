<?php

namespace frontend\modules\api\service;

use common\models\ApplicationForm;
use Yii;
use common\models\forms\FormConfirmation;
use common\models\forms\FormMktu;
use common\models\forms\FormPayment;
use common\models\forms\FormPriority;
use common\models\forms\mktu\FormMktuChildren;
use common\models\ApplicationFormMedia;
use common\models\forms\FormAuthor;
use common\models\forms\FormIndustryDocument;
use common\models\forms\FormIndustryExample;
use common\models\forms\FormRequester;
use common\models\UserApplications;
use common\models\forms\FormProductSymbol;
use yii\helpers\ArrayHelper;

class FormSaveService
{
    private $setForm;

    public function __construct()
    {
        $this->setForm = ArrayHelper::map(ApplicationForm::find()->all(), 'id', 'form_class');
    }

    public function saveData($user_id, $postContent, $files = null)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user_application_id = $this->saveApplication($postContent['application_id']);
            if ($postContent['forms']) $this->saveForms($postContent['forms'], $user_application_id, $postContent['wizard_id']);
            if ($files) $this->saveFiles($files, $postContent, $user_id, $user_application_id);
            $transaction->commit();
            return [
                'success' => true,
                'message' => 'Operation is successfull'
            ];

        } catch (\Exception $exception) {
            $transaction->rollBack();
            return [
                'success' => false,
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile()
            ];
        }
    }

    private function saveApplication($application_id, $user_id = 1)
    {
        $application = UserApplications::findOne([
            'user_id' => $user_id,
            'application_id' => $application_id,
            'is_finished' => null,
        ]);
        if (!$application) {
            $application = new UserApplications();
            $application->application_id = $application_id;
            $application->user_id = $user_id;
            $application->save();
        }
        return $application->id;
    }

    private function checkIfFormDataExists($formId, $user_application_id, $user_id)
    {
        $form = $this->setForm[$formId]::findone([
            'user_application_id' => $user_application_id,
            'user_id' => $user_id,
        ]);
          if (!empty($form)) {
              return true;
          }
          return false;
    }

    private function saveForms($forms, $application_id, $wizard_id, $user_id = 1)
    {
        foreach ($forms as $form) {
            if (!in_array($form['form_id'], array_keys($this->setForm))) {
                throw new \Exception(sprintf("Form with form_id %d does not exist, please check it again", $form["form_id"]));
            }
            if(!$this->checkIfFormDataExists($form['form_id'], $application_id, $user_id)) {
                $formModel = new $this->setForm[$form['form_id']];
                $formModel->user_application_id = $application_id;
                $formModel->user_application_wizard_id = $wizard_id;
                $formModel->user_id = $user_id;
                $formModel->setAttributes($form);
                if (!$formModel->save()) {
                    throw new \Exception(json_encode($formModel->errors));
                }
                if ($form['child']) {
                    $this->saveChildForm($form['child'], $formModel->id, $form['class_content_type'], $form['form_id']);
                }
            }
        }
    }

    private function saveChildForm($providedData, $model_id, $contentTypeId, $form_id)
    {
        if ($this->setForm[$form_id] instanceof FormMktu) {
            $this->saveMktuFormChild($providedData, $model_id, $contentTypeId);
        }
    }

    private function saveMktuFormChild($providedData, $parentId, $contentTypeId)
    {
        foreach ($providedData as $dataValue) {
            $mktuChildModel = new FormMktuChildren();
            $mktuChildModel->form_mktu_id = $parentId;
            $mktuChildModel->content_type_id = $contentTypeId;
            $mktuChildModel->mktu_product_id =
                $contentTypeId == FormMktu::MKTU_CONTENT_TYPE_PRODUCT ? $dataValue['product_id'] : null;
            $mktuChildModel->mktu_header_id =
                $contentTypeId == FormMktu::MKTU_CONTENT_TYPE_HEADER ? $dataValue['header_id'] : null;
            if (!$mktuChildModel->save()) {
                throw new \Exception(json_encode($mktuChildModel->errors));
            }
        }
    }

    public function saveFiles($files, $postContent, $user_id, $application_id)
    {
        $fileNames = $files['forms']['name'];
        $tempNames2 = $files['forms']['tmp_name'];
        $fileTypes = $files['forms']['type'];
        for ($i = 0; $i < sizeof($fileNames); $i++) {
            $fileIndentification = array_keys($fileNames[$i])[0];
            $fileTitle = time() . $fileNames[$i][$fileIndentification];
            $fileName = 'form_uploads/' . $fileTitle;
            move_uploaded_file($tempNames2[$i][$fileIndentification], $fileName);
            $mediaContent = new ApplicationFormMedia();
            $mediaContent->application_id = $application_id;
            $mediaContent->wizard_id = $postContent['wizard_id'];
            $mediaContent->form_id = $fileIndentification;
            $mediaContent->user_id = $user_id;
            $mediaContent->file_path = Yii::$app->request->hostInfo . '/' . $fileName;
            $mediaContent->file_name = $fileTitle;
            $mediaContent->file_extension = $fileTypes[$i][$fileIndentification];
            if (!$mediaContent->save()) {
                throw new  \Exception(json_encode($mediaContent->errors));
            }
        }
    }
}