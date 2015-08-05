<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\models\Ingredient;
use app\models\User;
use app\models\Cruise;
use app\models\Unit;
use app\models\Dish;
use app\models\Boat;
use app\models\Meal;
use app\models\Composition;
use app\components\EventMaker;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionRecipe($id = 0)
    {
        $boat        = Boat::find()->one();
        $ingredients = Ingredient::find()->all();
        $components = [];
        if ( $id !== 0 ) {
            $components = Composition::findAll(['dish' => $id]);
        }

        $params = [
            'boat'        => $boat,
            'ingredients' => $ingredients,
            'dish'        => $id,
            'components'  => $components,
        ];

        return $this->render('recipe', $params);
    }

    public function actionCalendar($id = 1)
    {
        $users = User::find()->all();
        $units = Unit::find()->all();

        $boats    = Boat::find()->all();
        $boatById = ArrayHelper::index($boats, 'id');
        $boat     = null;
        if (key_exists($id, $boatById)) {
            $boat = $boatById[$id];
        }

        $cruises    = Cruise::find()->all();
        $cruiseById = ArrayHelper::index($cruises, 'id');
        $cruise     = null;
        foreach ( $cruises as $cr ) {
            if ($cr->boat == $id) {
                $cruise = $cr;
                break;
            }
        }

        $meals        = [];
        if ($cruise !== null) {
            $meals = Meal::findAll(['cruise' => $cruise->id]);
        }

        $dishes       = Dish::find()->all();
        $dishIds      = ArrayHelper::getColumn($dishes, 'id');
        $compositions = Composition::findAll(['dish' => $dishIds]);
        $ingrIds      = ArrayHelper::getColumn($compositions, 'ingredient');
        $ingredients  = Ingredient::findAll(['id' => $ingrIds]);

        $types  = [ 'breakfast', 'lunch', 'dinner', 'snack' ];

        $params = [
            'users'        => $users,
            'units'        => $units,
            'dishes'       => $dishes,
            'meals'        => $meals,
            'types'        => $types,
            'boats'        => $boats,
            'boat'         => $boat,
            'cruises'      => $cruises,
            'cruise'       => $cruise,
            'compositions' => $compositions,
            'ingredients'  => $ingredients,
            ];
        return $this->render('calendar', $params);
    }

    public function actionNewIngredient()
    {
        $model = new \app\models\Ingredient();
        if ($model->load(Yii::$app->request->post()))
        {
            assert( $model->validate() );
            $model->save();
        }
    }

    /**
     * Adds a new boat to the database
     *
     * @return void
     */
    public function actionNewBoat()
    {
        $model = new \app\models\Boat();
        if ($model->load(Yii::$app->request->post())) {
            assert($model->validate());
            $model->save();
        }
        $boatId = $model->id;

        // hack: create a new cruise here
        $cruise             = new \app\models\Cruise();

        $cruise->boat       = $boatId;
        $cruise->dateStart  = "2015-01-01";
        $cruise->dateFinish = "2020-01-01";
        $cruise->save();

        $this->redirect(['site/calendar', 'id' => $boatId]);
    }

    public function actionNewMeal()
    {
        $model = new \app\models\Meal();
        if ($model->load(Yii::$app->request->post()))
        {
            assert( $model->validate() );
            $model->save();
        }

        $cruise = $model->getCruise0()->one();
        $boat   = $cruise->getBoat0()->one();

        $this->redirect(['site/calendar', 'id' => $boat->id]);
    }

    /**
     * Deletes an existing Meal
     *
     * @param integer $id The id of the meal to delete
     *
     * @return void
     */
    public function actionDeleteMeal($id)
    {
        $boatId = 0;
        if (($model = \app\models\Meal::findOne($id)) !== null) {
            $cruise = $model->getCruise0()->one();
            $boat   = $cruise->getBoat0()->one();
            $boatId = $boat->id;

            $model->delete();
        }


        $this->redirect(['site/calendar', 'id' => $boatId]);
    }

    /**
     * Updates an existing Meal
     *
     * @param integer $id The id of the meal to update
     *
     * @return void
     */
    public function actionUpdateMeal($id)
    {
        $boatId = 0;
        if (($model = \app\models\Meal::findOne($id)) !== null) {
            $model->load(Yii::$app->request->post());
            $cruise = $model->getCruise0()->one();
            $boat   = $cruise->getBoat0()->one();
            $boatId = $boat->id;

            $model->save();
        }

        $this->redirect(['site/calendar', 'id' => $boatId]);
    }

    public function actionAjaxDeleteMeal($id)
    {
        $meal = \app\models\Meal::find()->where( [ "id" => $id ] )->one();
        $meal->delete();
    }

    public function actionAjaxGetMeal($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $meal = \app\models\Meal::find()->where( ["id" => $id] )->one();
        if ($meal == null)
            return;

        return $meal->getAttributes( [ "id", "nbGuests", "firstCourse", "secondCourse", "dessert", "drink", "cook", "date" ] );
    }

    public function actionAjaxUser($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $user = \app\models\User::find()->where( ["id" => $id] )->one();
        return $user->getAttributes( [ "username" , "id" ] );
    }

    public function actionListIngredients()
    {
        return $this->render('list-ingredients');
    }

    public function actionManyColumnListDish($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $query = new \yii\db\Query;
        $query->select(
            [
                'composition.quantity',
                'ingredient.name',
                'ingredient.id',
                'ingredient.energy_kcal',
                'ingredient.protein'
            ]
        )
            ->from('composition')
            ->join(
                'left join',
                'ingredient',
                'composition.ingredient = ingredient.id'
            )
            ->where(['dish' => $id])
            ->addOrderBy(['ingredient.name' => SORT_DESC]);

        return $query->all();
    }

    /**
     * Insert composition in the database
     *
     * @return void
     */
    public function actionInsertComposition()
    {
        $model = new \app\models\Composition;
        $model->load(Yii::$app->request->post());
        $model->save();
    }

    /**
     * Insert composition in the database
     *
     * @return void
     */
    public function actionUpdateComposition()
    {
        $args = Yii::$app->request->post();
        $composition = $args['Composition'];

        $compositionHelper = new \app\components\CompositionHelper();
        $compositionHelper->updateDelete(
            $composition['dish'],
            $composition['ingredient'],
            $composition['quantity']
        );
    }

    /**
     * Returns fullcalendar events
     *
     * @param int $id The id of a cruise
     *
     * @return array An array of fullcalendar events
     */
    public function actionGetMeals($id = 0)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $units        = Unit::find()->all();
        $users        = User::find()->all();
        $dishes       = Dish::find()->all();
        $compositions = Composition::find()->all();
        $ingredients  = Ingredient::find()->all();

        $meals    = Meal::findAll(['cruise' => $id]);
        $mealIds  = ArrayHelper::getColumn($meals, 'id');

        $drinks   = Dish::findAll(['type' => 'drink']);
        $desserts = Dish::findAll(['type' => 'dessert']);
        $firsts   = Dish::findAll(['type' => 'firstCourse']);
        $seconds  = Dish::findAll(['type' => 'secondCourse']);

        $eventMaker = new EventMaker(
            ArrayHelper::index($desserts, 'id'),
            ArrayHelper::index($firsts, 'id'),
            ArrayHelper::index($seconds, 'id'),
            ArrayHelper::index($drinks, 'id'),
            ArrayHelper::index($users, 'id'),
            $ingredients,
            $compositions,
            $units,
            $dishes,
            $meals
        );
        $events = $eventMaker->getEventsAndBilanFromMeals($meals);
        return $events;
    }
}
