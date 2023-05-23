<?php

namespace expert\modules\v1\services;

use common\models\forms\FormAuthor;
use common\models\forms\FormRequester;
use common\models\UserApplications;
use expert\models\ExpertFormMedia;
use expert\models\forms\ExpertForm10;
use kartik\mpdf\Pdf;
use Yii;
use yii\helpers\ArrayHelper;

class PdfService
{

    private function getMaxOrderSeq($application_type_id)
    {
        $model = ExpertForm10::find()->where([
            'application_id' => $application_type_id
        ]);

        if ($model && $model->one()->order_seq < 1) {
            $order = 1;
        } else {
            $order = $model->max('order_seq') + 1;
        }
        return $order;
    }

    private function dataForPdf($user_application_id)
    {
        $formRequester = FormRequester::find()->select('full_name')
            ->where(['user_application_id'=>$user_application_id])
            ->all();
        $formAuthror = FormAuthor::find()->select('full_name')
            ->where(['user_application_id'=>$user_application_id])
            ->all();

        return [
            'patent_holder'=>implode(' ',ArrayHelper::getColumn($formRequester,'full_name')),
            'author'=>implode(' ',ArrayHelper::getColumn($formAuthror,'full_name'))
        ];
    }

    public function generatePDF($user_application_id)
    {
        try {
            $userApp = UserApplications::findOne($user_application_id);
            $getMaxOrderNum = $this->getMaxOrderSeq($userApp->application_id) ;
            $licenceNum =  $this->formatOrderNumber(
                'SAP',
                $getMaxOrderNum
            );;
            $model = new ExpertForm10();
            $model->expert_id = null;
            $model->user_id = $userApp->user_id;
            $model->application_id = $userApp->application_id;
            $model->user_application_id = $user_application_id;
            $model->order_seq = $getMaxOrderNum;
            $model->column_11 = $licenceNum;
            $model->column_15 = date('Y-m-d');
            $model->column_18 = date('Y-m-d', strtotime('+10 year', strtotime(date('Y-m-d'))));
            $model->column_19 = 'UZ';
            if ($model->save()) {
                $mediaAsset = new ExpertFormMedia();
                $mediaAsset->user_application_id = $userApp->id;
                $mediaAsset->application_id = $userApp->application_id;
                $mediaAsset->module_id = 3;
                $mediaAsset->tab_id = 1;
                $mediaAsset->form_id = 8;
                $mediaAsset->user_id = $userApp->user_id;
                $mediaAsset->object_id = $model->id;
                $fileTitle = $this->savePDFtoLocalServer([
                    'date_submitted'=>date('d-m-Y',$userApp->date_submitted),
                    'licence_number'=>$licenceNum,
                    'patent_holder'=>$this->dataForPdf($userApp->id)['patent_holder'],
                    'author'=>$this->dataForPdf($userApp->id)['author'],
                ]);
                $mediaAsset->file_name = $fileTitle;
                $mediaAsset->file_extension = 'application/pdf';
                $mediaAsset->file_path = Yii::$app->request->hostInfo . '/expert/web/' . $fileTitle;
                $mediaAsset->save();
            }

        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

    }

    private function savePDFtoLocalServer($data)
    {
        $content = \Yii::$app->controller->renderPartial('_guvohnoma',$data);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'marginTop' => 0,
            'marginLeft' => 0,
            'marginRight' => 0,
            'marginBottom' => 0,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_FILE,
            'content' => $content,
            'cssFile' => 'pdf_assets/style.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'options' => ['title' => 'Krajee Report Title'],

        ]);
        $outputFileName =sprintf('saved_licences/%d.pdf',time());
        $pdf->filename = $outputFileName;
        $pdf->render();

        return $outputFileName;
    }

    private function formatOrderNumber($prefix, $number)
    {
        $numberLength = strlen((string)$number);
        switch ($numberLength) {
            case 1:
                $formattedNumber = $prefix . '00000' . $number;
                break;
            case 2:
                $formattedNumber = $prefix . '0000' . $number;
                break;
            case 3:
                $formattedNumber = $prefix . '000' . $number;
                break;
            case 4:
                $formattedNumber = $prefix . '00' . $number;
                break;
            case 5:
                $formattedNumber = $prefix . '0' . $number;
                break;
            default:
                $formattedNumber = $number;
        }

        return $formattedNumber;
    }
}
