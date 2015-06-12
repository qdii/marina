<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Meal */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Meal',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Meals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="meal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
