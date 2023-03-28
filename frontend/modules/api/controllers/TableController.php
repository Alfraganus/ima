<?php

namespace frontend\modules\api\controllers;

use kartik\mpdf\Pdf;
use Mpdf\Mpdf;
use Yii;
use yii\rest\Controller;

/**
 * Default controller for the `api` module
 */
class TableController extends Controller
{
    public function actionTruncate($table_name)
    {
        return Yii::$app->db->truncateTable($table_name);
    }

    public function actionAnyTable($table_name, $query = [])
    {
        $table = (new \yii\db\Query())
            ->select('*')
            ->from($table_name);
        if ($query) {
            $table->andWhere($query);
        }
        return $table->all();
    }

    public function actionGetTables()
    {
        $connection = Yii::$app->db;//get connection
        $dbSchema = $connection->schema;
        return $dbSchema->getTableNames();
    }

    public function actionPdf()
    {

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::FORMAT_A4,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            // set margins
            'marginLeft' => 5,
            'marginRight' => 10,
            'marginTop' => 10,
            'marginBottom' => 20,
            // set font
            'defaultFont' => 'Open Sans',
            'cssFile' => 'css/pdf.css',
            // content
            'content' => $this->renderPartial('guvohnoma'),
            // set options for mPDF constructor
           /* 'options' => [
                'title' => 'Certificate of Completion',
                'keywords' => 'certificate, completion',
            ],*/
            // call mPDF methods on the Pdf object
          /*  'methods' => [
                'SetHeader' => ['Certificate of Completion'], // set header
                'SetFooter' => ['{PAGENO}'], // set footer
            ]*/
        ]);


// return the pdf output as a string
        return $pdf->render();
    }
}
