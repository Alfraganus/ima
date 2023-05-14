<?php

namespace expert\modules\v1\services;

use common\models\UserApplications;
use DateTime;
use Exception;
use expert\models\ApplicationStatus;
use expert\models\ApplicationStatusManagement;
use expert\models\forms\ExpertFormDecision;
use Yii;

class ApplicationStatusService
{
    private $status;

    public function __construct()
    {
        $this->status = Yii::createObject(ApplicationStatus::class);
    }

    public function manageApplicationStatus($formClass, $form_id)
    {
        $formModel = $formClass::findone($form_id);
        switch ($formModel) {
            case $formModel instanceof ExpertFormDecision :
                if ($formModel->tab_id == 1 && $formModel->decision_type == 1) {
                    $this->setApplicationStatusFirstPaymentStage($formModel->user_application_id);
                } elseif ($formModel->tab_id == 2) {
                    $this->setApplicationStatusFinished($formModel->user_application_id);
                }
        }

    }

    public function setApplicationStatusPending($user_application_id, $valid_month = null)
    {
        $status = $this->status::find()->where(['like', 'name', '%В ожидании формальной экспертизы%', false])->one();
        if (!empty($status->id)) $this->changeUserApplicationStatus($user_application_id, $status->id, $valid_month);
    }

    public function setApplicationStatusFirstPaymentStage($user_application_id, $valid_month = null)
    {
        $status = $this->status::find()->where(['like', 'name', '%На стадии уплаты пошлины за экспертизу%', false])->one();
        if (!empty($status->id)) $this->changeUserApplicationStatus($user_application_id, $status->id, $valid_month);
    }

    public function setApplicationStatusExpertPending($user_application_id, $valid_month = null)
    {
        $status = $this->status::find()->where(['like', 'name', '%В ожидании экспертизы%', false])->one();
        if (!empty($status->id)) $this->changeUserApplicationStatus($user_application_id, $status->id, $valid_month);
    }

    public function setApplicationStatusTabOneOverdue($user_application_id, $valid_month = null)
    {
        $status = $this->status::find()->where(['like', 'name', '%Формальная экспертиза просрочена%', false])->one();
        if (!empty($status->id)) $this->changeUserApplicationStatus($user_application_id, $status->id, $valid_month);
    }

    public function setApplicationStatusRestored($user_application_id, $valid_month = null)
    {
        $status = $this->status::find()->where(['like', 'name', '%Восстановленные%', false])->one();
        if (!empty($status->id)) $this->changeUserApplicationStatus($user_application_id, $status->id, $valid_month);
    }

    public function setApplicationStatusCanceled($user_application_id, $valid_month = null)
    {
        $status = $this->status::find()->where(['like', 'name', '%Отозванные%', false])->one();
        if (!empty($status->id)) $this->changeUserApplicationStatus($user_application_id, $status->id, $valid_month);
    }

    public function setApplicationStatusInProgress($user_application_id, $valid_month = null)
    {
        $status = $this->status::find()->where(['like', 'name', '%На экспертизе%', false])->one();
        if (!empty($status->id)) $this->changeUserApplicationStatus($user_application_id, $status->id, $valid_month);
    }

    public function setApplicationStatusFinished($user_application_id, $valid_month = null)
    {
        $status = $this->status::find()->where(['like', 'name', 'Экспертиза завершена%', false])->one();
        if (!empty($status->id)) $this->changeUserApplicationStatus($user_application_id, $status->id, $valid_month);
    }

    public function setApplicationStatusTabTwoOverdue($user_application_id, $valid_month = null)
    {
        $status = $this->status::find()->where(['like', 'name', '%Экспертиза просрочена%', false])->one();
        if (!empty($status->id)) $this->changeUserApplicationStatus($user_application_id, $status->id, $valid_month);
    }

    private function changeUserApplicationStatus($user_application_id, $status_id, $valid_month = null)
    {
        $userApplication = UserApplications::findOne($user_application_id);
        $userApplication->status_id = $status_id;
        $userApplication->save(false);

        $appStatusManagement = new ApplicationStatusManagement();
        $appStatusManagement->status_id = $status_id;
        $appStatusManagement->user_application_id = $user_application_id;
        if ($valid_month) {
            $appStatusManagement->finish = (new DateTime('now'))
                ->modify("+$valid_month month")
                ->format('Y-m-d H:i:s');
        }
        $appStatusManagement->save();


    }
}