<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Composition */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Composition',
]) . ' ' . $model->dish;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Compositions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->dish, 'url' => ['view', 'dish' => $model->dish, 'ingredient' => $model->ingredient]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="composition-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
