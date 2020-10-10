<?php


namespace backend\modules\tool\Assets;


use yii\web\AssetBundle;

class BaseBundle extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        "js/layer-v3.1.1/layer/layer.js",
        "js/common/ObjectHelper.js",
        'js/clipboard.min.js'
    ];
}