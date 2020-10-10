<?php


namespace backend\modules\tool\DataSource\Queue\Driver;


use backend\modules\tool\DataSource\Queue\Queue;
use backend\modules\tool\helpers\functions;

class RedisQueue extends Queue
{
    const redis_queue="redis_queue_task";
    protected $redis;
    public function __construct()
    {
        $this->redis=functions::getRedis();
    }

    public function pop($channel, int $size = 1)
    {
        $data=$this->redis->lPop(self::redis_queue);
        if(!empty($data)){
            return unserialize($data);
        }
        return null;
    }
    public function push($channel, $data): bool
    {
        return $this->redis->lPush(self::redis_queue,serialize($data));
    }
    public function size($channel): int
    {
        return $this->redis->lLen(self::redis_queue);
    }
    public function flush($channel)
    {
        $datas=[];
        while (empty(($data=$this->redis->lPop(self::redis_queue)))){
            $datas=unserialize($data);
        }
        return $datas;
    }
}