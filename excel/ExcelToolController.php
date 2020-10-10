<?php


namespace  backend\modules\tool\excel;


use dsj\components\controllers\WebController;
use dsj\components\helpers\CheckPermissionsHelper;
use dsj\components\interfaces\ITImport;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

abstract class ExcelToolController extends Controller implements ITImport
{
    protected $ExcelModel;
    protected $ExportName="导入模版文件";
    protected $blackfileds=[];//不希望导出的黑名单
    abstract function SetExcelModel();
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->ExcelModel=$this->SetExcelModel();
    }

    public function getExcelKeyForDatabaseKeyMap()
    {
        if(is_null($this->ExcelModel)){
            throw new \Exception("you should define the Excel model");
        }
        $fileds=$this->ExcelModel->attributeLabels();
        if(array_key_exists("id",$fileds)) {
            unset($fileds["id"]);
        }
        foreach ($fileds as $key=>$value){
            if(in_array($key,$this->blackfileds)){
                unset($fileds[$key]);
            }
        }
        $result=array_keys($fileds);
        $return_val=[];
        $start=ord("A");
        foreach ($result as $item){
            $return_val[chr($start)]=$item;
            $start=$start+1;
        }
        return $return_val;
    }
    public function actionExportTemplate(){;
        $Excel=new ExcelTool();
        $fileds_zh=[];
        foreach ($this->getExcelKeyForDatabaseKeyMap() as $item){
            $fileds_zh[]=$this->ExcelModel->attributeLabels()[$item];
        }
        $Excel->headerDataArray=$fileds_zh;
        $Excel->fileName=$this->ExportName;
        $Excel->array=[[]];
        $Excel->arrayToExcel();
    }
    public function HandleData($item){
        return $item;
    }
    public function dealExcelData($data){
        if($this->ExcelModel==null){
            throw new \Exception("you should define the Excel model");
        }
        if(!empty($data[1])){
            unset($data[1]);
        }
        foreach ($data as $datum){
            $model=clone $this->ExcelModel;
            foreach ($datum as $key=>$value){
                $method_name="setAttribute".$key;
                if(method_exists($this,$method_name)){
                    $datum[$key]=$this->$method_name($value);
                }
            }
            $datum=$this->HandleData($datum);
            $model->load($datum,"");
            if(!$model->save()){
                print_r($model->errors);
            }
        }
        return true;
    }
    public function actions()
    {
        return [
            'import' => [
                'class' => 'dsj\components\actions\ImportAction',
            ]
        ];
    }
    protected function redirectParent(Array $route){

        $url = Url::to($route,true);

        echo "<script>parent.location.reload()</script>";exit;
    }

    public function beforeAction($action)
    {
        if(strpos(\Yii::$app->request->url,"api")!==false) {
            if ($action->actionMethod == "actionList" || $action->actionMethod == "actionOne") {
                return true;
            }
        }
        if (parent::beforeAction($action)){
            //判断是否登录，没有登录跳转到登录页面
            if (\Yii::$app->user->isGuest){
                return $this->redirect(['/index/site/login'])->send();
            }
            //权限检查
            if (\Yii::$app->user->identity->username == 'root'){
                return true;
            }
            if (!(new CheckPermissionsHelper())->setRoute($this->route)->setUserId(\Yii::$app->user->id)->check()){
                throw new ForbiddenHttpException('对不起，你没有执行该操作的权限');
            }
        }

        return true;
    }
}