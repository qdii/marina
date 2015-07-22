<?php
use \app\components\ManyColumnList;
use \app\models\Ingredient;
use \yii\helpers\ArrayHelper;
use \skeeks\widget\chosen\Chosen;

$ingredientsById = ArrayHelper::index($ingredients, 'id');

$total_proteins = 0;
$total_energy   = 0;
$total_weight   = 0;

$items      = [];
$attributes = [];

$ingredModel = new \app\models\Ingredient;
$ingredientChoser = Chosen::widget(
    [
        'model'       => $ingredModel,
        'attribute'   => 'name',
        'placeholder' => 'Choose an ingredient',
        'items'       => ArrayHelper::map($ingredients, 'id', 'name'),
        'clientEvents' =>
        [
            'change' => "function(ev, params) {
               console.log('clicked on' + params.selected);
            }"
        ]
    ]
);

$i = 0;
foreach ( $components as $component ) {
    $ingredient = $ingredientsById[ $component->ingredient ];
    $quantity   = $component->quantity;
    $proteins   = $quantity * $ingredient['protein'] / 100;
    $energy     = $quantity * $ingredient['energy_kcal'] / 100;
    $items[]
        = [
            $ingredient['name'],
            round($quantity, 1) . " g",
            round($proteins, 1) . " g",
            round($energy, 1) . " kcal",
            '<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>'
        ];
    $attributes[$i++]['data-id'] = $component->ingredient;
    $total_weight += $quantity;
    $total_proteins += $proteins;
    $total_energy += $energy;
}

// line to add a new ingredient
$plusIcon = '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>';
$items[] = [ $ingredientChoser, '', '' ,'', $plusIcon ];
$attributes[$i++]['data-id'] = 'new-ingredient';

$options
    = [
        'items'      => $items,
        'headers'    => [ 'Name', 'Weight', 'Proteins', 'Energy', '' ],
        'showTotal'  => true,
        'totals'     =>
        [
            round($total_weight, 1) . " g",
            round($total_proteins, 1) . " g",
            round($total_energy, 1) .  " kcal",
            '',
        ],
        'attributes' => $attributes,
        'data_id'    => $dish->id
    ];

echo ManyColumnList::widget($options);
