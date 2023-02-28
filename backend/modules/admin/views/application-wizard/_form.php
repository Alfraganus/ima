<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\ApplicationWizard $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="application-wizard-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'application_id')->dropDownList(\yii\helpers\ArrayHelper::map(
            \common\models\Application::find()->all(),'id','name'
    )) ?>

    <?= $form->field($model, 'wizard_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'wizard_order')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'wizard_icon')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
