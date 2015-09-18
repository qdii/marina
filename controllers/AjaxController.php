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
use app\models\Product;
use app\models\Proportion;
use app\models\Fraction;
use app\components\EventMaker;
use app\components\CompositionHelper;
use app\components\PriceComputer;
use app\components\ProductPicker;

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
    public function actionUpdateMeal($mealId)
    {
        if (($model = \app\models\Meal::findOne($mealId)) !== null) {
            $model->load(Yii::$app->request->post());
            $model->save();
        }
    }

    /**
     * Deletes an existing Meal
     *
     * @param integer $mealId The id of the meal to delete
     *
     * @return void
     */
    public function actionDeleteMeal($mealId)
    {
        $meal = \app\models\Meal::find()->where([ "id" => $mealId ])->one();
        $meal->delete();
    }

    /**
     * Registers a new meal in the database
     *
     * @return void
     */
    public function actionNewMeal()
    {
        $model = new \app\models\Meal();
        if ($model->load(Yii::$app->request->post())) {
            assert($model->validate());
            $model->save();
        }
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

        return $meal->getAttributes( [ "id", "nbGuests", "firstCourse", "secondCourse", "dessert", "drink", "cook", "date", "backgroundColor", "cruise" ] );
    }

    public function actionUser($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $user = \app\models\User::find()->where( ["id" => $id] )->one();
        return $user->getAttributes( [ "username" , "id" ] );
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
     * Return fullcalendar events
     *
     * @param int $id The id of a boat
     *
     * @return array An array of fullcalendar events
     */
    public function actionGetMealsFromBoat($id = 0)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $boat = Boat::findOne(['id' => $id]);
        if ($boat === null) {
            return [];
        }

        $cruises = $boat->getCruises()->all();
        if (empty($cruises)) {
            return [];
        }

        return $this->actionGetMeals($cruises[0]->id);
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

    /**
     * Returns the data for a cookbook
     *
     * @param int $boatId   The id of the boat the cookbook is for
     * @param int $vendorId The shop the products are to be bought in
     * @param int $guests   The number of guests
     *
     * @return string A json array with information for the cookbook
     */
    public function actionGetCookbook($boatId, $vendorId, $guests)
    {
        $boat   = \app\models\Boat::findOne(['id' => $boatId]);
        if ($boat === null) {
            return "";
        }

        $vendor = \app\models\Vendor::findOne(['id' => $vendorId]);
        if ($vendor === null) {
            return "";
        }

        $helper = new CompositionHelper;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return $helper->getCookbook($boat, $vendor, $guests);
    }

    /**
     * Returns an ingredient list for the given cruise
     */
    public function actionGetIngredientList($cruiseId, $vendorId)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $ingredients  = Ingredient::find()->all();
        $compositions = Composition::find()->all();
        $units        = Unit::find()->all();
        $dishes       = Dish::find()->all();
        $cruise = Cruise::findOne(['id' => $cruiseId]);
        $meals = $cruise->getMeals()->where(['cruise' => $cruiseId])->all();
        $priceComputer = new PriceComputer(
            $ingredients,
            $compositions,
            $units,
            $dishes,
            $meals
        );
        $priceComputer->addMeals($meals);

        if ($vendorId == 0) {
            $result = $priceComputer->items;
            ArrayHelper::multisort($result, 'name');
            return $result;
        }

        $productPicker = new ProductPicker(
            Product::find()->all(),
            Cruise::find()->all(),
            $meals,
            $dishes,
            Proportion::find()->all(),
            Fraction::find()->all()
        );

        $ingredientList = [];
        foreach ( $priceComputer->items as $id => $item ) {
            $ingredientList[] = [
                'id' => $id,
                'name' => $item['name'],
                'qty' => $item['quantity']
            ];
        }
        $productList = $productPicker->getShoppingListFromIngredientList(
            $ingredientList, $vendorId
        );

        $result = [];
        foreach ( $productList as $id => $product ) {
            $result[$id] = [
                'quantity' => $product['qty'],
                'name'     => $product['name']
            ];
        }
        ArrayHelper::multisort($result, 'name');
        return $result;
    }
    /**
     * Returns an ingredient list for the given boat
     */
    public function actionGetIngredientListFromBoat($boatId, $vendorId)
    {
        $cruise = Cruise::findOne(['boat' => $boatId]);
        return $this->actionGetIngredientList($cruise->id, intval($vendorId));
    }

    public function actionGetCruiseFromBoat($boatId)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $cruise = Cruise::findOne(['boat' => $boatId]);
        return $cruise->id;
    }
}
