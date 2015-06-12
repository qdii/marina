<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Boat */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Boat',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Boats'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="boat-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
