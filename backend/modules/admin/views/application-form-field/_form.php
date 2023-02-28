<?php

use common\models\query\ApplicationFormFieldQuery;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\ApplicationFormField $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="application-form-field-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'form_id')->dropDownList(\yii\helpers\ArrayHelper::map(
        \common\models\ApplicationForm::find()->all(),'id','form_name'
    )) ?>
    <?= $form->field($model, 'field_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data_type')->dropDownList(ApplicationFormFieldQuery::getDataTypes()) ?>

    <?= $form->field($model, 'is_compulsory')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
