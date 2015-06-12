<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Cruise */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Cruise',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cruises'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cruise-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
