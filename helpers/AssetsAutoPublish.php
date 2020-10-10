<?php

/**
 *发布资源到需要系统的web path
 */
namespace backend\modules\tool\helpers;


class AssetsAutoPublish
{
    public static function publish(){
        $need_publish_files=FileHelper::FileWalk(dirname(__FILE__)."/Assets/");
        $current_path=dirname(__FILE__)."/Assets/";
        $web_path=Common::GetWebPathAbs();
        foreach ($need_publish_files as $file){
            $file_web_path=str_replace($current_path,$web_path,$file);
            if(!is_file($file_web_path)||md5_file($file_web_path)!=md5_file($file)){
                FileHelper::mkdir(dirname($file_web_path));
                copy($file,$file_web_path);
            }
        }
    }
}