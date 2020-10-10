<?php


namespace backend\modules\tool\helpers;


class ArrayHelper
{
    public static function ArrayParseJson(array $arr){
        $result=[];
        foreach ($arr as $item){
            $result[$item]=$item;
        }
        return $result;
    }
    /***
     * 将数组转化为键值对
     * @param array $arr
     * @param $key
     * @param $value_key
     * @return array
     */
    public static function array_parse_key_value(array $arr,$key,$value_key){
        $data=[];
        if(empty($arr)){
            return [];
        }
        if(self::is_1_array($arr)){
            return [$arr[$key]=>$arr[$value_key]];
        }
        else{
            foreach ($arr as $item){
                $data[$item[$key]]=$item[$value_key];
            }
        }
        return $data;
    }
    public static function is_1_array(array $arr){
        if (count($arr) == count($arr, 1)) {
            return true;
        } else {
            return false;
        }
    }
}