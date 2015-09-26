<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Meal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="meal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nbGuests')->textInput() ?>

    <?= $form->field($model, 'firstCourse')->textInput() ?>

    <?= $form->field($model, 'secondCourse')->textInput() ?>

    <?= $form->field($model, 'dessert')->textInput() ?>

    <?= $form->field($model, 'drink')->textInput() ?>

    <?= $form->field($model, 'cook')->textInput() ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'cruise')->textInput() ?>

    <?= $form->field($model, 'backgroundColor')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
