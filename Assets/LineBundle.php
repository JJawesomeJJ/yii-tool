<?php


namespace backend\modules\tool\Assets;


use yii\web\AssetBundle;

class LineBundle extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        'uploadimg/img_upload.css',
//        'css/base.css'
    ];
    public $js = [
        "js/jsplumb.min.js",
    ];
}