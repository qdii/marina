<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\components\Panel;

app\assets\AdminAsset::register($this);
app\assets\AdminUserAsset::register($this);

/* @var $this yii\web\View */
$this->title = 'Admin';
$this->params['breadcrumbs'][] = $this->title;

$tabs = [ 
    [ 'label' => "User",        'url' => 'javascript:void(0);', 'options' => [ 'onclick' => 'onclick_tab(this, "user"      )',  'data-url' => Url::to([ '/site/admin-user'       ]) ] ],
    [ 'label' => "Ingredient",  'url' => 'javascript:void(0);', 'options' => [ 'onclick' => 'onclick_tab(this, "ingredient")',  'data-url' => Url::to([ '/site/admin-ingredient' ]) ] ],
    [ 'label' => "Dish",        'url' => 'javascript:void(0);', 'options' => [ 'onclick' => 'onclick_tab(this, "dish"      )',  'data-url' => Url::to([ '/site/admin-dish'       ]) ] ],
    [ 'label' => "Boat",        'url' => 'javascript:void(0);', 'options' => [ 'onclick' => 'onclick_tab(this, "boat"      )',  'data-url' => Url::to([ '/site/admin-boat'       ]) ] ]
];

echo Nav::widget([
    'items' => $tabs,
    'options' => [ 'class' => 'nav-tabs' ]
]);
?>

<div id="admin_tab"></div>

