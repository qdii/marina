<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MealSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="meal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nbGuests') ?>

    <?= $form->field($model, 'firstCourse') ?>

    <?= $form->field($model, 'secondCourse') ?>

    <?= $form->field($model, 'dessert') ?>

    <?php // echo $form->field($model, 'drink') ?>

    <?php // echo $form->field($model, 'cook') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'type') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
