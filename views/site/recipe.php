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
use \yii\web\View;
use \yii\widgets\ActiveForm;
use \yii\bootstrap\Modal;
use \skeeks\widget\chosen\Chosen;
use \app\models\Boat;
use \app\models\Dish;

$this->title = 'Recipe';

\app\assets\RecipeAsset::register($this);

// the id of the div that will contain the list, when a dish is selected
$bilanId = "bilan";

// icons to add/remove ingredients
$plusIcon  = '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>';
$trashIcon = '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>';
$copyIcon = '<span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span>';

// the URL that permits loading the list
$loadUrl = Url::toRoute("ajax/dish-info");

$ingredientsById = ArrayHelper::index($ingredients, 'id');

$dishChosenOpts = [
        'model'       => new Dish,
        'attribute'   => 'name',
        'placeholder' => 'Choose a dish',
        'items'       => ArrayHelper::map($dishes, 'id', 'name'),
        'clientEvents' =>
        [
            'change' => "function(ev, params) {
                var table = $('tbody');
                load_bilan(table, params.selected, '$loadUrl');
                update_dish_id_in_copy_form(params.selected);
            }"
        ]
    ];

$ingredientChosenOpts = [
        'items' => ArrayHelper::map($ingredients, 'id', 'name'),
        'placeholder' => 'Choose an ingredient',
    ];

$formOptions = [
        'id'     => 'new-ingredient-form',
        'method' => 'POST',
        'action' => Url::toRoute('ajax/insert-composition'),
    ];

$updateFormOptions = [
    'id'     => 'update-ingredient-form',
    'method' => 'POST',
    'action' => Url::toRoute('ajax/update-composition'),
];

$deleteDishFormOpts = [
    'id'     => 'delete-dish-form',
    'method' => 'POST',
    'action' => Url::toRoute('ajax/delete-dish'),
];

$copyFormOptions = [
    'id'     => 'new-dish-form',
    'method' => 'POST',
    'action' => Url::toRoute('ajax/copy-dish'),
];

$copyModalOpts = [
    'header' => 'Create a dish from selection',
    'id'     => 'create-dish-modal',
];

$confirmFormOpts = [
    'id'     => 'delete-confirm-modal',
    'method' => 'POST',
    'action' => Url::toRoute('ajax/update-composition'),
];

$deleteModalOpts = [
    'header' => 'Are you sure you want to delete this ingredient?',
    'id'     => 'delete-confirm-modal',
];

$deleteDishModalOpts = [
    'header' => 'Are you sure you want to delete this dish?',
    'id'     => 'delete-dish-confirm-modal',
];


$tableOptions = ['id' => 'ingredient-table'];
Html::addCssClass($tableOptions, 'table');
Html::addCssClass($tableOptions, 'table-hover');
if ($dish === 0) {
    Html::addCssClass($tableOptions, 'hidden');
}

$compoModel = new \app\models\Composition;
$dishModel        = new \app\models\Dish;
$inline           = [ 'template' => '{input}{error}' ];
$types = [
    'firstCourse' => 'First Course',
    'secondCourse' => 'Second Course',
    'dessert' => 'Dessert',
    'drink' => 'Drink',
];

$submitNewIngredientButton
    = Html::submitButton(
        $plusIcon, [
            'class' => 'btn btn-success',
        ]
    );

$submitCopyButton
    = Html::submitButton(
        'Create', [
            'class' => 'btn btn-success',
            'id' => 'submit-copy',
        ]
    );

$deleteButton
    = Html::button(
        'Delete', [
            'class' => 'btn btn-danger',
            'id'    => 'submit-delete-compo',
        ]
    );

$deleteDishButton
    = Html::button(
        'Delete', [
            'class' => 'btn btn-danger',
            'id'    => 'submit-delete-dish-compo',
        ]
    );
/**
 * Generates the Yii2 ActiveField options
 *
 * @param string $fieldId The new id to set
 *
 * @return string The option field
 */
function fieldOpts($fieldId)
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

?>

    <div class="page-header">
        <h1><?php echo $this->title; ?></h1>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">Search</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-10">
                    <?php echo Chosen::widget($dishChosenOpts); ?>
                </div>
                <div class="col-md-2">
                    <button type="button" id="copy-dish" class="disabled btn btn-primary">
                        <?php echo $copyIcon ?>
                    </button>
                    <button type="button" id="delete-dish" class="disabled btn btn-danger">
                        <?php echo $trashIcon ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-success hidden">
        <div class="panel-heading">Ingredients</div>
        <div class="panel-body">
            <div class="row">
                <div id="<?php echo $bilanId ?>" class="col-md-12">
                <?php $form = ActiveForm::begin($formOptions);
                echo Html::beginTag("table", $tableOptions);
                ?>

                <thead>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Proteins</th>
                    <th>Energy</th>
                    <th></th>
                </thead>

                <tbody>
                    <tr id="new-ingredient">
                        <td data-id="0"> <?php echo $form->field($compoModel, 'ingredient', $inline)->widget(Chosen::className(), $ingredientChosenOpts); ?> </td>
                        <td><?php echo $form->field($compoModel, 'quantity', $inline); ?></td>
                        <td></td>
                        <td><?php echo Html::activeHiddenInput($compoModel, 'dish'); ?></td>
                        <td><?php echo $submitNewIngredientButton ?></td>
                    </tr>

                    <tr class="list-group-item-success" id="total"»
                        <td><?php echo Html::tag("td", Html::tag('strong', 'Total')); ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

<?php ActiveForm::end(); ?>

<div class="hidden">
    <?php $updateForm= ActiveForm::begin($updateFormOptions);
            echo $updateForm->field($compoModel, 'dish', fieldOpts('update-dish'));
            echo $updateForm->field($compoModel, 'ingredient', fieldOpts('update-ingr'));
            echo $updateForm->field($compoModel, 'quantity', fieldOpts('update-quantity'));
        ActiveForm::end();
    ?>

    <?php $deleteDishForm = ActiveForm::begin($deleteDishFormOpts);
            echo $deleteDishForm->field($dishModel, 'id', fieldOpts('delete-dish'));
        ActiveForm::end();
    ?>

</div>

<?php Modal::begin($copyModalOpts);
    $form = ActiveForm::begin($copyFormOptions);
    echo $form->field($dishModel, 'name');
    echo $form->field($dishModel, 'type')->dropDownList($types);
    echo Html::activeHiddenInput($dishModel, 'id');
    echo $submitCopyButton;
    ActiveForm::end();
Modal::end() ?>

<?php $deleteModal = Modal::begin($deleteModalOpts);
    echo $deleteButton;
Modal::end() ?>

<?php $deleteDishModal = Modal::begin($deleteDishModalOpts);
    echo $deleteDishButton;
Modal::end() ?>

<?php $this->registerJs(
    'var url_recipe = "'          . Url::toRoute("site/recipe")          . '";' . "\n" .
    'var url_get_ingredients = "' . Url::toRoute("ajax/get-ingredients") .'";' . "\n" .
    'var delete_ingr_modal   = "#' . $deleteModal->getId() .'";' . "\n" .
    'var delete_dish_modal   = "#' . $deleteDishModal->getId() .'";' . "\n" .
    'var current_dish = ' . $dish . ";\n" .
    'var update_ingr_form  = "#' . $updateForm->getId() . "\";\n",
    View::POS_BEGIN
); ?>

