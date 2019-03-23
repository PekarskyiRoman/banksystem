<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Deposit */
/* @var $form yii\widgets\ActiveForm */
/* @var $clientList array */
?>

<div class="deposit-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'client_id')->dropDownList(\yii\helpers\ArrayHelper::map($clientList, 'id', 'fullname'), ['prompt' => 'Select client']) ?>

    <?= $form->field($model, 'balance')->textInput() ?>

    <?= $form->field($model, 'interest_rate')->textInput() ?>

    <?= $form->field($model, 'creation_date')->textInput(['readonly' => true, 'value' => date('Y-m-d', time())]) ?>

    <?= $form->field($model, 'last_interest_date')->textInput(['type' => 'hidden', 'value' => date('Y-m-d', time())])->label(false) ?>

    <?= $form->field($model, 'next_interest_date')->textInput(['type' => 'hidden', 'value' => $model->getNextInterestDate(date('Y-m-d', time()))])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
