<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\ApplicationForm $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="application-form-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'form_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'can_be_multiple')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
