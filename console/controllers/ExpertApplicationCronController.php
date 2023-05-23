<?php

namespace console\controllers;

use common\models\UserApplications;
use common\traits\PaymentFunctions;
use expert\models\ApplicationStatus;
use expert\modules\v1\services\ApplicationStatusService;
use Yii;

class ExpertApplicationCronController extends \yii\console\Controller
{
    use PaymentFunctions;

    private $userApplicaitons;
    private $applicaitonStatusService;
    private $applicationStatusManager;

    public function __construct($id, $module, $config = [])
    {
        $this->userApplicaitons = UserApplications::find();
        $this->applicaitonStatusService = ApplicationStatus::find();
        $this->applicationStatusManager = Yii::createObject(ApplicationStatusService::class);
        parent::__construct($id, $module, $config);
    }

    /*pendingda turgan applciationlarni kegingi statusga o'tkazish*/

    public function actionMoveStatusFormalExpertise()
    {
        $this->changeApplicationStatus('В ожидании формальной экспертизы','setApplicationStatusFormalExpertise',1);
    }

    public function actionMoveStatusExpertise()
    {
        $this->changeApplicationStatus('В ожидании экспертизы','setApplicationStatusInProgress',6);
    }

    private function changeApplicationStatus($currentStatus,$statusChangerAction,$valid_month)
    {
        $applications = $this->userApplicaitons
            ->where([
                'user_applications.status_id' => $this->applicationStatusManager
                    ->getApplicationStatus( "%$currentStatus%")
            ])
            ->joinWith('statusManagement')
            ->asArray()->all();

        foreach ($applications as $application) {
            if(!empty($application['statusManagement']['finish'])) {
                $dateTime = new \DateTime($application['statusManagement']['finish']);
                $now = new \DateTime();
                if($dateTime < $now) {
                    $this->applicationStatusManager->$statusChangerAction($application['id'],$valid_month);
                    print_r($application['statusManagement']);
                }
            }

        }
    }


    /*har kuni shu funksiya run bolishi kerak*/
    public function actionCheckPaymentsAndMoveStatus()
    {
        $applications = $this->userApplicaitons->where([
            'status_id' => $this->applicationStatusManager->getApplicationStatus('%На стадии уплаты пошлины за экспертизу')
        ])
            ->joinWith('decision')
            ->asArray()
            ->all();

        foreach ($applications as $application) {
            $expertDecisionExtraInfo = json_decode($application['decision']['extra_info'], true);
            if (!empty($expertDecisionExtraInfo)) {
                $paymentStatusChecker = $this->checkInvoiceStatus(
                    $expertDecisionExtraInfo['invoice_serial'], $application->id
                );
                if ($paymentStatusChecker['success']) {
                    $this->applicationStatusManager->setApplicationStatusExpertPending($application['id'],6);
                }
            }
        }
    }
}