<?php

namespace backend\modules\tool\DataSource;


class SqlSource extends DataAdapter
{
    protected $target_pdo=null;
    protected $batch_num=10;//设置批量处理的数量
    protected $table_name=null;
    protected $bind_params=[];
    protected $local_pdo=null;
    protected $bind=[];
    protected $truct_local=false;
    protected $is_bind=true;//是否使用参数绑定
    public function __construct()
    {
        if(!self::is_cli()){
            throw new \Exception("程序必须在cli模式下运行");
        }
        if(empty($this->table_name)){
            throw new \Exception("请配置目标数据表");
        }
        if(empty($this->sql)){
            throw new \Exception("请配置数据源的SQL语句");
        }
        if(empty($this->local_pdo)) {
            $this->local_pdo = $this->GetLocalPdo();//获取本机的pdo连接
        }
        if(empty($this->target_pdo)) {
            $this->target_pdo = self::GetPdo(
                $this->source_config["driver"] ?? 'mysql',
                $this->source_config["host"],
                $this->source_config["user"],
                $this->source_config["password"],
                $this->source_config["port"],
                $this->source_config["database"]);
        }
        if($this->truct_local){
            $this->trucated();//清空本地数据
        }
    }

    protected $sql;

    /**
     *配置具体的连接配置
     * 远程配置的数据库连接
     */
    protected $source_config=[
        "driver"=>"mysql",
        "host"=>"127.0.0.1",
        "port"=>"3306",
        "database"=>"dsj_bn_show",
        "user"=>"root",
        "password"=>".zlj19971998",
    ];
    protected function trucated(){
        $this->local_pdo->exec("truncate table {$this->table_name}");
    }
    protected function DataSource()
    {
        if(!empty($this->CreateSql())){
            $this->sql=$this->CreateSql();
        }
        $stm=$this->target_pdo->prepare($this->sql);
        $stm->setFetchMode(\PDO::FETCH_NAMED);
        $result=$stm->execute($this->bind_params);
        if($result==false){
            $params_string=json_encode($this->bind_params);
            throw new \Exception("Sql 语句可能错误：[{$this->sql},params:[{$params_string}]]");
        }
        $wait_handdle_data=[];
        while (!empty($data=$stm->fetch())){
            if(count($wait_handdle_data)>=$this->batch_num){
                $handled_data=$this->HandleData($wait_handdle_data);//处理后的数据
                if(!empty($handled_data)) {
                    $this->StoreData($handled_data);
                    $wait_handdle_data = [];
                }
            }
            $wait_handdle_data[]=$data;
        }
        if(!empty($wait_handdle_data)){
            $handled_data=$this->HandleData($wait_handdle_data);//处理后的数据 //debug
            if(empty($handled_data)){
                return;
            }
            $this->StoreData($handled_data);
        }
    }
    protected function HandleData($data)
    {
        return $data;
    }
    protected function StoreData($data)
    {
        if($this->is_bind) {
            $this->Insert($data);
        }else{
            $this->InsertOrgin($data);
        }
    }
    protected function GetLocalPdo(){
        $dns=\Yii::$app->db->dsn;
        $user=\Yii::$app->db->username;
        $password=\Yii::$app->db->password;
        return self::GetPdoDsn($dns,$user,$password);
    }

