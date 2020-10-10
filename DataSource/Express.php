<?php


namespace backend\modules\tool\DataSource;


class Express
{
    public $sql;
    public function __construct($sql)
    {
        $this->sql=$sql;
    }
}