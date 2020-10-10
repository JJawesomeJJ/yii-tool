<?php


namespace backend\modules\tool\Assets;


use yii\web\AssetBundle;

class LayuiBundle extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "js/layui/css/layui.css"
    ];
    public $js = [
        "js/common/jquery-2.2.3.min.js",
        'js/layui/loadForm.js',
        "js/layui/layui.all.js",
    ];
}