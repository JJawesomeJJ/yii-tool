<?php


namespace backend\modules\tool\helpers;


use yii\base\Model;
use yii\data\Pagination;
use yii\db\ActiveRecord;
use yii\db\Query;

class ModelUtil
{
    public static function FindOneOrFail($model_name){
        $model=$model_name::find()->limit(1)->one();
        if(!empty($model)){
            return $model;
        }else{
            $object=new $model_name();
            //设置默认演示数据
            if(property_exists($object,"defalut_data")){
                return $object->defalut_data;
            }else{
                throw new \Exception("model data not find");
            }
        }
    }
    public static function pager(Query $query,$current_page,$page_num){
        $count = $query->count();

// 使用总数来创建一个分页对象
        $page_num=$page_num>0?$page_num:1;
        $current_page=$current_page-1;
        $total_page=ceil($count/$page_num);
        $data = $query->offset($current_page*$page_num)
            ->limit($page_num)
            ->all();
        return [
            "total"=>$count,
            "total_page"=>$total_page,
            "current_page"=>$current_page+1,
            'page_size'=>$page_num,
            "data"=>$data
        ];
    }
    public static function SimplePager($sql,$current_page,$page_num,\PDO $pdo){
        $count = $pdo->query("select count(1) as num from( ".$sql.") as tmp1")->fetch()["num"];
//        echo "select count(1) as num from( ".$sql.") as tmp1";die();

// 使用总数来创建一个分页对象
        $page_num=$page_num>0?$page_num:1;
        $current_page=$current_page-1;
        $total_page=ceil($count/$page_num);
        $offsite=$current_page*$page_num;
//        echo "select tmp1.* from( ".$sql.") as tmp1 limit $offsite,$page_num";die();
        $data = $pdo->query("select tmp1.* from( ".$sql.") as tmp1 limit $offsite,$page_num")->fetchAll(\PDO::FETCH_ASSOC);
        return [
            "total"=>$count,
            "total_page"=>$total_page,
            "current_page"=>$current_page+1,
            'page_size'=>$page_num,
            "data"=>$data
        ];
    }
    public static function FindOrCreate($ActiveRecordClass){
        $object=$ActiveRecordClass::find()->limit(1)->one();
        if(empty($object)){
            $object=new $ActiveRecordClass();
        }
        return $object;
    }

}