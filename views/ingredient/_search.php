<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\IngredientSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ingredient-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'price') ?>

    <?= $form->field($model, 'duration') ?>

    <?= $form->field($model, 'unit') ?>

    <?php // echo $form->field($model, 'sucrose') ?>

    <?php // echo $form->field($model, 'glucose') ?>

    <?php // echo $form->field($model, 'fructose') ?>

    <?php // echo $form->field($model, 'water') ?>

    <?php // echo $form->field($model, 'energy_kcal') ?>

    <?php // echo $form->field($model, 'energy_kj') ?>

    <?php // echo $form->field($model, 'protein') ?>

    <?php // echo $form->field($model, 'lipid') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
