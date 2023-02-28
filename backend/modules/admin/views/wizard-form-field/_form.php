<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\WizardFormField $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="wizard-form-field-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'wizard_id')->dropDownList(\yii\helpers\ArrayHelper::map(
            \common\models\ApplicationWizard::find()->all(),'id','wizard_name'
    )) ?>

    <?= $form->field($model, 'form_id')->dropDownList(\yii\helpers\ArrayHelper::map(
        \common\models\ApplicationForm::find()->all(),'id','form_name'
    )) ?>

    <?= $form->field($model, 'order_id')->dropDownList([
        1=>1,
        2=>2,
        3=>3,
        4=>4,
        5=>5,
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
