<?php
use app\components\ModifiableList;
use app\components\Panel;
use app\models\Ingredient;
use yii\helpers\ArrayHelper;

\app\assets\IngredientAsset::register( $this );

$all_ingredients = array_map( function( $ingredient ) { return ['label' => $ingredient->name, 'data-id' => $ingredient->id ]; }, Ingredient::find()->all() );

$list = new ModifiableList([
    'bootstrapList' => new \app\components\BootstrapList([
        'id'      => 'ingredient-list',
        'items'   => $all_ingredients,
        'options' => [ 'type'                => 'list-group',
                       'item-click-callback' => 'on_ingredient_list_click' ],
    ]),
    'buttons'       => new yii\bootstrap\ButtonGroup([
        'buttons'        => [ [ 'label' => '<span class="glyphicon glyphicon-plus"   aria-hidden="true">', 'id' => 'button-ingredient-add' ], 
                              [ 'label' => '<span class="glyphicon glyphicon-remove" aria-hidden="true">', 'id' => 'button-ingredient-remove' ] 
                            ],
        'encodeLabels'   => false,
    ]),
]);

$this->registerJs( "$('#" . $list->getID() . "').on('click', 'ol,dd,li', function( event ) { on_ingredient_list_click( event ); } );" );

Panel::begin( [ "header" => [ "label" => "Ingrédients" ] ] );
echo $list->run();
Panel::end();

?>
