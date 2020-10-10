<?php


namespace backend\modules\tool\helpers;


class DbHelper
{
    public static function GetPdo($driver,$host,$user,$password,$port,$database){
        $dns="$driver:host=$host;dbname=$database;port=$port;charset=UTF8";
        try {
            $pdo = new \PDO($dns, $user, $password);
        }
        catch (\Throwable $throwable){
            echo $throwable;die();
        }
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
//        $pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
        return $pdo;
    }
}