<?php


namespace backend\modules\tool\DataSource\Task\command;


class LinuxCommand extends command
{
    public function getMyPid(): int
    {
        return getmypid();
    }
    public function isRun($pid, $process_name = null): bool
    {
        $process_info=shell_exec("ps -aux | grep $pid");
//$process_info="root 2623 0.0 1.4 302904 29416 ? S 10:39 0:00 php timed_task.php www-data 4469 0.0 0.0 4628 796 ? S 20:12 0:00 sh -c ps -aux | grep 2623 www-data 4471 0.0 0.0 11464 1000 ? S 20:12 0:00 grep 2623";
        preg_match_all("/php (.*?).php/",$process_info,$process_name,PREG_SET_ORDER);
        preg_match_all("/S  ([0-9|:| ]*?) [a-zA-Z]/",$process_info,$time_info,PREG_SET_ORDER);
        if(!isset($process_name[0][1])){
            return false;
        }
//        if(!isset($time_info[2][0])){
//            return false;
//        }
        return true;
    }
    public function kill($pid, $sign = "-9")
    {
        // TODO: Implement kill() method.
    }
    public function getScriptParams()
    {
       return $_SERVER["argv"];
    }
    public function run($php_script_path, $php_interperter_path = "php")
    {
        exec("$php_interperter_path $php_script_path " . ' > /dev/null &');
    }
}