<?php


namespace backend\modules\tool\models;


use backend\modules\tool\helpers\ArrayHelper;
use backend\modules\tool\helpers\functions;
use backend\modules\tool\helpers\ModelUtil;
use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{
    protected static $create_sql="";
    public function beforeSave($insert)
    {
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
    public function getDirtyAttributes($names = null)
    {
        $result=parent::getDirtyAttributes($names); // TODO: Change the autogenerated stub
        foreach ($result as $key=>$value){
            if(is_array($value)||is_object($value)){
                $result[$key]=json_encode($value);
            }
        }
        return $result;
    }
    public static function GetTypeSelect($filed,$query=null){
        if(empty($query)){
            $query=self::find();
        }
        $result=$query
            ->select("$filed")
            ->distinct()
            ->asArray()->all();
        $result=array_column($result,$filed);
        array_unshift($result,"");
        return ArrayHelper::ArrayParseJson($result);
    }
    public static function List(){
        $query=get_called_class()::find();
        return ModelUtil::pager(get_called_class()::find(),functions::HttpParams("page",1),functions::HttpParams("page_size",8));
    }
    public static function CreateTable(){
//        \Yii::$app->db->createCommand(get_called_class()::$create_sql)->execute();
        \Yii::$app->cache->getOrSet(md5(get_called_class()::$create_sql),function (){
            try {
                \Yii::$app->db->createCommand(get_called_class()::$create_sql)->execute();
            }
            catch (\Exception $exception){
//                print_r($exception);
            }
            return true;
        });
    }
}