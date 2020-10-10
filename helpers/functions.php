<?php


namespace backend\modules\tool\helpers;


use function GuzzleHttp\Psr7\str;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\UploadedFile;

class functions
{
    protected static $redis;
    const RandType=[
        "mix"=>"mix",
        "number"=>"number",
        "char"=>"char"
    ];
    const server_path="http://cqbn.bddeve.xbcx.com.cn/dsj_cqbn_show/backend/web/";
    /**
     * 上传文件
     * @param string $file_name input-name
     * @param array $accept 接受的文件类型
     * @param int $max 文件的最大尺寸
     * @return bool|string 成功返回路径失败返回false
     */
    const image=["jpg","jpeg","png","gif"];
    public static function upload($model,string $file_name,array $accept=[],$max=0,$autorename=true){
        $back=UploadedFile::getInstance($model, $file_name);
        if(!is_null($back)) {
            if(!empty($accept)){
                if(!in_array($back->getExtension(),$accept)){
                    throw new \Exception("FILE TYPE ACEEPT ".implode(",",$accept),' but '.$back->getExtension());
                }
            }
            if(in_array($back->getExtension(),self::image)) {
                $path="upload-file/img/".date("Ymd")."/";
//                $name = "upload-file/img/".date("Y-m-d")."/" . md5(microtime(true) . self::randnum(6)) . ".png";
            }else{
                $path="upload-file/files/".date("Ymd")."/";
            }
            $abs_path=\Yii::getAlias('@webroot').'/'.$path;
            if(!is_dir($abs_path)){
                functions::mkdir($abs_path);
            }
            if($autorename) {
                $name = $path . md5(microtime(true) . self::randnum(6)) . "." . $back->getExtension();
            }
            else{
                $name = $path  . $back->getBaseName() . "." . $back->getExtension();
            }
            if (!is_null($back) && $back->saveAs($name)) {
                $model->$file_name = $name;
                return $name;
            }
            return false;
        }
        return false;
    }
    public static function uploadFiles($name)
    {
        $names=[];
        for ($index1=0;$index1<count($_FILES[$name]['name']);$index1++){
            $item=$_FILES[$name];
            $tem=$item['tmp_name'][$index1];
            if(!is_file($tem)){
                continue;
            }
            $extension=$item['name'][$index1];
            $index=strrpos($extension,".");
            $extension=substr($extension,$index+1,strlen($extension)-$index);
            $web_path="upload/img/".date("Y-m-d")."/";
            $name1=md5($extension).".$extension";
            $path=self::GetBasePath()."/web/".$web_path;
            if(!is_dir($path)){
                self::mkdir($path);
            }
            move_uploaded_file($tem, $path.$name1);
            $names[$index1]=$web_path.$name1;
        }
        return $names;
    }

    /**
     * 获取的随机的数字
     * @param int $max 长度
     * @return string
     */
    protected static function randnum(int $max){
        $str="";
        for ($i=0;$i<$max;$i++){
            $str.=rand(1,9);
        }
        return $str;
    }
    public static function model_load(ActiveRecord $model,array $data){
        foreach ($data as $key=>$value){
            $model->$key=$value;
        }
        return $model;
    }

    /**
     * 获取redis连接
     * @return \Redis
     */
    public static function getRedis(){
        if(self::$redis==null){
            self::$redis=new \Redis();
            self::$redis->connect("127.0.0.1",'6379');
        }
        return self::$redis;
    }

