<?php

namespace app\controllers;

use Yii;
use app\models\Proportion;
use app\models\ProportionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProportionController implements the CRUD actions for Proportion model.
 */
class ProportionController extends Controller
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
     * Lists all Proportion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProportionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Proportion model.
     * @param integer $ingredient
     * @param integer $product
     * @return mixed
     */
    public function actionView($ingredient, $product)
    {
        return $this->render('view', [
            'model' => $this->findModel($ingredient, $product),
        ]);
    }

    /**
     * Creates a new Proportion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Proportion();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'ingredient' => $model->ingredient, 'product' => $model->product]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Proportion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $ingredient
     * @param integer $product
     * @return mixed
     */
    public function actionUpdate($ingredient, $product)
    {
        $model = $this->findModel($ingredient, $product);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'ingredient' => $model->ingredient, 'product' => $model->product]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Proportion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $ingredient
     * @param integer $product
     * @return mixed
     */
    public function actionDelete($ingredient, $product)
    {
        $this->findModel($ingredient, $product)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Proportion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $ingredient
     * @param integer $product
     * @return Proportion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($ingredient, $product)
    {
        if (($model = Proportion::findOne(['ingredient' => $ingredient, 'product' => $product])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
