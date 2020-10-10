<?php


namespace backend\modules\tool\helpers;


class TemplateHelper
{
    /**
     * 模板编译组件
     * @param string $template_string
     * @param array $params
     * @return mixed|string
     */
    public static function parse(string $template_string,array $params){
        foreach ($params as $key=>$value){
            if(is_array($value)){
                $value=self::ParseArray($value);
            }
            $template_string=str_replace("{{{$key}}}",$value,$template_string);
        }
        return $template_string;
    }
    public static function ParseArray(array $arr){
        $result='';
        if($arr==array_values($arr)){//是否为键值对
            foreach ($arr as $item){
                if(is_array($item)){
                    $item=self::ParseArray($arr);
                }
                $result.="'$item',";
            }
            $result=substr($result,0,strlen($result)-1);
            $result="[".$result."]";
        }else{
            foreach ($arr as $key=>$value){
                if(is_array($value)){
                    $value=self::ParseArray($value);
                }
                $result.="'$key'=>'$value',";
            }
            $result=substr($result,0,strlen($result)-1);
            $result="[".$result."]";
        }
        $result=str_replace("'['","['",$result);
        $result=str_replace("']'","']".PHP_EOL,$result);
        return $result;
    }
    public static function AddPHPEOF($str,$num=12){
        $result="";
        $arr=explode(PHP_EOL,$str);
        foreach ($arr as $index=>$value){
            if($index==count($arr)-1){
                $result.=str_repeat(" ",$num).$value;
                continue;
            }
            $result.=str_repeat(" ",$num).$value.PHP_EOL;
        }
        return $result;
    }
    public static function RemoveLast($str,$len=1){
        return substr($str,0,strlen($str)-$len);
    }
    protected static function GetFileName($path){
        $name_info=explode("/",$path);
        $name=$name_info[count($name_info)-1];
        return explode('.',$name)[0];
    }
    /**
     * 编译验证规则里面的参数
     * @param $params
     * @return false|string|null
     *
     */
    protected static function CompileRulesParams($params){
        if(empty($params)){
            return null;
        }
        $params_string="";
        foreach ($params as $key=>$value){
            $params_string.="'".$key."'=>'".$value."',";
        }
        return substr($params_string,0,strlen($params_string)-1);
    }
    public static function Compile($path,$params,$des_path,$rename=false,$is_store=true){
        if(is_dir($path)){
            $files=FileHelper::FileWalk($path);
        }else{
            $files=[$path];
        }
        $result="";
        foreach ($files as $file){
            $template=file_get_contents($file);
            $template=TemplateHelper::parse($template,$params);
            if($rename==false) {
                $file_name = $des_path . "/" . FileHelper::GetFileName($file);
            }
            else{
                $file_name = $des_path . "/" .$rename;
            }
            if($is_store) {
                $file_name=str_replace("\\","/",$file_name);
                FileHelper::mkdir(dirname($file_name));
                file_put_contents($file_name, $template);
            }
            $result.=$template;
        }
        return $result;
    }
    public static function CompileRules(array $fileds){

    }
    public static function implode(array $arr,$deplo=","){
        $str="";
        foreach ($arr as $item){
            $str.="'".$item."'$deplo";
        }
        return self::RemoveLast($str);
    }
}