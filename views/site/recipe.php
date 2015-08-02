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

echo Html::beginTag('h2');
echo $boat->name . ' ';
echo Html::tag('small', 'Clipper 70');
echo Html::endTag('h2');

\app\assets\RecipeAsset::register($this);

// the id of the div that will contain the list, when a dish is selected
$bilanId = "bilan";

// icons to add/remove ingredients
$plusIcon  = '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>';
$minusIcon = '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>';

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
echo Html::tag("tr", "");
echo Html::endTag("tr");
echo Html::endTag("thead");

echo Html::beginTag("tbody");

// NEW INGREDIENT FORM
$compositionModel = new \app\models\Composition;
$inline           = [ 'template' => '{input}{error}' ];

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
echo Html::endTag('td');
echo Html::beginTag('td');
echo Html::endTag('td');
echo Html::activeHiddenInput($compositionModel, 'dish');
echo Html::endTag('td');
echo Html::beginTag('td');
echo Html::submitButton($plusIcon, [ 'class' => 'btn btn-success' ]);
echo Html::endTag('td');
echo Html::endTag('tr');

// TOTAL
echo Html::beginTag('tr', ['class' => 'list-group-item-success', 'id' => 'total']);
echo Html::tag("td", Html::tag('strong', 'Total'));
echo Html::tag("td", "");
echo Html::tag("td", "");
echo Html::tag("td", "");
echo Html::tag("td", "");
echo Html::endTag('tr');


echo Html::endtag("tbody");
echo Html::endtag("table");

ActiveForm::end();

// DELETE INGREDIENT FORM
/**
 * Generates the Yii2 ActiveField options
 *
 * @param string $fieldId The new id to set
 *
 * @return string The option field
 */
function fieldOptions($fieldId)
{
    return
        [
            'options' =>
            [
                'id' => $fieldId,
                'selectors' => '#' + $fieldId,
            ]
        ];
}
echo Html::beginTag("div", ['class' => 'hidden']);
$updateFormOptions = [
    'id'     => 'update-ingredient-form',
        'method' => 'POST',
        'action' => Url::toRoute('site/update-composition'),
    ];
$updateForm = ActiveForm::begin($updateFormOptions);
echo $form->field($compositionModel, 'dish', fieldOptions('update-dish'));
echo $form->field($compositionModel, 'ingredient', fieldOptions('update-ingr'));
echo $form->field($compositionModel, 'quantity', fieldOptions('update-quantity'));
ActiveForm::end($updateFormOptions);
echo Html::endTag("div");
