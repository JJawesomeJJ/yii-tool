<?php


namespace backend\modules\tool\Assets;


use backend\Assets\BaseBundle;

class IndexAssets extends BaseBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "js/zTree-3.5/css/zTreeStyle.css"
    ];
    public $js = [
        "js/zTree-3.5/js/jquery.ztree.copy.js",
        "js/ztreeModal.js",
        'js/jQuery/jquery.browser.js'
    ];
}