    /**
     * 检查数组是否为一维数组
     * @param array $arr
     * @return bool
     */
    public static function is_1_array(array $arr){
        if (count($arr) == count($arr, 1)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 为存入数据库的路径获取给予域名的完全路径
     * @param array $data
     * @param array $fileds
     * @return array
     */
    public static function atrributeSrc(array $data,array $fileds=[]){
        $server_path = \Yii::getAlias("@web");
        $server_path=(str_replace("/api","/backend/web/",$server_path));
        $server_path = Url::to($server_path,true);
        if(self::is_1_array($data)){
            foreach ($fileds as $filed){
                if(empty($data[$filed])){
                    continue;
                }
                $data[$filed]=$server_path.$data[$filed];
            }
            return $data;
        }
        else{
            foreach ($data as &$item){
                foreach ($fileds as $filed){
                    if(empty($item[$filed])){
                        continue;
                    }
                    $item[$filed]=$server_path.$item[$filed];
                }
            }
            return $data;
        }
    }
    public static function atrributeSrcArray(array $arr){
        $server_path = \Yii::getAlias("@web");
        $server_path=(str_replace("/api","/backend/web/",$server_path));
        $server_path = Url::to($server_path,true);
        foreach ($arr as &$item){
            if(!empty($item)){
                $item=$server_path.$item;
            }
        }
        return $arr;
    }
    public static function post($url, array $params=[], $headers=[],$timeout = 5){//curl
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_TIMEOUT, 60 );
        $result = curl_exec ( $ch );
        curl_close ( $ch );
        return $result;
    }
    public static function mkdir(string $path,$priv=0777){
        if(is_dir($path)){
            return;
        }else{
            if(!is_dir(dirname($path))){
                self::mkdir(dirname($path),$priv);
            }
            mkdir($path,$priv);
        }
    }

    /**
     * 生成随机数
     * @param int $num
     * @param string $type
     * @return string
     */
    public static function rand(int $num,$type="mix"){
        if($type=='mix') {
            $code_list = "abcdefghijklmnopqrstuvwxyz0123456789";
        }
        elseif($type=="number"){
            $code_list = "123456789";
        }
        else{
            if($type=="char"){
                $code_list = "abcdefghijklmnopqrstuvwxyz";
            }
        }
        $code="";
        for ($i=0;$i<$num;$i++){
            $code.=substr($code_list,mt_rand(0,strlen($code_list)-1),1);
        }
        return $code;
    }
    //debug 兼容php 5.6
    public static function GetOrDefault($value,$default){
        if(empty($value)){
            return $default;
        }
        return $value;
    }
    public static function GetPublicPathUrl(){
        return str_replace("index.php","",\Yii::$app->getHomeUrl());
    }
    public static function GetIndexPath(){
        return explode("?",\Yii::$app->request->getUrl())[0];
    }
    public static function FileWolk($dir,$except=[]){
        $result=[];
        $dir=str_replace("//","/",$dir);
        if(in_array($dir,$except))
        {
            return [];
        }
        if(is_file($dir)){
            return [$dir];
        }
        if(@$handle = opendir($dir)) { //注意这里要加一个@，不然会有warning错误提示：）
            while(($file = readdir($handle)) !== false) {
                if($file != ".." && $file != ".") {
                    if(is_dir($dir."/".$file)) {
                        $files[] =self::FileWolk($dir."/".$file,$except);
                    } else {
                        $files[] = $file;
                        $result[]=str_replace("//","/",$dir."/".$file);
                    }
                }
            }
            closedir($handle);
            return $result;
        }
    }
    public static function GetBasePath(){
        return \Yii::$app->getBasePath();
    }
    public static function getWebFilePath($path){
        $path=str_replace(dirname(functions::GetBasePath())."/backend/","",$path);
        return str_replace("/web/web","/web",self::atrributeSrc(['path'=>$path],['path']))['path'];
    }
    public static function PublicPathWalk($path){
        $img_path=dirname(functions::GetBasePath())."/backend/".$path;
        $paths=functions::FileWolk($img_path);
        foreach ($paths as $path) {
            $result[]=functions::getWebFilePath($path);
        }
        return $result;
    }
    public static function HttpParams($key,$default=null)
    {
        if(isset($_GET["r"])){
            unset($_GET["r"]);
        }
        if (empty($_GET)&&empty($_POST)) {
//            print_r(json_decode(file_get_contents('php://input'), true));die();
//            \Yii::$app->request->setBodyParams($_POST,json_decode(file_get_contents('php://input'), true)??[]);
            if(\Yii::$app->request->isPost) {
                $_POST= json_decode(file_get_contents('php://input'), true);
//                \Yii::$app->request->setBodyParams($_POST);
            }
            else{
                $_GET= json_decode(file_get_contents('php://input'), true);
//                \Yii::$app->request->setBodyParams($_GET);
            }

        }
//        print_r(json_decode(file_get_contents('php://input'), true));die();
//        json_decode(file_get_contents('php://input'), true);
//        print_r($_POST);die();
        if(\Yii::$app->request->isGet){
            $result=\Yii::$app->request->get($key);
        }else{
            $result=\Yii::$app->request->post($key);
        }
        if(empty($result)&&!is_numeric($result)){
            if(is_array($_POST)&&is_array($_GET)) {
                $data = array_merge($_POST??[], $_GET??[]);
                if (!empty($data[$key])) {
                    return $data[$key];
                }
            }
            return $default;
        }
        return $result;
    }
    public static function HttpParamsAll(){
        if(\Yii::$app->request->isPost){
            return \Yii::$app->request->post();
        }
        return \Yii::$app->request->get();
    }
}