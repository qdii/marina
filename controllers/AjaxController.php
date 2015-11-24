<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\models\Boat;
use app\models\Composition;
use app\models\Course;
use app\models\Cruise;
use app\models\Dish;
use app\models\Fraction;
use app\models\Ingredient;
use app\models\Meal;
use app\models\Product;
use app\models\Proportion;
use app\models\Unit;
use app\models\User;
use app\components\EventMaker;
use app\components\CompositionHelper;
use app\components\PriceComputer;
use app\components\ProductPicker;

class AjaxController extends Controller
{
    public function behaviors()
    {
        $behaviors = [];
        $rules = [
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => $rules,
        ];

        return $behaviors;
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
     * @return void
     */
    public function actionUpdateMeal()
    {
        $model = new \app\models\NewMeal();
        if (!$model->load(Yii::$app->request->post())) {
            return;
        }

        // TODO: Make sure firstCourse, secondCourse, etc. exist

        $transaction = Yii::$app->getDb()->beginTransaction();

        $meal = Meal::findOne(['id' => $model->mealId]);
        if (!$meal) {
            return;
        }
        $mealId = $meal->id;

        $meal->nbGuests = $model->nbGuests;
        $meal->date     = $model->date;
        $meal->cook     = $model->cook;
        $meal->cruise   = $model->cruise;
        $meal->save();

        $firstCourse       = Course::findOne(['meal' => $mealId, 'type' => 0]);
        $firstCourse->dish = $model->firstCourse;
        $firstCourse->save();

        $secondCourse       = Course::findOne(['meal' => $mealId, 'type' => 1]);
        $secondCourse->dish = $model->secondCourse;
        $secondCourse->save();

        $dessert       = Course::findOne(['meal' => $mealId, 'type' => 2]);
        $dessert->dish = $model->dessert;
        $dessert->save();

        $drink = Course::findOne(['meal' => $mealId, 'type' => 3]);
        $drink->dish = $model->drink;
        $drink->save();

        $transaction->commit();
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
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new \app\models\NewMeal();
        if (!$model->load(Yii::$app->request->post())) {
            return;
        }
        $transaction = Yii::$app->getDb()->beginTransaction();

        $meal = new Meal;
        $meal->nbGuests = $model->nbGuests;
        $meal->date     = $model->date;
        $meal->cook     = $model->cook;
        $meal->cruise   = $model->cruise;
        $meal->save();
        $mealId = $meal->id;
        assert($mealId);

        $firstCourse       = new Course;
        $firstCourse->dish = $model->firstCourse;
        $firstCourse->meal = $mealId;
        $firstCourse->type = 0;
        $firstCourse->save();

        $secondCourse       = new Course;
        $secondCourse->dish = $model->secondCourse;
        $secondCourse->meal = $mealId;
        $secondCourse->type = 1;
        $secondCourse->save();

        $dessert       = new Course;
        $dessert->dish = $model->dessert;
        $dessert->meal = $mealId;
        $dessert->type = 2;
        $dessert->save();

        $drink = new Course;
        $drink->dish = $model->drink;
        $drink->meal = $mealId;
        $drink->type = 3;
        $drink->save();

        $transaction->commit();
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
        $meal = Meal::findOne(["id" => $id]);
        if ($meal == null) {
            return;
        }

        $attr = $meal->getAttributes([
            "id", "nbGuests", "cook", "date", "backgroundColor", "cruise" ]);

        $attr['firstCourse'] = $meal->getFirstCourse0()->one()->id;
        $attr['secondCourse'] = $meal->getSecondCourse0()->one()->id;
        $attr['dessert'] = $meal->getDessert0()->one()->id;
        $attr['drink'] = $meal->getDrink0()->one()->id;

        return $attr;
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

        $meals = Meal::findAll(['cruise' => $id]);
        $eventMaker = new EventMaker;
        return $eventMaker->getEventsAndBilanFromMeals($meals);
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

        $copyDish = new \app\models\CopyDish;
        if (!$copyDish->load($post) && !$copyDish->validate()) {
            \Yii::$app->response->setStatusCode(500);
        }

        $id = $post['CopyDish']['id'];

        if (($source = Dish::findOne($id)) === null) {
            \Yii::$app->response->setStatusCode(400);
            return;
        }

        $dish = new \app\models\Dish;
        $transaction = $dish->getDb()->beginTransaction();
        $dish->name = $copyDish->name;
        $dish->type = $copyDish->type;
        if (!$dish->save()) {
            \Yii::$app->response->setStatusCode(500);
        }

        if (!CompositionHelper::cloneDish($id, $dish->id)) {
            \Yii::$app->response->setStatusCode(500);
        }

        $transaction->commit();

        return $dish->id;
    }

    /**
     * Returns the data for a cookbook
     *
     * @param int $cruiseId The id of the cruise the cookbook is for
     * @param int $vendorId The shop the products are to be bought in
     * @param int $guests   The number of guests
     *
     * @return string A json array with information for the cookbook
     */
    public function actionGetCookbook($cruiseId, $vendorId, $guests)
    {
        $cruise = \app\models\Cruise::findOne(['id' => $cruiseId]);
        if ($cruise === null) {
            return "";
        }

        $vendor = \app\models\Vendor::findOne(['id' => $vendorId]);
        if ($vendor === null) {
            return "";
        }

        $helper = new CompositionHelper;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $cookbook = $helper->getCookbook($cruise, $vendor, $guests);
        ArrayHelper::multisort($cookbook, 'name');
        return $cookbook;
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
            Fraction::find()->all(),
            $ingredients
        );

        $ingredientList = [];
        foreach ( $priceComputer->items as $id => $item ) {
            $ingredientList[] = [
                'id' => $id,
                'name' => $item['name'],
                'qty' => $item['quantity']
            ];
        }
        $lists = $productPicker->getShoppingListFromIngredientList(
            $ingredientList, intval($vendorId)
        );

        $result = [];
        foreach ( $lists['products'] as $id => $product ) {
            $result[$id] = [
                'id'       => $id,
                'quantity' => $product['qty'],
                'name'     => $product['name'],
                'type'     => 'product'
            ];
        }

        foreach ( $lists['ingredients'] as $id => $ingredient ) {
            $result[$id] = [
                'id'       => $id,
                'quantity' => $ingredient['qty'],
                'name'     => $ingredient['name'],
                'type'     => 'ingredient'
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

    /**
     * Creates a new cruise with the same dates, name, boat and meals as
     * another cruise.
     *
     * @return void
     */
    public function actionDuplicateCruise()
    {
        $post  = Yii::$app->request->post();
        $model = new \app\models\Cruise();
        if (!$model->load($post)) {
            throw new \Exception("Invalid cruise");
        }

        $fromCruise = \app\models\Cruise::findOne(['id' => $post['Cruise']['id']]);

        $siteHelper = new \app\components\SiteHelper();

        $newCruise = $siteHelper->duplicateCruise($fromCruise);

        return $newCruise->id;
    }
}
