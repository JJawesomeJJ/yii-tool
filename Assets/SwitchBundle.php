<?php


namespace backend\modules\tool\Assets;


use yii\web\AssetBundle;

class SwitchBundle extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap-switch.css'
    ];
    public $js = [
        "js/bootstrap-switch.min.js",
    ];
}