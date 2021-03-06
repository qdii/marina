<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Composition */

$this->title = Yii::t('app', 'Create Composition');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Compositions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="composition-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
