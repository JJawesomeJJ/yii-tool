<?php


namespace backend\modules\tool\helpers;


class Common
{
    public static function GetWebPathAbs(){
        return \Yii::$app->getBasePath()."/web/";
    }
    public static function GetWebPath(){
        return dirname(\Yii::$app->getHomeUrl())."/";
    }
    public static function GetModulesPath($namesapce){
        return dirname(\Yii::$app->basePath).'/'.$namesapce."/";
    }
    public static function HttpParams($key){
        if(\Yii::$app->request->isGet){
            return \Yii::$app->request->get($key);
        }else{
            return \Yii::$app->request->post($key);
        }
    }
}