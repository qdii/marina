<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

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

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionRepas()
    {
        return $this->render('repas');
    }

    public function actionAdmin()
    {
        return $this->render('admin');
    }

    public function actionAdminUser()
    {
        return $this->render('admin/user');
    }

    public function actionAdminDish()
    {
        return $this->render('admin/dish');
    }

    public function actionAdminIngredient()
    {
        return $this->render('admin/ingredient');
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

    public function actionNewMeal()
    {
        $model = new \app\models\Meal();
        if ($model->load(Yii::$app->request->post()))
        {
            assert( $model->validate() );
            $model->save();
        }

        $this->redirect(['site/repas']);
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
        if (($model = \app\models\Meal::findOne($id)) !== null) {
            $model->load(Yii::$app->request->post());
            $model->save();
        }

        $this->redirect(['site/repas']);
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

        return $meal->getAttributes( [ "id", "nbGuests", "firstCourse", "secondCourse", "dessert", "drink", "cook", "date", "type" ] );
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
}
