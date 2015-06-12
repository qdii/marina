<?php

namespace app\controllers;

use Yii;
use app\models\Composition;
use app\models\CompositionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CompositionController implements the CRUD actions for Composition model.
 */
class CompositionController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Composition models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompositionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Composition model.
     * @param integer $dish
     * @param integer $ingredient
     * @return mixed
     */
    public function actionView($dish, $ingredient)
    {
        return $this->render('view', [
            'model' => $this->findModel($dish, $ingredient),
        ]);
    }

    /**
     * Creates a new Composition model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Composition();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'dish' => $model->dish, 'ingredient' => $model->ingredient]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Composition model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $dish
     * @param integer $ingredient
     * @return mixed
     */
    public function actionUpdate($dish, $ingredient)
    {
        $model = $this->findModel($dish, $ingredient);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'dish' => $model->dish, 'ingredient' => $model->ingredient]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Composition model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $dish
     * @param integer $ingredient
     * @return mixed
     */
    public function actionDelete($dish, $ingredient)
    {
        $this->findModel($dish, $ingredient)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Composition model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $dish
     * @param integer $ingredient
     * @return Composition the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($dish, $ingredient)
    {
        if (($model = Composition::findOne(['dish' => $dish, 'ingredient' => $ingredient])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
