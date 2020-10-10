<?php


namespace backend\modules\tool\DataSource\Task\command;


class WindowsCommad extends command
{
    public function getMyPid(): int
    {
        return getmypid();
    }
    public function run($php_script_path, $php_interperter_path = "php")
    {
        pclose(popen("start $php_interperter_path $php_script_path","r"));
    }
    public function isRun($pid, $process_name = null): bool
    {
        // TODO: Implement isRun() method.
    }
    public function getScriptParams()
    {
        return $_SERVER["argv"];
    }
    public function kill($pid, $sign = "-9")
    {
        // TODO: Implement kill() method.
    }
}