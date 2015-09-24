<?php

use \yii\authclient\widgets\AuthChoice;
use \yii\helpers\Url;
use \yii\helpers\Html;
use \app\models\LoginForm;
use \yii\bootstrap\ActiveForm;
use \yii\bootstrap\Button;
use \yii\captcha\Captcha;

\yii\bootstrap\BootstrapAsset::register($this);
\app\assets\LoginAsset::register($this);

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

$captchaOpts = [
    'template' => '<div style="padding-bottom: 15px">{image}</div><div>{input}</div>'
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
        echo $form->field($loginMdl, 'captcha')->widget(Captcha::className(), $captchaOpts);
        echo Button::widget(['label' => 'Login', 'options' => ['class' => 'btn-success']]);
        ActiveForm::end(); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6 well">
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

