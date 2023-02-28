<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ApplicationForm $model */

$this->title = 'Create Application Form';
$this->params['breadcrumbs'][] = ['label' => 'Application Forms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-form-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
