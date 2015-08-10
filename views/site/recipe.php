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
use \app\models\Boat;
use \app\models\Dish;

$this->title = 'Recipe';

\app\assets\RecipeAsset::register($this);

// the id of the div that will contain the list, when a dish is selected
$bilanId = "bilan";

// icons to add/remove ingredients
$plusIcon  = '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>';
$minusIcon = '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>';
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

$tableOptions = ['id' => 'ingredient-table'];
Html::addCssClass($tableOptions, 'table');
Html::addCssClass($tableOptions, 'table-hover');

$compositionModel = new \app\models\Composition;
$inline           = [ 'template' => '{input}{error}' ];

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

if ($dish === 0) {
    Html::addCssClass($tableOptions, 'hidden');
}
?>

<div class="container">

    <div class="row">
        <div class="col-md-11">
            <?php echo Chosen::widget($dishChosenOpts); ?>
        </div>
        <div class="col-md-1">
            <button class="btn btn-primary"><?php echo $copyIcon ?></button>
        </div>
    </div>

    <div class="row">
        <div id="<?php echo $bilanId ?>" class="col-md-12">
        <?php $form = ActiveForm::begin($formOptions);
        echo Html::beginTag("table", $tableOptions);
        ?>

        <thead>
            <th>Name</th>
            <th>Weight</th>
            <th>Proteins</th>
            <th>Energy</th>
            <th></th>
        </thead>

        <tbody>
            <tr id="new-ingredient">
                <td data-id="0"> <?php echo $form->field($compositionModel, 'ingredient', $inline)->widget(Chosen::className(), $ingredientChosenOpts); ?> </td>
                <td><?php echo $form->field($compositionModel, 'quantity', $inline); ?></td>
                <td></td>
                <td><?php echo Html::activeHiddenInput($compositionModel, 'dish'); ?></td>
                <td><?php echo Html::submitButton($plusIcon, [ 'class' => 'btn btn-success' ]); ?></td>
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

<?php ActiveForm::end(); ?>

<div class="hidden">

    <?php $updateForm = ActiveForm::begin($updateFormOptions);
            echo $form->field($compositionModel, 'dish', fieldOptions('update-dish'));
            echo $form->field($compositionModel, 'ingredient', fieldOptions('update-ingr'));
            echo $form->field($compositionModel, 'quantity', fieldOptions('update-quantity'));
        ActiveForm::end($updateFormOptions);
    ?>

</div>
