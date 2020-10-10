<?php

/**
 * 接收上传文件的组件
 */
namespace backend\modules\tool\controllers;


use backend\modules\tool\helpers\FileHelper;
use backend\modules\tool\models\Image;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class UploadController extends Controller
{
    public function actionImg(){
        \Yii::$app->response->format=Response::FORMAT_JSON;
        $path=self::upload(new Image(),"img",self::image);
        if($path){
            return ['code'=>200,'url'=>$path];
        }
        return ['code'=>403,'message'=>"上传失败"];
    }
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
                FileHelper::mkdir($abs_path);
            }
            if($autorename) {
                $name = $path . md5(microtime(true) . rand(6,10000)) . "." . $back->getExtension();
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
}