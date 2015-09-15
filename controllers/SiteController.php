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
use app\models\Vendor;
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
        $dishes      = Dish::find()->all();
        $components = [];
        $ingredients = [];
        if ( $id !== 0 ) {
            $components = Composition::findAll(['dish' => $id]);
            $compoIds   = ArrayHelper::getColumn($components, 'ingredient');
            $ingredients = Ingredient::findAll(['id' => $compoIds]);
        }

        $params = [
            'boat'        => $boat,
            'ingredients' => $ingredients,
            'dish'        => $id,
            'dishes'      => $dishes,
            'components'  => $components,
        ];

        return $this->render('recipe', $params);
    }

    public function actionCalendar($id = 1)
    {
        $vendors = Vendor::find()->all();
        $boats   = Boat::find()->all();

        $params = [
            'boats'        => $boats,
            'vendors'      => $vendors,
        ];

        return $this->render('calendar2', $params);
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

    public function actionCookbook()
    {
        $boats   = \app\models\Boat::find()->all();
        $vendors = \app\models\Vendor::find()->all();

        $params = [
            'boats'   => $boats,
            'vendors' => $vendors,
        ];

        return $this->render('cookbook', $params);
    }
}
