<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Proportion */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Proportion',
]) . ' ' . $model->ingredient;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Proportions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ingredient, 'url' => ['view', 'ingredient' => $model->ingredient, 'product' => $model->product]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="proportion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
