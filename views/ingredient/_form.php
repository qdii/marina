<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Ingredient */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ingredient-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'duration')->textInput() ?>

    <?= $form->field($model, 'unit')->textInput() ?>

    <?= $form->field($model, 'sucrose')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'glucose')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fructose')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'water')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'energy_kcal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'energy_kj')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'protein')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lipid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ash')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'carbohydrates')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sugars')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fiber')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'weight')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
