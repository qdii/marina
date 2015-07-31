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
use \yii\widgets\ActiveForm;
use \skeeks\widget\chosen\Chosen;

$this->title = 'Recipe';
$this->params['breadcrumbs'][] = $this->title;

\app\assets\RecipeAsset::register($this);

// the id of the div that will contain the list, when a dish is selected
$bilanId = "bilan";

// the URL that permits loading the list
$loadUrl = Url::toRoute("site/many-column-list-dish");

$ingredientsById = ArrayHelper::index($ingredients, 'id');

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
                var table = $('tbody');
                load_bilan(table, params.selected, '$loadUrl');
            }"
        ]
    ]
);

echo Html::tag("div", "", [ "id" => $bilanId ]);

// RECIPE
$formOptions = [
        'id'     => 'new-ingredient-form',
        'method' => 'POST',
        'action' => Url::toRoute('site/insert-composition'),
    ];
$form = ActiveForm::begin($formOptions);

$tableOptions = ['id' => 'ingredient-table'];
Html::addCssClass($tableOptions, 'table');
Html::addCssClass($tableOptions, 'table-hover');
if ($dish === 0) {
    Html::addCssClass($tableOptions, 'hidden');
}

echo Html::beginTag("table", $tableOptions);
echo Html::beginTag("thead");

// HEADERS
$headers = [ 'Name', 'Weight', 'Proteins', 'Energy', '' ];
echo Html::beginTag("tr");
foreach ($headers as $value) {
    echo Html::tag("th", $value);
}
echo Html::endTag("tr");
echo Html::endTag("thead");

// INGREDIENTS
echo Html::beginTag("tbody");
$total_proteins = 0;
$total_energy   = 0;
$total_weight   = 0;

foreach ( $components as $component ) {
    $ingredient = $ingredientsById[ $component->ingredient ];
    $quantity   = $component->quantity;
    $proteins   = $quantity * $ingredient['protein']     / 100;
    $energy     = $quantity * $ingredient['energy_kcal'] / 100;
    $options    = ['data-id' => $component->ingredient];
    Html::addCssClass($options, 'ingredient');

    echo Html::beginTag("tr", $options);
    echo Html::tag("td", $ingredient['name']);
    echo Html::tag("td", round($quantity, 1) . " g");
    echo Html::tag("td", round($proteins, 1) . " g");
    echo Html::tag("td", round($energy, 1)   . " kcal");
    echo Html::endTag("tr");

    $total_weight   += $quantity;
    $total_proteins += $proteins;
    $total_energy   += $energy;
}

// NEW INGREDIENT FORM
$compositionModel = new \app\models\Composition;
$inline           = [ 'template' => '{input}{error}' ];

$plusIcon = '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>';

echo Html::beginTag('tr', ['id' => 'new-ingredient']);
echo Html::beginTag('td', ['data-id' => 0]);
echo $form->field($compositionModel, 'ingredient', $inline)->widget(
    Chosen::className(),
    [
        'items' => ArrayHelper::map($ingredients, 'id', 'name'),
        'placeholder' => 'Choose an ingredient',
    ]
);
echo Html::endTag('td');
echo Html::beginTag('td');
echo $form->field($compositionModel, 'quantity', $inline);
echo Html::endTag('td');
echo Html::beginTag('td');
echo Html::submitButton($plusIcon, [ 'class' => 'btn btn-success' ]);
echo Html::endTag('td');
echo Html::beginTag('td');
echo Html::activeHiddenInput($compositionModel, 'dish');
echo Html::endTag('td');
echo Html::endTag('tr');
//
// TOTAL
echo Html::beginTag('tr', ['class' => 'list-group-item-success', 'id' => 'total']);
echo Html::tag("td", Html::tag('strong', 'Total'));
echo Html::tag("td", Html::tag('strong', round($total_weight,   1) . " g"));
echo Html::tag("td", Html::tag('strong', round($total_proteins, 1) . " g"));
echo Html::tag("td", Html::tag('strong', round($total_energy,   1) . " kcal"));
echo Html::endTag('tr');


echo Html::endtag("tbody");
echo Html::endtag("table");

ActiveForm::end();

