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
use app\models\Auth;
use app\models\Composition;
use app\components\EventMaker;

class SiteController extends Controller
{
    public function behaviors()
    {
        $behaviors = [];
        $rules = [
            [
                'actions' => ['logout'],
                'allow' => true,
                'roles' => ['@'],
            ],
            [
                'actions' => ['login', 'enter', 'auth'],
                'allow' => true,
                'roles' => ['?'],
            ],
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ];

        // bypass login rule
        if (defined(YII_ENV_TEST)) {
            $rules[] = [
                'actions' => ['bypass'],
                'allow' => true,
                'roles' => ['?'],
            ];
        }

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => $rules,
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'logout' => ['post'],
            ],
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
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'authSuccess'],
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

    public function actionLogin()
    {
        return $this->render('login');
    }

    public function actionLogout()
    {
        // logs out the user
        Yii::$app->user->logout();

        $this->redirect(['login']);
    }

    /**
     * Checks an user's password and proceeds to login if correct
     *
     * @return void
     */
    public function actionEnter()
    {
        $loginForm = new \app\models\LoginForm();
        if (!$loginForm->load(Yii::$app->request->post())) {
            return $this->render("invalid-password");
        }

        $identity = User::findOne(['username' => $loginForm->username]);
        if ($identity === null) {
            return $this->render("invalid-password");
        }

        $validPassword = Yii::$app->getSecurity()->validatePassword(
            $loginForm->password,
            $identity->password
        );

        if (!$validPassword) {
            return $this->render("invalid-password");
        }

        // logs in the user
        Yii::$app->user->login($identity);

        // redirects him to the index
        $this->redirect(['site/index']);
    }

    public function authSuccess($client)
    {
        $attributes = $client->getUserAttributes();
        $email = $attributes['email'];
        $id    = $attributes['id'];
        $src   = $client->getId();

        $auth = Auth::find()->where([
            'src'   => $src,
            'srcid' => (string)$id,
        ])->one();

        // if the user is already logged in
        if (!Yii::$app->user->isGuest) {
            if ($auth) {
                return;
            }

            // create a new entry in Auth
            $auth = new Auth([
                'user'  => Yii::$app->user->id,
                'src'   => $src,
                'srcid' => $id,
            ]);
            $auth->save();
            return;
        }

        // if the user is NOT logged-in but has a session
        if ($auth) { // login
            $user = User::findOne(['id' => $auth->user]);
            Yii::$app->user->login($user);
            return;
        }

        // if the user is NOT logged-in and DOES NOT have a session
        if (User::find()->where(['email' => $email])->exists()) {
                // TODO treat that case:
                // User with the same email as in {client} account already exists
                // but isn't linked to it. Login using email first to link it.",
                // ['client' => $client->getTitle()]),
        } else {
            $authHelper = new \app\components\AuthHelper();
            $user = $authHelper->createNewUserAndAuthenticate(
                $email,
                $email,
                $src,
                (string)$id
            );
            if ($user === null) {
                return;
            }

            Yii::$app->user->login($user);
        }
    }

    /**
     * Bypass login
     */
    public function actionBypass()
    {
        if (!YII_DEBUG) {
            throw new \yii\web\NotFoundHttpException;
        }
    }
}
