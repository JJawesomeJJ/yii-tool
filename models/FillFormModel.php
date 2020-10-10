<?php


namespace backend\modules\tool\models;


use yii\db\ActiveRecord;

class FillFormModel extends ActiveRecord
{
    public static $unique_template_key;
    protected static $is_unique=false;
    public static $primary_key;
    public static $defalut=[];//设置默认值
    public $key;
    public static function tableName()
    {
        return '{{%fill_form_tool}}';
    }

    /**
     * 找到一个模型根据key值
     * @param $key
     * @return array|ActiveRecord|null
     */

    public function InitModel(){
        foreach (json_decode($this->value,true) as $key=>$value){
            if(property_exists($this,$key)) {
                $this->$key = $value;
            }
        }
        return $this;
    }
    public static function is_1_array(array $arr){
        if (count($arr) == count($arr, 1)) {
            return true;
        } else {
            return false;
        }
    }
    public static function find()
    {
        return parent::find()->andWhere(["p_key"=>get_called_class()::$unique_template_key]); // TODO: Change the autogenerated stub
    }

    /**
     * @param $p_key
     * @param array $filter 过滤器 每一项必须是一个闭包函数
     * @param $is_array 是否转化为数组
     * @return array|\yii\db\ActiveQuery
     * @throws \Exception
     */
    public static function FindByPk($p_key,array $filter=[],$is_array=false){
        $models=self::find();
        if($is_array){
            $models->asArray();
        }
        $models->all();
        if($is_array){
            foreach ($models as &$item){
                $item['value']=json_decode($item['value'],true);
            }
        }else{
            foreach ($models as &$item){
                $item->value=json_decode($item->value,true);
            }
        }
        if(empty($filter)){
            return $models;
        }
        $result=[];
        foreach ($models as $model) {
            foreach ($filter as $item) {
                if (!$item instanceof \Closure) {
                    throw new \Exception("filter item should be Closure but " . gettype($item) . " given");
                }
                if(!call_user_func_array($item,[$model])){
                    continue;
                }
            }
            $result[]=$model;
        }
        return $result;
    }
    protected function GetInsertValue(){
        $result=[];
        foreach ($this->rules() as $item){
            foreach ($item[0] as $filed){
                $result[]=$filed;
            }
        }
        return array_unique($result);
    }

    public function getDirtyAttributes($names = null)
    {
        $result=parent::getDirtyAttributes($names); // TODO: Change the autogenerated stub
        $result['p_key']=get_called_class()::$unique_template_key;
        $value=[];
        foreach ($this->GetInsertValue() as $filed){
            $value[$filed]=$this->$filed;
        }
        $result['value']=json_encode($value);
        $result['unique_hash']=md5($result['value']);
        if(empty($result['created_at'])){
            $result['created_at']=time();
        }
        $key=get_called_class()::$primary_key;
        if(get_called_class()::$is_unique){
            $result['key']=$this->key;
        }else {
            $result['key'] = $this->$key;
        }
        $result['updated_at']=date("Y-m-d h:i:s",time());
        return $result;
    }
    public function afterFind()
    {
        $this->InitModel();
        parent::afterFind(); // TODO: Change the autogenerated stub
    }
    public static function FindOneOrCreate(){
        $class_name=get_called_class();
        return self::find()->limit(1)->one()??new $class_name();
    }
    public static function Tojson($data){
        if(is_object($data)){
            $data=$data->toArray();
        }
        if(empty($data)){
            return [];
        }
        if(is_array($data)&&self::is_1_array($data)){
            $info=json_decode($data['value'],true);
            $info['id']=$data['id'];
            return $info;
        }
        $result=[];
        foreach ($data as $datum){
            $info=json_decode($datum['value'],true);
            $info['id']=$datum['id'];
            $result[]=$info;
        }
        return $result;
    }
    public function ToJsonThis(){
        return self::Tojson($this);
    }
    public static function DefaultValue(){

    }

    /**
     * 设置默认的数据
     * @param $data
     * @return mixed
     */
    public static function SetDefaults($data){
        if(empty($defalut=get_called_class()::DefaultValue())) {
            $defalut = get_called_class()::$defalut;
        }
        if(empty($defalut)){
            return $data;
        }
        foreach ($data as &$item){
            if(empty($defalut=get_called_class()::DefaultValue())) {
                $defalut = get_called_class()::$defalut;
            }
            foreach ($item as $key=>$value){
                if(empty($value)){
                    if(!empty($defalut[$key])){
                        $item[$key]=$defalut[$key];
                    }
                }
            }
        }
        return $data;
    }
    public static function SetDefault($data){
        if(empty($defalut=get_called_class()::DefaultValue())) {
            $defalut = get_called_class()::$defalut;
        }
        if(empty($defalut)){
            return $data;
        }
        foreach ($defalut as $key=>$value){
            if(empty($data[$key])){
                if(!empty($defalut[$key])){
                    $data[$key]=$defalut[$key];
                }
            }
        }
        return $data;
    }
//    public function toArray(array $fields = [], array $expand = [], $recursive = true)
//    {
//        return $this->ToJsonThis();
//    }
}