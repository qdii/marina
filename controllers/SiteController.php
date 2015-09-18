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
        $dishes  = Dish::find()->all();
        $users   = User::find()->all();

        $firstCourses = array_filter($dishes, function($dish) {
            return $dish->type === 'firstCourse';
        });
        $secondCourses = array_filter($dishes, function($dish) {
            return $dish->type === 'secondCourse';
        });
        $desserts = array_filter($dishes, function($dish) {
            return $dish->type === 'dessert';
        });
        $drinks = array_filter($dishes, function($dish) {
            return $dish->type === 'drink';
        });

        $params = [
            'boats'         => $boats,
            'vendors'       => $vendors,
            'dishes'        => $dishes,
            'users'         => $users,
            'firstCourses'  => ArrayHelper::map($firstCourses, 'id', 'name'),
            'secondCourses' => ArrayHelper::map($secondCourses, 'id', 'name'),
            'desserts'      => ArrayHelper::map($desserts, 'id', 'name'),
            'drinks'        => ArrayHelper::map($drinks, 'id', 'name'),
        ];


        return $this->render('calendar', $params);
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
