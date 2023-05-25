<?php

namespace expert\modules\v1\services;

use common\models\ApplicationForm;
use common\models\ApplicationWizard;
use common\models\forms\FormAuthor;
use common\models\forms\FormDocument;
use common\models\forms\FormIndustryExample;
use common\models\forms\FormPriority;
use common\models\forms\FormRequester;
use common\models\UserApplications;
use common\models\WizardFormField;
use Yii;
use yii\base\Model;


class FrontApplicationService extends Model
{

    public function getCustomFromClass($form_id, $user_application_id, array $columns, $isSingle = true, $data_id = null)
    {
        $form = ApplicationForm::findOne($form_id);
        $formClass = $form->form_class;
        $model = null;
        $queryBuilder = (new \yii\db\Query())->select($columns);
        if ( Yii::createObject($formClass) instanceof FormRequester) {
            $model =$queryBuilder->from('form_requester');
        } elseif (Yii::createObject($formClass) instanceof FormAuthor) {
            $model =$queryBuilder->from('form_author');
        } elseif (Yii::createObject($formClass) instanceof FormDocument) {
            $model =$queryBuilder->from('form_document');
        } elseif (Yii::createObject($formClass) instanceof FormPriority) {
            $model =$queryBuilder->from('form_priority');
        } elseif (Yii::createObject($formClass) instanceof FormIndustryExample) {
            $model =$queryBuilder->from('form_industry_example');
        }
        $model = $model->where(['user_application_id' => $user_application_id]);
        if ($data_id) {
            $model = $model->andWhere(['id' => $data_id]);
        }
        $model = $model->select($columns);
        if ($isSingle) {
            return $model->one();
        }
        return $model->all();

    }


    public function getUserApplication($user_application_id)
    {
        return UserApplications::findOne($user_application_id);
    }

    public function getUserApplicationWizardForm($application_id, $form_id)
    {
        return WizardFormField::findOne([
            'application_id' => $application_id,
            'form_id' => $form_id
        ])->form_id;
    }


    public function getForm($user_application_id, $form_id)
    {
        return $this->getFromClass($form_id)::find()
            ->where([
                'user_application_id' => $user_application_id
            ]);
    }

    public function deleteFrontForm($form_type_id, $data_id)
    {
        $formModel = $this->getFromClass($form_type_id)::findone($data_id);
        $formModel->delete();
        return [
            'success' => true,
            'message' => 'Form has been deleted!'
        ];
    }

    public function getFrontForm($user_application_id, $form_id)
    {
        $form = $this->getForm($user_application_id, $form_id);

        return $form->all();
    }

    public function getSingleFrontForm($user_application_id, $form_id, $data_id)
    {
        $formModel = $this->getForm($user_application_id, $form_id)->andWhere(['id' => $data_id])->one();

        if (!$formModel) return ['message' => 'Data not found!'];

        return $formModel;
    }

    public function getFromClass($form_id): string
    {
        $form = ApplicationForm::findOne($form_id);
        if ($form) return $form->form_class;
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

    public function updateFormAll($form_id, $postData)
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

    public function updateFormSingle($postData)
    {
        try {
            $formModel = $this->getFromClass($postData['form_id'])::findone(['id' => $postData['id']]);
            $formModel->setAttributes($postData);
            $formModel->save(false);
            return [
                'success' => true,
                'data' => $formModel
            ];
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

    }

}
