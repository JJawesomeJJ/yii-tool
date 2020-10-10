<?php


namespace backend\Assets;


use yii\web\AssetBundle;

class ImageUpload extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'uploadimg/img_upload.css',
        'css/base.css'
    ];
    public $js = [
        "uploadimg/img_upload.js",
        "js/layer-v3.1.1/layer/layer.js",
    ];
}