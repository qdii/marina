<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Fraction */

$this->title = $model->ingredient;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Fractions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fraction-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'ingredient' => $model->ingredient, 'product' => $model->product], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'ingredient' => $model->ingredient, 'product' => $model->product], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ingredient',
            'product',
            'fraction',
        ],
    ]) ?>

</div>
