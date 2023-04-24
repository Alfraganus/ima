<?php

namespace frontend\modules\api\service;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\ApplicationForm;
use common\models\forms\FormMktu;
use common\models\forms\mktu\FormMktuChildren;
use common\models\ApplicationFormMedia;
use common\models\UserApplications;

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
            if (empty($postContent['user_application_id'])) {
                $user_application_id = $this->saveApplication($postContent['application_id']);
            } else {
                $user_application_id = $postContent['user_application_id'];
            }
            if ($postContent['forms']) $this->saveForms($postContent['forms'], $user_application_id, $postContent['wizard_id']);

            if(!empty($postContent['attachments'])) {
                $this->setFormAttachmentMissingValues(
                    json_decode(json_decode($postContent['attachments'])),
                    $postContent['wizard_id'],
                    $user_application_id
                );
            }

            if ($files) $this->saveFiles($files, $postContent, $user_id, $user_application_id);
            $transaction->commit();
            return [
                'success' => true,
                'message' => 'Operation is successful!',
                'data' => (new FormReadService())->getWizardContent(
                    $user_application_id,
                    $postContent['wizard_id'],
                    Yii::$app->user->id
                )
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
    private function setFormAttachmentMissingValues(array $ids,int $wizard_id, int $application_id)
    {
        foreach ($ids as $id) {
            $formAttachments = ApplicationFormMedia::findOne($id);
            $formAttachments->wizard_id = $wizard_id;
            $formAttachments->application_id = $application_id;
            if(!$formAttachments->save()) {
                throw new \Exception(json_encode($formAttachments->errors));
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
        $attachmentIds = [];
        for ($i = 0; $i < sizeof($fileNames); $i++) {
            $fileIndentification = array_keys($fileNames[$i])[0];
            $fileTitle = time() . $fileNames[$i][$fileIndentification];
            $fileName = 'form_uploads/' . $fileTitle;
            move_uploaded_file($tempNames2[$i][$fileIndentification], $fileName);
            $mediaContent = new ApplicationFormMedia();
            $mediaContent->application_id = $application_id??null;
            $mediaContent->wizard_id = !empty($postContent) ? $postContent['wizard_id'] : null;
            $mediaContent->form_id = $fileIndentification;
            $mediaContent->user_id = $user_id;
            $mediaContent->file_path = Yii::$app->request->hostInfo . '/' . $fileName;
            $mediaContent->file_name = $fileTitle;
            $mediaContent->file_extension = $fileTypes[$i][$fileIndentification];
            if (!$mediaContent->save()) {
                throw new  \Exception(json_encode($mediaContent->errors));
            }
            $attachmentIds[] = $mediaContent->id;
        }
        return $attachmentIds;
    }

    private function checkIfDataExistsForMedia($user_id, $user_application_id, $form_id)
    {
        $form = ApplicationFormMedia::find()->where([
            'application_id' => $user_application_id,
            'user_id' => $user_id,
            'form_id' => $form_id,
        ]);
        if ($form->exists()) {
            return true;
        }
        return false;
    }
}