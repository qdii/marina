<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$all_dessert = app\models\Dish::find()->where('Type = \'dessert\'')->all();
$all_first   = app\models\Dish::find()->where('Type = \'firstCourse\'')->all();
$all_second  = app\models\Dish::find()->where('Type = \'secondCourse\'')->all();
$all_drink   = app\models\Dish::find()->where('Type = \'drink\'')->all();

$model       = new app\models\Meal;
$form = ActiveForm::begin([ 'id' => 'new-meal', ]);

$firstCourseField = $form->field($model, 'firstCourse');
$firstCourseField->dropDownList( yii\helpers\ArrayHelper::map( $all_first, 'id', 'name' ) );
$secondCourseField = $form->field($model, 'secondCourse');
$secondCourseField->dropDownList( yii\helpers\ArrayHelper::map( $all_second, 'id', 'name' ) );
$dessertCourseField = $form->field($model, 'dessert');
$dessertCourseField->dropDownList( yii\helpers\ArrayHelper::map( $all_dessert, 'id', 'name' ) );
$drinkCourseField = $form->field($model, 'drink');
$drinkCourseField->dropDownList( yii\helpers\ArrayHelper::map( $all_drink, 'id', 'name' ) );
?>

<?php echo $firstCourseField;    ?>
<?php echo $secondCourseField;   ?>
<?php echo $dessertCourseField;  ?>
<?php echo $drinkCourseField;    ?>

<div class="form-group">
    <?= Html::submitButton('OK', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>
