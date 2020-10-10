<?php


namespace backend\modules\tool\DataSource\Config;


use backend\modules\tool\DataSource\DataAdapter;
use backend\modules\tool\DataSource\Queue\Driver\MysqlQueue;
use backend\modules\tool\DataSource\Queue\Driver\RedisQueue;
use backend\modules\tool\DataSource\Task\command\command;
use backend\modules\tool\DataSource\Task\command\CommandFaced;
use backend\modules\tool\Job\SqlNode;
use SebastianBergmann\CodeCoverage\Report\PHP;

class Config
{
    public static $driver=[
        "queue"=>MysqlQueue::class
    ];
    public static function Register(){
        return [
            DataAdapter::class=>function($class){
                (new $class())->run();
            },
            SqlNode::class=>function($class){
                $driver=CommandFaced::getDirver();
                (new SqlNode($driver->getScriptParams()[2]))->run();
            }
        ];//此处配置任务的运行方式
    }
    public static function run($class_path):\Closure{
        $flag=true;
        if(!class_exists($class_path)){
            throw new \Exception("class {$class_path} not find");
        }
        $obj=new \ReflectionClass($class_path);
        $class_name=$obj->name;
        while ($flag&&$obj){
            foreach (self::Register() as $class=>$closure){
                if($class==$class_name){
                    return $closure;
                }
            }
            $class_name=$obj->getParentClass()->name;
            $obj=$obj->getParentClass();
        }
    }
}