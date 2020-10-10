<?php


namespace backend\modules\tool\models;


use yii\db\ActiveRecord;
use yii\db\Query;

class AutoQuery
{
    public static function query($query,$rules,$model=null){
        /**
         * @var $query ActiveRecord;
         */
        if(is_string($query)) {
            $query = new $query();
            $query = $query::find();
        }
        if(empty($rules)){
            return $query;
        }
        if(empty($model)) {
            $model = new AutoSearchModle();
            $model->load(\Yii::$app->request->get());
        }
        foreach ($model->toArray() as $key=>$item){
            if(!empty($item)){
                if(empty($rules[$key])){
                    continue;
                }
                switch ($rules[$key]){
                    case "like":
                        $query->andFilterWhere(["like",$key,"$item"]);
                        break;
                    case "in":
                        if(is_array($item)){
                            $query->andFilterWhere([">=",$key,$item[0]??'']);
                           $query->andFilterWhere(["<=",$key,$item[1]??'']);
                        }
                        break;
                    case "=":
                       $query->andFilterWhere([$key=>$item]);
                       break;
                    default:
                        break;
                }
            }
        }
        return $query;
    }
}