<?php

namespace backend\modules\tool\controllers;

use backend\modules\awesome\annotation\source;
use backend\modules\tool\Job\SqlNode;
use backend\modules\tool\models\DataSourceTask;
use backend\modules\tool\models\SqlConfig;
use dsj\components\controllers\WebController;
use Yii;
use backend\modules\tool\models\Node;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\tool\excel\ExcelToolController;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\tool\models\AutoQuery;
use backend\modules\tool\models\AutoSearchModle;
use backend\modules\tool\helpers\functions;
use backend\modules\tool\helpers\ModelUtil;
use yii\web\Response;

/**
 * FillFormController implements the CRUD actions for FillFormTool model.
 */
class NodeController extends ExcelToolController
{
    /**
     * @inheritdoc
     */
    public $enableCsrfValidation=false;
    public $rules=['node_name'=>'like'];
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
            return new Node();
    }


    /**
     * Lists all FillFormTool models.
     * @return mixed
     */
    public function actionIndex()
    {
        Node::CreateTable();
        $query=Node::find()->orderBy("id desc");
        $dataProvider = new ActiveDataProvider([
            'query' => AutoQuery::query($query,$this->rules),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model'=>AutoSearchModle::GetObject(Node::class)
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
        $model = new Node();

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
     * @return Node the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Node::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionList(){
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            $search=AutoSearchModle::GetObject(Node::class);
            $query=AutoQuery::query(Node::class,$this->rules,$search);
            return ['data'=>ModelUtil::pager($query,functions::HttpParams("page",1),functions::HttpParams("page_size",8))];
    }
    public function actionOne(){
          Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
          return ['data'=>Node::find()->asArray()->one()];
    }
    public function actionParseSql(){
        Yii::$app->response->format=Yii::$app->response::FORMAT_JSON;
        $id=functions::HttpParams("id");
        $node=Node::find()->where(["id"=>$id])->one();
        try {
            return ModelUtil::SimplePager($node->sql_string,functions::HttpParams("page",1),functions::HttpParams("page_size",8),SqlConfig::GetConfigPdo($node->source_config));
        }
        catch (\Throwable $exception){
            return ["code"=>403,"message"=>$exception->getMessage()];
        }
    }
    public function actionReflection(){
        Yii::$app->response->format=Response::FORMAT_JSON;
//        $id=functions::HttpParams("id");
//        $source_id=functions::HttpParams("source_config");
//        $source_pdo=SqlConfig::GetConfigPdo($source_id);
//        $desc_pdo=SqlConfig::GetConfigPdo(functions::HttpParams("desc_config"));
//        $sql=functions::HttpParams("sql");
        $node=new Node();
        $node->load(Yii::$app->request->post());
//        $node->save();
        $source_pdo=SqlConfig::GetConfigPdo($node->source_config);
        $desc_pdo=SqlConfig::GetConfigPdo($node->desc_config);
        $data=ModelUtil::SimplePager($node->sql_string,1,1,$source_pdo);
        $fileds=array_keys($data['data'][0]);
        $desc_fileds=$desc_pdo->query("desc ".$node->desc_table)->fetchAll(\PDO::FETCH_ASSOC);
        return [
            "source"=>$fileds,
            "desc"=>array_column($desc_fileds,"Field")
        ];
    }
    public function actionAddTask(){
        if(Yii::$app->request->isGet){
            $model=DataSourceTask::find()->where(["task_id"=>functions::HttpParams("task_id")])->one();
            if(empty($model)){
                $model=new DataSourceTask();
                $model->task_id=functions::HttpParams("task_id");
            }
            return $this->render("add-task",["task_id"=>functions::HttpParams("task_id"),
                "model"=>$model
            ]);
        }else{
            $model=DataSourceTask::find()->where(["task_id"=>Yii::$app->request->get("task_id")])->one();
            $new=false;
            if(empty($model)){
                $model=new DataSourceTask();
                $new=true;
            }
            $model->load(Yii::$app->request->post());
            $model->class_path=SqlNode::class;
            if($new) {
                $model->status = 1;
            }
            if($model->save()){
                return $this->redirectParent(["index"]);
            }
            print_r($model->errors);die();
        }
    }
    public function actionCreateSql(){
        Yii::$app->response->format=Response::FORMAT_JSON;
        $table=functions::HttpParams("table");
        $pdo_id=functions::HttpParams("source_config");
        $desc_pdo=SqlConfig::GetConfigPdo($pdo_id);
        return ["data"=>$this->getCreateSql($table,$desc_pdo)];
    }
    protected function getCreateSql($table,\Pdo $pdo){
        $data=$pdo->query("show create table {$table}")->fetch();
        $sql=$data['Create Table'];
        return str_replace("CREATE TABLE","CREATE TABLE IF NOT EXISTS",$sql);
    }
    public function actionSwitch(){
        $id=functions::HttpParams("id");
        $node=DataSourceTask::find()->andWhere(["task_id"=>$id])->one();
        $node->status=!$node->status;
        $node->status=intval($node->status);
        $node->save();
        echo "<script>window.parent.location.reload()</script>";die();
    }
}
