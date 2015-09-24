<?php

use \yii\authclient\widgets\AuthChoice;
use \yii\helpers\Url;
use \yii\helpers\Html;
use \app\models\LoginForm;
use \app\models\SignupForm;
use \yii\bootstrap\ActiveForm;
use \yii\bootstrap\Button;
use \yii\captcha\Captcha;

\yii\bootstrap\BootstrapAsset::register($this);
\app\assets\LoginAsset::register($this);

$this->title = 'Login';

$loginMdl  = new LoginForm;
$loginForm = [
    'id'     => 'login-form',
    'action' => Url::toRoute(['site/enter']),
    'layout' => 'horizontal',
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label'   => 'col-md-2',
            'offset'  => 'col-md-offset-1',
            'wrapper' => 'col-md-10',
            'error'   => '',
            'hint'    => '',
        ],
    ]
];

$signupMdl  = new SignupForm;
$signupForm = [
    'id'     => 'signup-form',
    'action' => Url::toRoute(['site/register']),
    'layout' => 'horizontal',
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label'   => 'col-md-2',
            'offset'  => 'col-md-offset-1',
            'wrapper' => 'col-md-10',
            'error'   => '',
            'hint'    => '',
        ],
    ]
];

$captchaOpts = [
    'template' => '<div style="padding-bottom: 15px">{image}</div><div>{input}</div>'
    ];
?>

<div class="page-header">
    <h1><?php echo $this->title ?></h1>
</div>

<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <ul class="nav nav-pills" style="padding-bottom: 15px;">
            <li id="login-tab" class="active"><a href="#">Login</a></li>
            <li id="signup-tab"><a href="#">Signup</a></li/>
        </ul>
        <div class="well">
            <?php $form = ActiveForm::begin($loginForm);
            echo $form->field($loginMdl, 'username');
            echo $form->field($loginMdl, 'password');
            echo Button::widget(['label' => 'Login', 'options' => ['class' => 'btn-success']]);
            ActiveForm::end(); ?>

            <?php $form = ActiveForm::begin($signupForm);
            echo $form->field($signupMdl, 'username');
            echo $form->field($signupMdl, 'password');
            echo $form->field($signupMdl, 'email');
            echo $form->field($signupMdl, 'captcha')->widget(Captcha::className(), $captchaOpts);
            echo Button::widget(['label' => 'Sign up', 'options' => ['class' => 'btn-success']]);
            ActiveForm::end(); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="well" style="padding-bottom: 55px;">
            <div class="col-md-8">
                <?php $authChoices = AuthChoice::begin(['baseAuthUrl' => ['site/auth']]);
                foreach($authChoices->getClients() as $key => $client) {
                    if (true || $key == 'facebook') {
                        $authChoices->clientLink(
                            $client, '<i class="fa fa-facebook"></i> Login with facebook', [ 'class' => 'btn btn-block btn-social btn-facebook' ]
                        );
                    }
                }
                AuthChoice::end(); ?>
            </div>
        </div>
    </div>
</div>

