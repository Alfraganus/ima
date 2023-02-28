<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\WizardFormField $model */

$this->title = Yii::t('app', 'Create Wizard Form Field');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Wizard Form Fields'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wizard-form-field-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
