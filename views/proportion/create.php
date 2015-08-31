<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Proportion */

$this->title = Yii::t('app', 'Create Proportion');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Proportions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="proportion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
