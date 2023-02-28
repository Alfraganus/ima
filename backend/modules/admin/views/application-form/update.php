<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ApplicationForm $model */

$this->title = 'Update Application Form: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Application Forms', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="application-form-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
