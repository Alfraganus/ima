<?php

namespace expert\modules\v1\services;

use common\models\UserApplications;
use expert\models\ApplicationStatus;
use Yii;

class ApplicationStatusService
{
    private $status;

    public function __construct()
    {
        $this->status = Yii::createObject(ApplicationStatus::class);
    }

    public function setApplicationStatusPending($user_application_id)
    {
        $status = $this->status::find()->where(['like', 'name', '%В ожидании формальной экспертизы%', false])->one();
        if (!empty($status->id)) $this->changeUserApplicationStatus($user_application_id, $status->id);
    }

    public function setApplicationStatusFirstPaymentStage($user_application_id)
    {
        $status = $this->status::find()->where(['like', 'name', '%На стадии уплаты пошлины за экспертизу%', false])->one();
        if (!empty($status->id)) $this->changeUserApplicationStatus($user_application_id, $status->id);
    }

    public function setApplicationStatusExpertPending($user_application_id)
    {
        $status = $this->status::find()->where(['like', 'name', '%В ожидании экспертизы%', false])->one();
        if (!empty($status->id)) $this->changeUserApplicationStatus($user_application_id, $status->id);
    }

    public function setApplicationStatusTabOneOverdue($user_application_id)
    {
        $status = $this->status::find()->where(['like', 'name', '%Формальная экспертиза просрочена%', false])->one();
        if (!empty($status->id)) $this->changeUserApplicationStatus($user_application_id, $status->id);
    }

    public function setApplicationStatusRestored($user_application_id)
    {
        $status = $this->status::find()->where(['like', 'name', '%Восстановленные%', false])->one();
        if (!empty($status->id)) $this->changeUserApplicationStatus($user_application_id, $status->id);
    }

    public function setApplicationStatusCanceled($user_application_id)
    {
        $status = $this->status::find()->where(['like', 'name', '%Отозванные%', false])->one();
        if (!empty($status->id)) $this->changeUserApplicationStatus($user_application_id, $status->id);
    }

    public function setApplicationStatusInProgress($user_application_id)
    {
        $status = $this->status::find()->where(['like', 'name', '%На экспертизе%', false])->one();
        if (!empty($status->id)) $this->changeUserApplicationStatus($user_application_id, $status->id);
    }

    public function setApplicationStatusFinished($user_application_id)
    {
        $status = $this->status::find()->where(['like', 'name', 'Экспертиза завершена%', false])->one();
        if (!empty($status->id)) $this->changeUserApplicationStatus($user_application_id, $status->id);
    }

    public function setApplicationStatusTabTwoOverdue($user_application_id)
    {
        $status = $this->status::find()->where(['like', 'name', '%Экспертиза просрочена%', false])->one();
        if (!empty($status->id)) $this->changeUserApplicationStatus($user_application_id, $status->id);
    }

    private function changeUserApplicationStatus($user_application_id, $status_id)
    {
        $userApplication = UserApplications::findOne($user_application_id);
        $userApplication->status_id = $status_id;
        $userApplication->save(false);
    }
}