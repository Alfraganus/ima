<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ApplicationFormField $model */

$this->title = 'Create Application Form Field';
$this->params['breadcrumbs'][] = ['label' => 'Application Form Fields', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-form-field-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
