<?php


namespace backend\modules\tool\helpers\widgets;


use yii\base\Widget;

class ExcelButton extends Widget
{
    public function run()
    {
        return $this->render("excelbotton");
    }
}