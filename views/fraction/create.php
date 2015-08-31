<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fraction */

$this->title = Yii::t('app', 'Create Fraction');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Fractions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fraction-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
