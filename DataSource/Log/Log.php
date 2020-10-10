<?php


namespace backend\modules\tool\DataSource\Log;
use backend\modules\tool\helpers\FileHelper;

define("log_path",dirname(__DIR__)."/Filesystem/");

class Log
{
    protected static $object;
    protected $wirte_path;
    public static function SingleTon($file_path){
        if(empty(self::$object)){
            self::$object=new self($file_path);
        }
        return self::$object;
    }
    protected function __construct($file_path)
    {
        FileHelper::mkdir(log_path.$file_path);
        $this->wirte_path=log_path.$file_path;
    }

    public function write($message){
        file_put_contents($this->wirte_path.date("Y-m-d").".log",$message,FILE_APPEND|LOCK_EX);
    }
    public function getLog($task){

    }
}