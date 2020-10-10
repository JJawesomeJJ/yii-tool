<?php

namespace backend\modules\tool\Assets;


use yii\web\AssetBundle;

class VueBundle extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'pager/pagination.css'
    ];
    public $js = [
        "js/vue/vue.min.js",
        'js/vue/axios.js',
        'js/vue/qs.js',
        'js/layer.js',
        'pager/pagination.js'
    ];
}