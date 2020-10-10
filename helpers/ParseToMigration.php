<?php


namespace backend\modules\tool\helpers;


use api\modules\mediation\controllers\LetterController;

class ParseToMigration
{
    public $config=[
        [
            "name"=>"key",
            "type"=>"int",
            "length"=>10,
            "demical"=>0,
            "empty"=>true,
            "comment"=>"字段中文名称"
        ]
    ];
    public function __construct($config)
    {
        $this->config=$config;
    }
    public function parse(){
        foreach ($this->config as $item){

        }
    }
}