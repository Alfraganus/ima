<?php

namespace expert\modules\v1\services;

use common\models\UserApplications;
use expert\models\forms\ExpertForm10;
use kartik\mpdf\Pdf;

class PdfService
{

    public function generatePDF($user_application_id)
    {
        $userApp = UserApplications::findOne($user_application_id);
        $model = new ExpertForm10();
        $model->expert_id = null;
        $model->user_id = $userApp->user_id;
        $model->application_id = $userApp->application_id;
        $model->user_application_id = $user_application_id;
        $model->column_11 = 'SAP000001';
        $model->column_15 = date('Y-m-d');
        $model->column_18 =date('Y-m-d', strtotime('+1 year', strtotime(date('Y-m-d'))) );
        $model->column_19 = 'UZ';
        if($model->save()) {
            $this->savePDFtoLocalServer();
        }
    }

    public function savePDFtoLocalServer()
    {
        $content = \Yii::$app->controller->renderPartial('default/_guvohnoma', [
            'name' => 'Alfraganus'
        ]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            'marginTop' => 0,
            'marginLeft' => 0,
            'marginRight' => 0,
            'marginBottom' => 0,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_FILE,
            'content' => $content,
            'cssFile' => 'index.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'options' => ['title' => 'Krajee Report Title'],

        ]);
        $outputFileName = 'test2.pdf';
        $pdf->filename = $outputFileName;
        $pdf->render();
    }
}
