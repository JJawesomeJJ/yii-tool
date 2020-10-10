<?php


namespace backend\modules\tool\DataSource\EventLoop;


use backend\modules\tool\DataSource\Config\Config;
use backend\modules\tool\DataSource\Queue\Queue;
use backend\modules\tool\DataSource\Task\command\command;
use backend\modules\tool\DataSource\Task\command\LinuxCommand;
use backend\modules\tool\DataSource\Task\command\WindowsCommad;
use backend\modules\tool\models\DataSourceTask;
use console\modules\datasource\models\DataSourceProvider;
use yii\db\Expression;
use yii\db\Query;

class Main
{
    protected $channel_name="wait_run_task";
    /**
     * @var Queue
     */
    protected $queue;
    /**
     * @var command
     */
    protected $commnd;
    public function __construct()
    {
        $queue_name=Config::$driver["queue"];
        /**
         * @var Queue
         */
        $this->queue=new $queue_name();
        if(command::getRunVersion()=="Windows"){
            $this->commnd=new WindowsCommad();
        }
        if(command::getRunVersion()=="Linux"){
            $this->commnd=new LinuxCommand();
        }
    }
    public function Run(){
        while(true){
//            echo microtime(true).PHP_EOL;
            $result=DataSourceTask::find()
//                ->where(new Expression("pid='' or pid is null"))
                ->andWhere(["<=","run_time",date("Y-m-d H:i:s")])
                ->andWhere(['status'=>1])//设置可执行的数据
                ->all();
            foreach ($result as $item){
                $item->nextTick();
                $this->queue->push($this->channel_name,$item->id);
            }
            while (!empty($data=$this->queue->pop($this->channel_name))){
//                print_r($data);die();
//                echo "run";
                $this->commnd->run("yii datasource/index {$data}");
            }
            usleep(100);
        }
    }
}