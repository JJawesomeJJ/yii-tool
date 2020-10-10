<?php


namespace backend\modules\tool\Assets;


use yii\web\AssetBundle;

class SeachBundle extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/search_index.css'
    ];
    public $js = [
        "js/index_search.js",
    ];
}