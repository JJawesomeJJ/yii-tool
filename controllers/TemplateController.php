<?php

namespace backend\modules\tool\controllers;

use backend\modules\tool\helpers\AssetsAutoPublish;
use backend\modules\tool\helpers\ClassAnalyse;
use backend\modules\tool\helpers\Common;
use backend\modules\tool\helpers\Componmet;
use backend\modules\tool\helpers\FileHelper;
use backend\modules\tool\helpers\functions;
use backend\modules\tool\helpers\Migration;
use backend\modules\tool\helpers\StringHelper;
use backend\modules\tool\helpers\TemplateHelper;
use backend\modules\tool\models\FillFormModel;
use dsj\menu\models\Menu;
use PharIo\Manifest\Url;
use SebastianBergmann\CodeCoverage\Report\PHP;
use Yii;
use backend\modules\tool\models\FillForTemplate;
use yii\data\ActiveDataProvider;
use dsj\components\controllers\WebController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use function Matrix\identity;

/**
 * TemplateController implements the CRUD actions for FillForTemplate model.
 */
class TemplateController extends WebController
{
    /**
     * @inheritdoc
     */
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

    /**
     * Lists all FillForTemplate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $com=new ClassAnalyse(Migration::class);
//        print_r($com->GetMethods(array_keys(Migration::$accept_fun)));
//        AssetsAutoPublish::publish();
//        die();
        AssetsAutoPublish::publish();
        $dataProvider = new ActiveDataProvider([
            'query' => FillForTemplate::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FillForTemplate model.
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
     * Creates a new FillForTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FillForTemplate();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->redirectParent(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing FillForTemplate model.
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
     * Deletes an existing FillForTemplate model.
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
     * Finds the FillForTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FillForTemplate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FillForTemplate::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionTemplate($id){
        if(Yii::$app->request->isGet){
            return $this->render("createtemplate");
        }
        $model=$this->findModel($id);
        $model->CompileModel(
            Yii::$app->request->post("namespace"),
            Yii::$app->request->post("modelname"),
            Yii::$app->request->post("controllername"));
        $this->redirect(Yii::$app->getHomeUrl());
    }
    public function ParseSql($type,$params){
        $migrate=Migration::SingleTon();
        foreach ($params as &$param){
            if($param=="true"){
                $param=true;
            }
            if($param=="false"){
                $param=false;
            }
        }
        call_user_func_array([$migrate,$type],$params);
    }
    public function actionEasy(){
        if(Yii::$app->request->isPost){
            $view_template_string="";
//            print_r(Yii::$app->request->post("fileds"));
//            die();
           foreach (Yii::$app->request->post("fileds") as $item){
               if(empty($item["widget_params"])){
                   $item["widget_params"]=[];
               }
               if($this->isunique()){
                   $this->ParseSql($item["fun"],$item["key_params"]);
               }
                $view_template_string.=$this->ParseWidget($item["type"],$item["key_params"]['create_column_name'],$item["widget_params"]).PHP_EOL;
           }
           try {
               $this->CreateSql();//生成数据库
               $this->CreateModules();//生成注册模块
               $this->CompileView();//生成视图
               $this->CompileModelString();//生成模型
               $this->CreateController();//生成控制器
               $menu=new Menu();
               $module_name=explode("\\",functions::HttpParams("namespace"));
               if(Menu::find()->where(["route"=>'/'.$module_name[count($module_name)-1]."/".StringHelper::toUnderScore(ucwords(functions::HttpParams("modelname")))])->count()==0){
                   $menu->load([
                       "pid"=>functions::HttpParams("menu"),
                       "title"=>functions::HttpParams("title"),
                       "route"=>'/'.$module_name[count($module_name)-1]."/".StringHelper::toUnderScore(ucwords(functions::HttpParams("modelname"))),
                       "sort"=>1,
                       "icon"=>"glyphicon glyphicon-stats"
                   ],"");
                   $menu->save();
               }//生成菜单
//               Yii::$app->db->createCommand("");
           }
           catch (\Throwable $exception){
             echo $exception;
           }
        }
        return $this->render("easybuild",[
            "data"=>json_encode($this->Menu())
        ]);
    }
    public function CreateSql(){
        $create_sql=null;
        if($this->isunique()==false){
            Migration::SingleTon()->integer("id","id",11,false,true,true,true);
            foreach (Common::HttpParams("fileds") as $filed){
                $this->ParseSql($filed["fun"],array_values($filed["key_params"]));
            }
            Migration::SingleTon()->tableName($this->GetTableName());
            Migration::SingleTon()->create();
            $create_sql=Migration::SingleTon()->GetRunSql();
        }
        return $create_sql;
    }
    public function isunique(){
        if(empty(Common::HttpParams("unique"))){
            return false;
        }
        if(Common::HttpParams("unique")=="true"){
            return true;
        }
        return false;
    }
    public function ParseWidget($type,$key,$params){
        $params["key"]=$key;
        $componmet=Componmet::list()[$type];
        if(!empty($componmet["params"])){
            $params=call_user_func($componmet["params"],$params);
        }
        $template=$componmet["template"];
        $template=TemplateHelper::parse($template,$params);
        return $template;
    }
    public function CompileView(){
        $search=$this->CreateSearch();
        $search_rules=$search['rules']??[];
        $search_params=$search['result'];
        $search_string='';
        $namespace=Common::HttpParams("namespace");
        if($this->isunique()==false) {
            $path = dirname(__DIR__) . "/template/views1";
        }else{
            $path = dirname(__DIR__) . "/template/unique/views1";
        }
        $modelname=Common::HttpParams("modelname");
        $fileds="";
        $controllername=Common::HttpParams("modelname")."Controller";
        $Componmet=Componmet::list();
         $view_template_string="";
           foreach (Yii::$app->request->post("fileds") as $item){
               if(empty($item["widget_params"])){
                   $item["widget_params"]=[];
               }
               if($this->isunique()){
                   $this->ParseSql($item["fun"],$item["key_params"]);
               }
               if(!isset($Componmet[$item["type"]]["view"])){
                   $fileds.="'".$item["key_params"]["create_column_name"]."',".PHP_EOL;
               }elseif ($Componmet[$item["type"]]["view"]!=false) {
                   $fileds.="'".$item["key_params"]["create_column_name"].Componmet::list()[$item["type"]]["view"]."',".PHP_EOL;
               }
               if(!empty($search_params[$item["key_params"]['create_column_name']])){
                   $params=$search_params[$item["key_params"]['create_column_name']];
                   $search_string.=$this->ParseSearchWidget($params['search']['filter'],$params['search']['widget'],$item["key_params"]['create_column_name'],$params['widget_params']).PHP_EOL;
               }
               $view_template_string.=$this->ParseWidget($item["type"],$item["key_params"]['create_column_name'],$item["widget_params"]).PHP_EOL;
           }
           $fileds=TemplateHelper::RemoveLast($fileds,2);
           $fileds=TemplateHelper::AddPHPEOF($fileds);
        $controllername=str_replace("Controller","",$controllername);
        if(!empty($search_string)) {
            $search_string = TemplateHelper::Compile(dirname(__DIR__) . "/template/search.php.template", ["search_widget" => $search_string], false, false, false);
        }else{
            $search_string='<?= \backend\modules\tool\helpers\widgets\ExcelButton::widget([])?>
<?php $form = ActiveForm::begin([\'method\'=>\'get\']); ?>
<?php ActiveForm::end(); ?>';

        }
        TemplateHelper::Compile($path,[
            "namespace"=>$namespace,
            "modelname"=>$modelname,
            "fileds_model"=>TemplateHelper::AddPHPEOF($view_template_string),
            "title"=>Common::HttpParams("title"),
            'fileds_list_string'=>$fileds,
            'search_widget'=>$search_string
        ],Common::GetModulesPath($namespace)."/views/".StringHelper::toUnderScore(ucwords($controllername))."/");
    }
    public function ParseSearchWidget($filter,$type,$key,$params){
        $params['is_filter']=true;
        switch ($filter){
            case "in":
                $key1=$key."[0]";
                $result=$this->ParseWidget($type,$key1,$params).PHP_EOL;
                $key=$key."[1]";
                $result.=$this->ParseWidget($type,$key,$params);
                return "<div style=\"display: flex;width: 600px;flex-wrap: nowrap\">".PHP_EOL.$result.'</div>'.PHP_EOL;
                break;
            default:
                return $this->ParseWidget($type,$key,$params);
                break;
        }
    }
    public function CompileRulesFiled(array $fileds){
        $rules_fileds_rules=[];
        $attribute=[];
        $propertys=[];
        foreach ($fileds as $filed){
            if(!empty($filed["key_params"]["required"])){
                if($filed["key_params"]["required"]=="true"){
                    $rules_fileds_rules["required"][]=$filed["key_params"]["create_column_name"];
                }
            }
            $attribute[$filed['key_params']["create_column_name"]]=$filed['key_params']["commemt"];//生成yii的attribute;
            $propertys[]=["type"=>$filed['fun']=="text"?"string":$filed["fun"],'name'=>$filed['key_params']['create_column_name']];
            switch ($filed["fun"]){
                case "text":
                    if($filed["type"]=="uploadimgs"){
                        $rules_fileds_rules["safe"][]=$filed["key_params"]["create_column_name"];
                    }else {
                        $rules_fileds_rules["string"][] = $filed["key_params"]["create_column_name"];
                    }
                    break;
                case "string":
                    $rules_fileds_rules["string___".json_encode(["max"=>$filed["key_params"]["length"]])][]=$filed["key_params"]["create_column_name"];
                    break;
                default:
                    $rules_fileds_rules[$filed["fun"]][]=$filed["key_params"]["create_column_name"];
                    break;
            }
        }
        $real_rules=[];
        foreach ($rules_fileds_rules as $key=>$value){
            $params=explode("___",$key);
            $real_rules[]=["rule"=>$params[0],"params"=>!empty($params[1])?json_decode($params[1],true):'',"fileds"=>$value];
        }
        return [
            'rule'=>$real_rules,
            'attribute'=>$attribute,
            'propertys'=>$propertys
        ];
    }
    protected function CompileModelString()
    {
        $tempalte_rules = $this->CompileRulesFiled(Common::HttpParams("fileds"));
        //start compile template model propertys
        $property_string = "/**";
        foreach ($tempalte_rules['propertys'] as $property) {
            $property_string .= "*@property " . $property['type'] . " $" . $property['name'] . PHP_EOL;
        }
        $property_string .= " */" . PHP_EOL;
        //end compile
        //start compile template rules
        $rules_array = [];
        foreach ($tempalte_rules['rule'] as $item) {
            if (empty($item['fileds'])) {
                continue;
            }
            $rules_item = "[";
            $rules_item .= '[' . TemplateHelper::implode($item['fileds']) . '],';
            $rules_item .= "'" . $item['rule'] . "'";
            if (!empty($params_string = $this->CompileRulesParams($item['params']))) {
                $rules_item .= "," . $params_string;
            }
            $rules_item .= "]";
            $rules_array[] = $rules_item;
        }
        $rules_string = implode("," . PHP_EOL, $rules_array);
        //end compile template rules
        //start compile template attribute
        $attribute_string = "";
        $fileds_string = "";
        foreach ($tempalte_rules['attribute'] as $key => $value) {
            $attribute_string .= "'" . $key . "'=>'" . $value . "'," . PHP_EOL;
            $fileds_string .= "public $" . $key . ";" . PHP_EOL;
        }
        //end compile template attribute
        $params= [
            'rule' => TemplateHelper::AddPHPEOF($rules_string),
            'propertys' => TemplateHelper::AddPHPEOF($property_string, 0),
            'attribute' => TemplateHelper::AddPHPEOF($attribute_string),
            'fileds' => TemplateHelper::AddPHPEOF($fileds_string, 4),
            "namespace"=>Common::HttpParams("namespace"),
            "modelname"=>Common::HttpParams("modelname"),
            'tablename'=>$this->GetTableName(),
            "class"=>Common::HttpParams("modelname"),
            "template_key"=>Common::HttpParams("namespace").'\\'.Common::HttpParams("modelname"),
            "is_unique"=>$this->isunique()?"true":"false",
            "create_sql"=>Migration::SingleTon()->GetRunSql()
        ];
        if($this->isunique()){
            $Modules_template = dirname(__DIR__) . "/template/models/unique/model.php.template";
        }else{
            $Modules_template = dirname(__DIR__) . "/template/models/normal/model.php.template";
        }
        $Modules=Common::HttpParams("namespace");
        $path=Common::GetModulesPath($Modules)."/models/";
        TemplateHelper::Compile($Modules_template,$params,$path,Common::HttpParams("modelname").".php");
    }
    protected function GetTableName(){
        return strtolower("t_".Common::HttpParams("modelname")."_back_fill");
    }

    protected function CompileRulesParams($params){
        if(empty($params)){
            return null;
        }
        $params_string="";
        foreach ($params as $key=>$value){
            $params_string.="'".$key."'=>'".$value."',";
        }
        return substr($params_string,0,strlen($params_string)-1);
    }
    protected function CreateController(){
        if($this->isunique()){
            $Modules_template = dirname(__DIR__) . "/template/unique/controller.php.template";
        }else{
            $Modules_template = dirname(__DIR__) . "/template/controller.php.template";
        }
        $Modules=Common::HttpParams("namespace");
        $path=Common::GetModulesPath($Modules)."/controllers/";
        if(!is_dir(dirname($path))){
            FileHelper::mkdir(dirname($path));
        }
        TemplateHelper::Compile($Modules_template,[
            "class"=>Common::HttpParams("modelname")."Controller",
            "modelname"=>Common::HttpParams("modelname"),
            "namespace"=>Common::HttpParams("namespace"),
            'rules'=>$this->CreateSearch()['rules']??'[]'
        ],$path,ucwords(Common::HttpParams("modelname")."Controller").".php");
    }
    protected function CreateModules()
    {
        $Modules = Common::HttpParams("namespace");
        $path = Common::GetModulesPath($Modules);
        $path=
        FileHelper::mkdir(dirname($path));
        if (!is_file($Modules)) {
            $Modules_template = dirname(__DIR__) . "/template/Module.php.template";
            TemplateHelper::Compile($Modules_template,
                [
                    "namespace" => $Modules
                ]
                , $path);
        }
        $this->RegisterModules($Modules);
    }
    protected function RegisterModules($modulename){
        $modules_path=Yii::$app->basePath."\config\modules.php";
        if(is_file($modules_path)) {
            try {
                $modules_params = require $modules_path;
            }
            catch (\Throwable $e){
                $modules_params=[];
            }
        }else{
            $modules_params=[];
        }
        $modules_params=array_values($modules_params);
        $params=[];
        foreach ($modules_params as $param){

            $params[]=str_replace("\Module","",array_values($param)[0]);
        }
        $modules_params=$params;
        $modules_params[]=$modulename;
        $modules_params=array_unique($modules_params);
        $template="";
        foreach ($modules_params as $item){
            $module_name=explode("\\",$item);
            $template.="'".$module_name[count($module_name)-1]."'=>[".
                "'class'=>".$modulename."\Module::class],".PHP_EOL;
        }
        $template=TemplateHelper::RemoveLast($template,3);
        $template="<?php
return [".
            $template
            ."];";
        $modules_path=str_replace("//","/",$modules_path);
        file_put_contents($modules_path,$template);
    }
    public function CreateSearch(){
        $data=Yii::$app->request->post("fileds");
        $result=[];
        $SearchModel=[];
        foreach ($data as $datum){
            if(empty($datum['search'])){
                continue;
            }
            foreach ($datum['search'] as $search=>$val){
                if(empty($val)){
                    continue 2;
                }
            }
            $result[$datum['key_params']['create_column_name']]=[
                "search"=>$datum['search'],
                "widget_params"=>$datum['widget_params']??[]
            ];
//            $SearchModel[$datum["search"]['filter']][]=$datum['key_params']['create_column_name'];
            $SearchModel[$datum['key_params']['create_column_name']]=$datum["search"]['filter'];
        }
        return [
            "rules"=>TemplateHelper::ParseArray($SearchModel),
            "result"=>$result
        ];
    }
    public function Menu(){
        $data=Menu::find()->select("id,pid,title")->asArray()->all();
        return $this->children($data);
    }
    protected function children($data,$pid=0){
        $result=[];
        foreach ($data as $datum){
            if($datum["pid"]==$pid){
                $append=['name'=>$datum['title'],"open"=>false,"state"=>["opened"=>false,"selected"=>false],"path"=>$datum['id']];
                $append['children']=$this->children($data,$datum['id']);
                $result[]=$append;
            }
        }
        return $result;
    }
}