    /**
     * 插入数据 使用预处理更安心
     * @param $insert_data
     * @return mixed
     */
    public function Insert($insert_data,$con=null){
        $insert_list=null;
        $insert_string="";
        $insert_list_string="(";
        if($this->is_1_array($insert_data)){
            $insert_list=array_keys($insert_data);
            $insert_string_column="(";
            foreach ($insert_data as $value){
                $value_key=$this->unique_key($value);
                $insert_string_column.="$value_key,";
            }
            $insert_string_column.=")";
            $insert_string.=$insert_string_column;
        }
        else{
            $insert_list=array_keys($insert_data[0]);
            foreach ($insert_data as $insert_value){
                $insert_string_column="(";
                foreach ($insert_value as $value){
                    $value_key=$this->unique_key($value);
                    $insert_string_column.="$value_key,";
                }
                $insert_string_column.="),";
                $insert_string.=$insert_string_column;
            }
            $insert_string=substr($insert_string,0,strlen($insert_string)-1);
        }
        $insert_update_string_value="";
        foreach ($insert_list as $value){
            $insert_list_string.="`$value`,";
            $insert_update_string_value.="$value=values($value),";
        }
        $insert_list_string.=")";
        $insert_update_string_value=substr($insert_update_string_value,0,strlen($insert_update_string_value)-1);
        $sql="INSERT INTO  $this->table_name $insert_list_string values $insert_string on duplicate key update $insert_update_string_value";
        $sql=str_replace("  "," ",$sql);
        $sql=str_replace(", "," ",$sql);
        $sql=str_replace(",)",")",$sql);
//        echo $sql;
//        sleep(100);
        if(!empty($con)){
            $stm=$con->prepare($sql);
        }else {
            $stm = $this->local_pdo->prepare($sql);
        }
        $result=$stm->execute($this->bind_params($sql));
        if($result){
            $this->bind=[];//释放内存
        }else{
            throw new \Exception($sql);
        }
        return $result;
    }
    protected function unique_key($value){
        $key=":a".strval(count($this->bind));
        $this->bind[$key]=$value;
        return $key;
    }
    protected function bind_params($sql){
        $bind_params=[];
        preg_match_all("/:a(\d)+/",$sql,$matchs);
        foreach ($matchs[0] as $item){
            $bind_params[$item]=$this->bind[$item];
        }
        return $bind_params;
    }
    protected static function is_cli(){
        return preg_match("/cli/i", php_sapi_name()) ? true : false;
    }
    protected function CreateSql(){

    }
    /**
     * 插入数据 使用预处理更安心
     * @param $insert_data
     * @return mixed
     */
    public function InsertOrgin($insert_data,\PDO $con=null){
        $insert_list=null;
        $insert_string="";
        $insert_list_string="(";
        if($this->is_1_array($insert_data)){
            $insert_list=array_keys($insert_data);
            $insert_string_column="(";
            foreach ($insert_data as $value){
                $value_key=$this->unique_key($value);
                $insert_string_column.="$value_key,";
            }
            $insert_string_column.=")";
            $insert_string.=$insert_string_column;
        }
        else{
            $insert_list=array_keys($insert_data[0]);
            foreach ($insert_data as $insert_value){
                $insert_string_column="(";
                foreach ($insert_value as $value){
//                    $value_key=$this->unique_key($value);
                    if(!is_object($value)) {
                        $insert_string_column .= "'$value',";
                    }else{
                        $insert_string_column .= "$value->sql,";
                    }
                }
                $insert_string_column.="),";
                $insert_string.=$insert_string_column;
            }
            $insert_string=substr($insert_string,0,strlen($insert_string)-1);
        }
        foreach ($insert_list as $value){
            $insert_list_string.="$value,";
        }
        $insert_update_string_value="";
        foreach ($insert_list as $value){
            $insert_list_string.="`$value`,";
            $insert_update_string_value.="$value=values($value),";
        }
        $insert_list_string.=")";
        $sql="INSERT INTO  $this->table_name $insert_list_string values $insert_string on duplicate key update $insert_update_string_value";
        $sql=str_replace("  "," ",$sql);
        $sql=str_replace(", "," ",$sql);
        $sql=str_replace(",)",")",$sql);
        if(!empty($con)){
            $stm=$con->exec($sql);
        }else {
            $stm = $this->local_pdo->exec($sql);
        }
        $result=$stm;
        if($result){
            $this->bind=[];//释放内存
        }else{
            throw new \Exception($sql);
        }
        return $result;
    }
}