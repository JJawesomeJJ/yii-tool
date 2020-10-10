<?php


namespace backend\modules\tool\DataSource\Queue;


abstract class Queue
{
    /**
     * 推入条数据进入消息队列
     * @param string $channel
     * @param $data
     * @return mixed
     */
    abstract function push($channel,$data):bool;

    /**
     * 消息队列的长度
     * @param string $channel 通道的名称
     * @return int
     */
    abstract function size($channel):int;

    /**
     * 弹出消息的队列的数据
     * @param string $channel 通道的名称
     * @param int $size 取出的数量
     * @return mixed
     */
    abstract function pop($channel,int $size=1);

    /**
     * 取出消息队列全部的数据
     * @param string $channel 通道的名称
     * @return array
     */
    abstract function flush($channel);
}