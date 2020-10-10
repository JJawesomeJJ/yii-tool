<?php


namespace backend\modules\tool\helpers\widgets;


use yii\base\Widget;

class SwitcButton extends Widget
{
    public $filed;
    public $model;
    public function run()
    {
        return $this->render("switchbutton",[
            "filed"=>$this->filed,
            "model"=>$this->model
        ]);
    }
}