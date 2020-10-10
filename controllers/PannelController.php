<?php


namespace backend\modules\tool\controllers;


use backend\modules\tool\helpers\AssetsAutoPublish;
use dsj\components\controllers\WebController;
use yii\helpers\Url;

class PannelController extends WebController
{
    public function actionIndex(){
        AssetsAutoPublish::publish();
        $data=[
            [
                "name"=>"填报工具V2",
                "description"=>"  简单构建后台填报，生成CRUD界面，自动注册菜单，可支持导入EXCEL,动态生成填报的API，MYSQL数据表。",
                "path"=>Url::to(["/tool/template/easy"]),
                "icon"=>"glyphicon glyphicon-stats",
                'update'=>date("Y-m-d H:i:s")
            ],
            [
                "name"=>"中间数据表生产V1",
                "description"=>"  使用SQL语句生成中间表，流式查询，可针对大量数据生成中间表，一次编写可多处运行，支持嵌入PHP程序,分布式部署可通过REDIS,MYSQL,RABBITMQ,抢占式拉取任务",
                "path"=>Url::to(["/tool/node"]),
                "icon"=>"glyphicon glyphicon-random",
                'update'=>date("Y-m-d H:i:s")
            ],
            [
                "name"=>"模型抽取",
                "description"=>"将相似的业务逻辑进行抽取，数据适配程序，或者程序适配程序，实现业务复用",
                "path"=>Url::to(["/awesome/awesome"]),
                "icon"=>"glyphicon glyphicon-folder-close",
                'update'=>date("Y-m-d H:i:s")
            ]
        ];
        return $this->render("index", [
            "list"=>$data
        ]);
    }
}