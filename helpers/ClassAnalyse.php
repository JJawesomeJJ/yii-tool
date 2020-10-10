<?php


namespace backend\modules\tool\helpers;


class ClassAnalyse
{
    protected $class;
    public function __construct($class_name)
    {
        if(!class_exists($class_name)){
            throw new \Exception("Fail load class ".$class_name);
        }
        $this->class=new \ReflectionClass($class_name);
    }
    public function ComputeFunctionParams($function_name){
        $fun=$this->class->getMethod($function_name);
        $params=$fun->getParameters();
        return $params;
    }
    public function GetMethods($accept=[]){
        $result=[];
        foreach ($this->class->getMethods() as $method){
            $params=$method->getParameters();
            $params_list=[];
            foreach ($params as $param){
                $params_list[]=$param->name;
            }
            if(empty($accept)) {
                $result[$method->name] = $params_list;
            }else{
                if(in_array($method->name,$accept)){
                    $result[$method->name] = $params_list;
                }
            }
        }
        return $result;
    }
}