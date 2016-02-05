<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

$logoutIcon = '<span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>';
$loginIcon = '<span class="glyphicon glyphicon-log-in" ria-hidden="true"></span>';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'Marinade',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            echo Nav::widget([
                'encodeLabels' => false,
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Boat',     'url' => ['/site/boat']],
                    ['label' => 'Cruise',   'url' => ['/site/cruise']],
                    ['label' => 'Cookbook', 'url' => ['/site/cookbook']],
                    ['label' => 'Recipe',   'url' => ['/site/recipe']],
                    ['label' => 'Calendar', 'url' => ['/site/calendar']],
                    Yii::$app->user->isGuest ?
                        ['label' => $loginIcon, 'url' => ['/site/login']] :
                        [
                            'label' => Yii::$app->user->identity->username . ' ' . $logoutIcon,
                            'url' => ['/site/logout'],
                            'linkOptions' => ['data-method' => 'post']
                        ],
                ],
            ]);
            NavBar::end();
        ?>

        <div class="container">
            <?= $content ?>
        </div>
    </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
