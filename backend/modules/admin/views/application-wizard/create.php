<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ApplicationWizard $model */

$this->title = Yii::t('app', 'Create Application Wizard');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Application Wizards'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-wizard-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
