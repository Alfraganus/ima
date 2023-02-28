<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ApplicationFormField $model */

$this->title = 'Update Application Form Field: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Application Form Fields', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="application-form-field-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
