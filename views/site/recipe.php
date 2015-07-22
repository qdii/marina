<?php
/**
 * This page lets the user look up how many calories/protein/etc. a dish has
 *
 * PHP version 5.4
 *
 * @category Views
 * @package  Views
 * @author   Victor Lavaud (qdii) <victor.lavaud@gmail.com>
 * @license  GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link     http://marina.dodges.it
 *
 */
use \yii\helpers\ArrayHelper;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\web\JsExpression;
use \skeeks\widget\chosen\ChosenAsset;

ChosenAsset::register($this);

$this->title = 'Recipe';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('js/bilan.js');

// the id of the div that will contain the list, when a dish is selected
$bilanId = "bilan";

// the URL that permits loading the list
$loadUrl = Url::toRoute("site/many-column-list-dish");

$meals = \app\models\Dish::find()->all();
$model = new \app\models\Dish;
echo \skeeks\widget\chosen\Chosen::widget(
    [
        'model'       => $model,
        'attribute'   => 'name',
        'placeholder' => 'Choose a dish',
        'items'       => ArrayHelper::map($meals, 'id', 'name'),
        'clientEvents' =>
        [
            'change' => "function(ev, params) {
                load_bilan('$bilanId', params.selected, '$loadUrl');
            }"
        ]
    ]
);

echo Html::tag("div", "", [ "id" => $bilanId ]);
