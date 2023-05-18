<?php

namespace backend\controllers;

use common\models\LoginForm;
use kartik\mpdf\Pdf;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    /*public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','pdf'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }*/

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionReport() {
        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('_guvohnoma',[
            'name'=>'Alfraganus'
        ]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            'marginTop'=>0,
            'marginLeft'=>0,
            'marginRight'=>0,
            'marginBottom'=>0,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_FILE,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => 'index.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => ['title' => 'Krajee Report Title'],
            // call mPDF methods on the fly
          /*  'methods' => [
                'SetHeader'=>['Krajee Report Header'],
                'SetFooter'=>['{PAGENO}'],
            ]*/
        ]);
        $outputFileName = 'test.pdf';
        $pdf->filename = $outputFileName;
       return $pdf->render();
        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    public function actionPdf()
    {
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
//            'format' => 'A4',
//            'orientation' => 'P',
            'margin_top' => 0,
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_bottom' => 0
        ]);

        $htmlContent = <<<HTML
<!DOCTYPE html>
<html lang="en">
 <div id="document">
      <div class="space"></div>
      <div class="table">
        <div class="row">
          <div class="cell" style="color:blue">Guvohnoma egasi:</div>
          <div class="cell">{response.name_company}</div>
        </div>
        <div class="row">
          <div class="cell">Talabnoma raqami:</div>
          <div class="cell">MGU 20220990</div>
        </div>
        <div class="row">
          <div class="cell">Talabnoma kelib tushgan sana:</div>
          <div class="cell">{response.created_at}</div>
        </div>
        <div class="row">
          <div class="cell">Ustuvorlik sanasi:</div>
          <div class="cell">{response.created_at}</div>
        </div>
        <div class="row">
          <div class="cell">Quyidagi tovarlar va xizmatlarga doir:</div>
          <div class="cell">30 sinf (ilovaga qaralsin)</div>
        </div>
      </div>

      <div class="summary">
        <p>
          Tovar belgisi va xizmat koʻrsatish belgisiga berilgan guvohnoma
          Oʻzbekiston Respublikasining barcha hududida 09.06.2022 yildan
          e’tiboran 10 yil mobaynida amal qiladi. Oʻzbekiston Respublikasi tovar
          belgilari davlat reestrida 13.01.2023 yilda roʻyxatdan oʻtkazilgan.
        </p>
        <div class="qr">
          <img width='150px' height="150px" src="https://upload.wikimedia.org/wikipedia/commons/4/41/QR_Code_Example.svg" />
        </div>
      </div>

      <div>
         <div class="m150"></div>
         <div class="m150"></div>

        <img width='150px' height="150px" src=https://upload.wikimedia.org/wikipedia/commons/4/41/QR_Code_Example.svg />
        <h2 class="black_title">
          No {response.os_id} RAQAMLI GUVOHNOMAGA ILOVA ПРИЛОЖЕНИЕ К
          СВИДЕТЕЛЬСТВУ No
          {response.os_id}
        </h2>
        <div class="application_image">
          <img
            style="border-radius: 50%;width: 100px;height: 100px"
            src=
              "https://images.unsplash.com/photo-1661956603025-8310b2e3036d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2670&q=80"
            alt=""
          />
        </div>
        <div
          class="black_title"
          style=" width: 80%; font-size: 15px"
        >
          QUYIDAGI TOVARLAR VA/YOKI XIZMATLAR UCHUN RO‘YXATDAN O‘TKAZILGAN TOVAR
          BELGISI: <br /> ТОВАРЫ И/ИЛИ УСЛУГИ ДЛЯ ОБОЗНАЧЕНИЯ КОТОРЫХ
          ЗАРЕГИСТРИРОВАН ТОВАРНЫЙ ЗНАК:
        </div>
         <div class="m150"></div>

        <h3 class="footer">
          <span>
            30 - sinf -<br /> 30 - класс -{" "}
          </span>
          Печенье.
        </h3>
    
      </div>
    </div>
</html>
HTML;

        $cssContent = <<<CSS
#document {
    background-image: url('background.png');
    width: 500px;
      background-size: 100%;
  padding: 35px 20px;
  background-repeat: no-repeat;
  background-position-y: top;
  border-radius: 4px;
   width: 595px;
  position: absolute;
  z-index: 1111;
}

.m150{
  margin-top: 150px;
}
.table {
  margin-top: 15px;
  font-size: 16px;
}
.row {
  width: 50%;
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}
.cell {
  width: 50%;
     
}
.space{
  padding-top: 15rem;
  display: block;
}
.cell:nth-child(2) {
      font-weight: bold;}



      .summary {
  margin-top: 7rem;
  }

  .summary p{
      width: 60%;
      margin-left: auto;
  }
  .summary .qr {
      width: 20%;
      margin-left: auto;
      margin-right: 35px;
}
.application_image{
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 20px 0;
 
}
.application_image  img{
      width: 50%;
  }
  .black_title{
  text-align: center;
  font-size: 18px;
  width: 70%;
  font-weight: bold;
  margin: auto;
}

.m150{
  margin-top: 150px;
}

.footer{
  margin-top: 20px;
  
}

.footer span{
      font-weight: bold;
  }
/* Your CSS content here */
CSS;
        $mpdf->WriteHTML($htmlContent, \Mpdf\HTMLParserMode::HTML_BODY);
        $mpdf->WriteHTML($cssContent, \Mpdf\HTMLParserMode::HEADER_CSS);
// generate the PDF file
        $mpdf->Output();

    }
    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
