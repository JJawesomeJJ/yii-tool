<?php


namespace backend\modules\tool\Assets;


use yii\web\AssetBundle;


class OneUpload extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        'uploadimg/img_upload.css',
//        'css/base.css'
    ];
    public $js = [
        "uploadimg/img_upload_one.js",
        "js/layer-v3.1.1/layer/layer.js",
    ];
}