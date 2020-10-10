<?php

namespace backend\modules\tool\DataSource;
define("start_at",microtime(true));
/**
 * 任务调度的方式自我调度
 * Interface DataAdapter
 * @package backend\modules\tool\models
 */
abstract class DataAdapter
{

    /**
     * 时间戳
     */
    const tick=[
        "minute"=>60,
        "hour"=>60*60,
        "day"=>60*60*24,
        "week"=>60*60*24*7,
        "month"=>"month",
        "year"=>"year"
    ];

    /**
     * 此处编写具体的数据源 可以数据库 可以是接口 可以是来至其他的数据分析
     * 调用HandleData Next
     * 注意查询的数据量特别大的话建议是游标查询不要一次放入内存，可能导致内存溢出的情况，而且可能将内存打满 严重的导致服务器挂掉
     * @return mixed
     */
    abstract protected function DataSource();

    /**
     * 此处编写具体的数据处理逻辑
     * @param $data
     * @return mixed
     */
    abstract protected function HandleData($data);

    /**
     * 此处编写具体的数据存储的逻辑
     * @return mixed
     */
    abstract protected function StoreData($data);
    public static function GetPdo($driver,$host,$user,$password,$port,$database){
        $dns="$driver:host=$host;dbname=$database;port=$port;charset=UTF8";
        try {
            $pdo = new \PDO($dns, $user, $password);
        }
        catch (\Throwable $throwable){
            echo $throwable;die();
        }
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
        return $pdo;
    }
    public static function GetPdoDsn($dns,$user,$password){
        $pdo=new \PDO($dns,$user,$password);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
        return $pdo;
    }
    public function run(){
        $this->DataSource();
    }
    function is_1_array(array $arr){
        if (count($arr) == count($arr, 1)) {
            return true;
        } else {
            return false;
        }
    }
    public function RunTime(){
        return microtime(true)-start_at;
    }
}