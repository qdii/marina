<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Auth */

$this->title = Yii::t('app', 'Create Auth');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auths'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
