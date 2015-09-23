<?php

use \yii\authclient\widgets\AuthChoice;
use \yii\helpers\Url;
use \yii\helpers\Html;
use \app\models\LoginForm;
use \yii\bootstrap\ActiveForm;
use \yii\bootstrap\Button;

$this->title = 'Login';
$loginMdl    = new LoginForm;
$loginForm        = [
    'action' => Url::toRoute(['site/enter']),
    'layout' => 'horizontal',
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-md-2',
            'offset' => 'col-md-offset-1',
            'wrapper' => 'col-md-10',
            'error' => '',
            'hint' => '',
        ],
    ]
];
?>

<div class="page-header">
    <h1><?php echo $this->title ?></h1>
</div>

<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6 well">
        <?php $form = ActiveForm::begin($loginForm);
        echo $form->field($loginMdl, 'username');
        echo $form->field($loginMdl, 'password');
        echo Button::widget(['label' => 'Login', 'options' => ['class' => 'btn-success']]);
        ActiveForm::end(); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6 well">
        <?php echo AuthChoice::widget(['baseAuthUrl' => ['site/auth']]); ?>
    </div>
</div>

