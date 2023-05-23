<?php

namespace frontend\modules\api\service;

use common\models\forms\FormAuthor;
use common\models\forms\FormDocument;
use common\models\forms\FormIndustryExample;
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
                $user_application_id = $this->saveApplication($postContent['application_id'], $user_id);
            } else {
                $user_application_id = $postContent['user_application_id'];
            }

            if (!empty($postContent['forms'])) $this->saveForms($postContent['forms'], $user_application_id, $postContent['wizard_id'], $user_id);


            if ($files) $this->saveFiles($files, $postContent, $user_id, $user_application_id);
            $transaction->commit();
            $result = [
                'success' => true,
                'message' => 'Operation is successful!',
                'user_application_id' => $user_application_id,
                'data' => (new FormReadService())->getWizardContent(
                    $user_application_id,
                    $postContent['wizard_id'],
                    Yii::$app->user->id
                ),
                'sender_user_id' => $user_id
            ];
            $submitted = false;
            if ($this->checkIfApplicationSubmitted(Yii::$app->user->id, $user_application_id)) {
                $submitted = true;
            }
            $result['is_submitted'] = $submitted;

            return $result;

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

    private function checkIfApplicationSubmitted($user_id, $user_application_id)
    {
        $app = UserApplications::findOne(['user_id' => $user_id, 'id' => $user_application_id]);
        $document = ApplicationFormMedia::find()->where([
            'user_id' => $user_id,
            'form_id' => ApplicationFormMedia::LEGAL_ENTITY_DOC_FORM_ID,
            'application_id' => $user_application_id
        ])->exists();
        if ($app->is_finished && $document) {
            return true;
        }
        return false;
    }

    private function saveApplication($application_id, $user_id)
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

    private function cleanRecords($forms, $application_id, $user_id)
    {
        foreach ($forms as $form) {
            if (!in_array($form, array_keys($this->setForm))) {
                throw new \Exception(sprintf("Form with form_id %d does not exist, please check it again", $form["form_id"]));
            }
            $query = ['user_application_id' => $application_id, 'user_id' => $user_id];
            if ($this->setForm[$form] instanceof FormDocument) {
                $query = ['user_application_id' => $application_id, 'form_id' => $form, 'user_id' => $user_id];
            }
            $this->setForm[$form]::deleteAll($query);

            $applicationMedia = ApplicationFormMedia::findAll([
                'application_id' => $application_id,
                'form_id' => $form,
                'user_id' => $user_id
            ]);
            foreach ($applicationMedia as $media) {
                $media->delete();
            }
        }
    }

    private function saveForms($forms, $application_id, $wizard_id, $user_id)
    {
        /*cleaning previous records before inserting new ones*/
        $this->cleanRecords(
            array_unique(array_column($forms, 'form_id')),
            $application_id,
            $user_id
        );
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

            if (!empty($form['attachments'])) {
                $this->setFormAttachmentMissingValues(
                    json_decode($form['attachments']),
                    $wizard_id,
                    $application_id,
                    $user_id
                );
            }

            if (!empty($form['child'])) {
                $this->saveChildForm($form['child'], $formModel->id, $form['class_content_type'], $form['form_id']);
            }
        }
    }

    private function setFormAttachmentMissingValues(array $ids, int $wizard_id, int $application_id, $user_id)
    {
        foreach ($ids as $id) {
            $formAttachments = ApplicationFormMedia::findOne($id);
            $formAttachments->wizard_id = $wizard_id;
            $formAttachments->application_id = $application_id;
            if (!$formAttachments->save()) {
                throw new \Exception(json_encode($formAttachments->errors));
            }
            $formModel = new $this->setForm[$formAttachments->form_id];
            if ($formModel instanceof FormIndustryExample) {
                $formIndustry = FormIndustryExample::findOne([
                    'user_application_id' => $application_id,
                    'user_id' => $user_id,
                ]);
                $formIndustry->file = $formAttachments->file_path;
                $formIndustry->is_main = true;
                $formIndustry->save(false);
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
        $result = [];
        $attachmentIdsForForm = [];
        for ($i = 0; $i < sizeof($fileNames); $i++) {
            $fileIndentification = array_keys($fileNames[$i])[0];
            $fileTitle = time() . $fileNames[$i][$fileIndentification];
            $fileName = 'frontend/web/form_uploads/' . $fileTitle;
            move_uploaded_file($tempNames2[$i][$fileIndentification], 'form_uploads/' . $fileTitle);
            $mediaContent = new ApplicationFormMedia();
            $mediaContent->application_id = $application_id ?? null;
            $mediaContent->wizard_id = !empty($postContent) ? $postContent['wizard_id'] : null;
            $mediaContent->form_id = $fileIndentification;
            $mediaContent->user_id = $user_id;
            $mediaContent->file_path = Yii::$app->request->hostInfo . '/' . $fileName;
            $mediaContent->file_name = $fileTitle;
            $mediaContent->file_extension = $fileTypes[$i][$fileIndentification];
            if (!$mediaContent->save()) {
                throw new  \Exception(json_encode($mediaContent->errors));
            }
            $attachmentIdsForForm[][$fileIndentification] = $mediaContent->id;
        }
        foreach ($attachmentIdsForForm as $item) {
            foreach ($item as $key => $value) {
                if (!array_key_exists($key, $result)) {
                    $result[$key] = [];
                }
                $result[$key][] = $value;
            }
        }
        return $result;
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