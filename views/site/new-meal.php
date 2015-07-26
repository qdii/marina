<?php
use \app\components\ManyColumnList;
use \app\models\Ingredient;
use \yii\helpers\ArrayHelper;
use \yii\helpers\Html;
use \yii\widgets\ActiveForm;
use \skeeks\widget\chosen\Chosen;

$ingredientsById = ArrayHelper::index($ingredients, 'id');

// CHOSEN WIDGET
$ingredientChoser = Chosen::widget(
    [
        'model'       => new \app\models\Ingredient,
        'attribute'   => 'name',
        'placeholder' => 'Choose an ingredient',
        'items'       => ArrayHelper::map($ingredients, 'id', 'name'),
    ]
);

// RECIPE
echo Html::beginTag("table", ['class' => 'table table-hover']);
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

    echo Html::beginTag("tr", ['data-id' => $component->ingredient]);
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
$formOptions = [
        'id' => 'new-ingredient-form',
        'method' => 'POST',
        'action' => 'site/insert-composition',
    ];

echo Html::beginTag('tr');
$form = ActiveForm::begin($formOptions);

    echo Html::beginTag('td', ['data-id' => 'new-ingredient']);
    echo $form->field($compositionModel, 'ingredient', $inline)->widget(
        Chosen::className(),
        [ 'items' => ArrayHelper::map($ingredients, 'id', 'name') ]
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

ActiveForm::end();
echo Html::endTag('tr');

// TOTAL
echo Html::beginTag('tr', ['class' => 'list-group-item-success']);
echo Html::tag("td", Html::tag('strong', 'Total'));
echo Html::tag("td", Html::tag('strong', round($total_weight,   1) . " g"));
echo Html::tag("td", Html::tag('strong', round($total_proteins, 1) . " g"));
echo Html::tag("td", Html::tag('strong', round($total_energy,   1) . " kcal"));
echo Html::endTag('tr');

echo Html::endtag("tbody");
echo Html::endtag("table");
