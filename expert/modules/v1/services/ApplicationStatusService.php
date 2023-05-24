<?php

namespace expert\modules\v1\services;

use common\models\UserApplications;
use DateTime;
use Exception;
use expert\models\ApplicationStatus;
use expert\models\ApplicationStatusManagement;
use expert\models\forms\ExpertFormDecision;
use expert\models\forms\ExpertFormEnquiry;
use expert\models\forms\ExpertFormFeedback;
use expert\models\forms\ExpertFormNotification;
use expert\models\forms\ExpertFormPayment;
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
                    $this->setApplicationStatusFirstPaymentStage($formModel->user_application_id, 3);
                } elseif ($formModel->tab_id == 2) {
                    $this->setApplicationStatusFinished($formModel->user_application_id);
                }
            case $formModel instanceof ExpertFormPayment :
                if ($formModel->payment_purpose_id == 1 && $formModel->tab_id == 2) {
                    $this->setApplicationStatusExpertPending($formModel->user_application_id, 6);
                }
            case $formModel instanceof ExpertFormEnquiry :
            case $formModel instanceof ExpertFormNotification :
                $this->expandWaitingPeriod($formModel->user_application_id, 3, true);
            case $formModel instanceof ExpertFormFeedback :
                if (
                    !empty($formModel->date_recovery) &&
                    $this->getApplicationStatusById($formModel->user_application_id) == 'Отозванные'
                ) {
                    $this->setApplicationStatusRestored($formModel->user_application_id);
                }
        }

    }

    public function getApplicationStatus($statusName)
    {
        $status = $this->status::find()->where(
            ['like', 'name', "%$statusName%", false]
        )->one();

        return $status->id;
    }

    public function getApplicationStatusById($user_application_id)
    {
        $userApplication = UserApplications::findOne($user_application_id);
        $status = $this->status::findOne($userApplication->status_id);
        return $status->name;
    }

    public function setApplicationStatusPending($user_application_id, $valid_month = null)
    {
        $status = $this->status::find()->where(['like', 'name', '%В ожидании формальной экспертизы%', false])->one();
        if (!empty($status->id)) $this->changeUserApplicationStatus($user_application_id, $status->id, $valid_month);
    }

    public function setApplicationStatusFormalExpertise($user_application_id, $valid_month = null)
    {
        $status = $this->status::find()->where(['like', 'name', '%На формальной экспертизе%', false])->one();
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
                ->modify("+$valid_month months")
                ->format('Y-m-d H:i:s');
        }


        $appStatusManagement->save();
    }

    public function expandWaitingPeriod($user_application_id, $month, $answer_required = null)
    {
        $userApplication = UserApplications::findOne($user_application_id);
        $appStatusManagement = ApplicationStatusManagement::findOne([
            'user_application_id' => $user_application_id,
            'status_id' => $userApplication->status_id
        ]);
        $appStatusManagement->finish = (new DateTime('now'))
            ->modify("+$month months")
            ->format('Y-m-d H:i:s');
        if ($answer_required) $appStatusManagement->is_answer_required = true;
        $appStatusManagement->save(false);
    }

    public function cancelRespondRequired($user_application_id, $cancel_finishing_date = false)
    {
        $userApplication = UserApplications::findOne($user_application_id);
        $appStatusManagement = ApplicationStatusManagement::findOne([
            'user_application_id' => $user_application_id,
            'status_id' => $userApplication->status_id
        ]);
        if ($cancel_finishing_date) {
            $appStatusManagement->finish = null;
        }
        $appStatusManagement->is_answer_required = false;
        $appStatusManagement->save(false);
    }

    public function checkIfWaitingForRespond($user_application_id)
    {
        $userApplication = UserApplications::findOne($user_application_id);
        $appStatusManagement = ApplicationStatusManagement::findOne([
            'user_application_id' => $user_application_id,
            'status_id' => $userApplication->status_id
        ]);
        if ($appStatusManagement->is_answer_required) {
            return true;
        }
        return false;
    }

}