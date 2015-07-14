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
use \yii\web\JsExpression;

$this->title = 'Intake';
$this->params['breadcrumbs'][] = $this->title;

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
            'change' => "function(event, params) { console.log(params); }"
        ]
    ]
);

