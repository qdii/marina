<?php
use \app\components\ManyColumnList;
use yii\helpers\ArrayHelper;

$ingredientsById = ArrayHelper::index($ingredients, 'id');

$total_proteins = 0;
$total_energy   = 0;
$total_weight   = 0;

$items      = [];

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
    $total_weight += $quantity;
    $total_proteins += $proteins;
    $total_energy += $energy;
}

// line to add a new ingredient
$items[] = [ '', '', '' ,'','<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>' ];

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
        ]
    ];

echo ManyColumnList::widget($options);
