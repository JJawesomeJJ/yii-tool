<?php


namespace backend\modules\tool\helpers;


class StringHelper
{
    public static function toUnderScore($str,$replace="-")
    {
        $dstr = preg_replace_callback('/([A-Z]+)/',function($matchs) use ($replace)
        {
            return $replace.strtolower($matchs[0]);
        },$str);
//        $regx="/".$replace."{2,}/";
        $result=trim(preg_replace("/$replace{2,}/",$replace,$dstr),$replace);
        return $result;
    }
}