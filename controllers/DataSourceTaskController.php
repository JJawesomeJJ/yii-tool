<?php

namespace backend\modules\tool\controllers;

use Yii;
use backend\modules\tool\models\DataSourceTask;
use yii\data\ActiveDataProvider;
use backend\modules\tool\excel\ExcelToolController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\tool\models\AutoQuery;
use backend\modules\tool\models\AutoSearchModle;
use backend\modules\tool\helpers\functions;
use backend\modules\tool\helpers\ModelUtil;

/**
 * FillFormController implements the CRUD actions for FillFormTool model.
 */
class DataSourceTaskController extends ExcelToolController
{
    /**
     * @inheritdoc
     */
    public $rules=['class_path'=>'='];
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    public function SetExcelModel()
    {
            return new DataSourceTask();
    }


    /**
     * Lists all FillFormTool models.
     * @return mixed
     */
    public function actionIndex()
    {
        DataSourceTask::CreateTable();
        $dataProvider = new ActiveDataProvider([
            'query' => AutoQuery::query(DataSourceTask::class,$this->rules),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model'=>AutoSearchModle::GetObject(DataSourceTask::class)
        ]);
    }

    /**
     * Displays a single FillFormTool model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new FillFormTool model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DataSourceTask();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->redirectParent(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing FillFormTool model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->redirectParent(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing FillFormTool model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the FillFormTool model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DataSourceTask the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DataSourceTask::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionList(){
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            $search=AutoSearchModle::GetObject(DataSourceTask::class);
            $query=AutoQuery::query(DataSourceTask::class,$this->rules,$search);
            return ['data'=>ModelUtil::pager($query,functions::HttpParams("page",1),functions::HttpParams("page_size",8))];
    }
    public function actionOne(){
          Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
          return ['data'=>DataSourceTask::find()->asArray()->one()];
    }
}
