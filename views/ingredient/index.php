<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\IngredientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Ingredients');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ingredient-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Ingredient'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'price',
            'duration',
            'unit',
            // 'sucrose',
            // 'glucose',
            // 'fructose',
            // 'water',
            'energy_kcal',
            // 'energy_kj',
            'protein',
            // 'lipid',
            // 'fat',
            // 'ash',
            // 'carbohydrates',
            // 'sugars',
            // 'fiber',
            // 'weight',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
