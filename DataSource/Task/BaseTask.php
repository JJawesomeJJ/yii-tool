<?php


namespace backend\modules\tool\DataSource\Task;


use backend\modules\tool\DataSource\Task\command\command;
use backend\modules\tool\DataSource\Task\command\LinuxCommand;
use backend\modules\tool\DataSource\Task\command\WindowsCommad;
use yii\console\Controller;
define("TASK_RUN_AT",microtime(true));
abstract class BaseTask extends Controller
{
    /**
     * @var command
     */
    protected $command;
    protected $task_id;
    abstract function recordMyPid();
    public function __construct($id, $module, $config = [])
    {
        if(command::getRunVersion()=="Linux"){
            $this->command=new LinuxCommand();
        }
        if(command::getRunVersion()=="Windows"){
            $this->command=new WindowsCommad();
        }
        $this->task_id=$this->command->getScriptParams()[2];
        $this->recordMyPid();//记录当前程序的PID
        $this->beforeRun();
        parent::__construct($id, $module, $config);
    }
    public abstract function beforeRun();
    public abstract function afterRun();
    protected function getRunTime(){
        return microtime(true)-TASK_RUN_AT;
    }
}