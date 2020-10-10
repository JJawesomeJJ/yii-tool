<?php


namespace backend\modules\tool\helpers;


class FileHelper
{
    public static function mkdir(string $path,$priv=0777){
        if(is_dir($path)){
            return;
        }else{
            if(!is_dir(dirname($path))){
                self::mkdir(dirname($path),$priv);
            }
            mkdir($path,$priv);
        }
    }
    public static function FileWalk($dir,$except=[]){
        $dir=str_replace("//","/",$dir);
        $result=[];
        if(in_array($dir,$except))
        {
            return [];
        }
        if(is_file($dir)){
            return [$dir];
        }
        if(@$handle = opendir($dir)) { //注意这里要加一个@，不然会有warning错误提示：）
            while(($file = readdir($handle)) !== false) {
                if($file != ".." && $file != ".") {
                    if(is_dir($dir."/".$file)) {
                        foreach (self::FileWalk($dir."/".$file,$except) as $item){
                            $result[]=$item;
                        }
                    } else {
                        $result[]=str_replace("//","/",$dir."/".$file);
                    }
                }
            }
            closedir($handle);
            return $result;
        }else{
            return [];
        }
    }
    public static function GetFileName($path,$ex=".php"){
        $name_info=explode("/",$path);
        $name=$name_info[count($name_info)-1];
        return explode('.',$name)[0].$ex;
    }
}