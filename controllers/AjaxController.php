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
use app\components\CompositionHelper;

class AjaxController extends Controller
{
    public function behaviors()
    {
        return [];
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

    /**
     * Deletes an existing Meal
     *
     * @param integer $id The id of the meal to delete
     *
     * @return void
     */
    public function actionDeleteMeal($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $meal = \app\models\Meal::find()->where( [ "id" => $id ] )->one();
        $meal->delete();
    }

    /**
     * Deletes an existing Dish
     *
     * @param integer $id The id of the dish to delete
     *
     * @return void
     */
    public function actionDeleteDish()
    {
        $post = Yii::$app->request->post();
        $dish = Dish::findOne(['id' => $post['Dish']['id']]);


        if ($dish === null) {
            \Yii::$app->response->setStatusCode(400);
            return;
        }

        // remove all compositions first
        Composition::deleteAll(['dish' => $dish->id]);

        // then remove the dish
        $dish->delete();
    }

    /**
     * Returns attributes of an existing Meal
     *
     * @param integer $id The id of the meal to view
     *
     * @return string A JSON array of attributes of the meal
     */
    public function actionGetMeal($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $meal = \app\models\Meal::find()->where( ["id" => $id] )->one();
        if ($meal == null)
            return;

        return $meal->getAttributes( [ "id", "nbGuests", "firstCourse", "secondCourse", "dessert", "drink", "cook", "date", "backgroundColor" ] );
    }

    public function actionUser($id)
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

        $compositionHelper = new \app\components\CompositionHelper();

        return $compositionHelper->getInformation($id);
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
        $compoIds     = ArrayHelper::getColumn($compositions, 'ingredient');
        $ingredients  = Ingredient::findAll(['id' => array_unique($compoIds)]);

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

    /**
     * Returns information about a given dish
     *
     * @param int $id The id of the dish
     *
     * @return string a JSON array of information about this dish
     */
    public function actionDishInfo($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $helper = new CompositionHelper;
        return $helper->getInformation($id);
    }

    /**
     * Returns the ingredient list as an array
     *
     * @param array $ids The ids of the ingredients to select, or an empty
     * array to select them all
     *
     * @return string a JSON string representing the ingredients
     */
    public function actionGetIngredients($ids = [])
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = new \yii\db\Query;
        $query->select([ 'name', 'id'])
            ->from('ingredient')
            ->addOrderBy(['name' => SORT_DESC]);

        if (count($ids)) {
            $query = $query->where(['id' => $ids]);
        }

        return $query->all();
    }

    /**
     * Creates a new dish with the same composition as another dish
     *
     * @return void
     */
    public function actionCopyDish()
    {
        $post = Yii::$app->request->post();

        $dish = new Dish;
        $dish->load($post);
        $id = $post['Dish']['id'];

        if (($source = Dish::findOne($id)) === null) {
            \Yii::$app->response->setStatusCode(400);
            return;
        }

        $dish->save();

        if (!CompositionHelper::cloneDish($id, $dish->id)) {
            \Yii::$app->response->setStatusCode(500);
        }

        return $dish->id;
    }
}
