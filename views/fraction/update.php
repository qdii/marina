<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fraction */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Fraction',
]) . ' ' . $model->ingredient;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Fractions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ingredient, 'url' => ['view', 'ingredient' => $model->ingredient, 'product' => $model->product]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="fraction-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
