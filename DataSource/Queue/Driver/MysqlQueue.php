<?php


namespace backend\modules\tool\DataSource\Queue\Driver;


use backend\modules\tool\DataSource\Queue\Queue;
use TheSeer\Tokenizer\Exception;

class MysqlQueue extends Queue
{
    protected $con;
    protected $bind=[];
    protected $table_name="data_source_queue";
    public function __construct()
    {
        $pdo=new \PDO(\Yii::$app->db->dsn,\Yii::$app->db->username,\Yii::$app->db->password);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->con=$pdo;
    }

    public function push($channel, $data): bool
    {
        return $this->Insert(["channel"=>$channel,"data"=>serialize($data),'push_time'=>date("Y-m-d H:i:s")],$this->con);
    }
    public function size($channel): int
    {
        $sql="select count(1) as num from {$this->table_name} where channel='{$channel}'";
        $stm=$this->con->prepare($sql);//预处理 防止sql注入
        $stm->execute($this->bind_params($sql));
        $stm->setFetchMode(\PDO::FETCH_NAMED);
        return $stm->fetch()["num"];
    }
    public function pop($channel, int $size = 1)
    {
        $this->con->beginTransaction();
        try{
            $sql="select data,id from {$this->table_name} where channel='{$channel}' order by push_time asc limit {$size}";
            $stm=$this->con->prepare($sql);//预处理 防止sql注入
            $stm->execute($this->bind_params($sql));
            $stm->setFetchMode(\PDO::FETCH_NAMED);
            $data=$stm->fetchAll();
            if(empty($data)){
                $this->con->commit();
                return null;
            }else{
                $return_val=[];
                foreach ($data as $datum){
                    $return_val[]=unserialize($datum["data"]);
                }
                $id_list=implode(",",array_column($data,"id"));
                $sql="delete from {$this->table_name} where id in ({$id_list})";
                $this->con->exec($sql);
                $this->con->commit();
                if($size==1){
                    return $return_val[0];
                }
                return $return_val;
            }
        }
        catch (\Throwable $throwable){
            $this->con->rollBack();
            throw new \Exception($throwable->getMessage());
        }
    }
    public function flush($channel)
    {
        $this->con->beginTransaction();
        try{
            $sql="select data,id as num from {$this->table_name} where channel='{$channel}'";
            $stm=$this->con->prepare($sql);//预处理 防止sql注入
            $stm->execute($this->bind_params($sql));
            $stm->setFetchMode(\PDO::FETCH_NAMED);
            $data=$stm->fetchAll();
            if(empty($data)){
                $this->con->commit();
                return null;
            }else{
                $id=$data['id'];
                $sql="delete from {$this->table_name} where channel='{$channel}'";
                $this->con->exec($sql);//预处理 防止sql注入
                $return_value=[];
                foreach ($data as $datum){
                    $return_value[]=unserialize($datum['data']);
                }
                $this->con->commit();
                return $return_value;
            }
        }
        catch (\Throwable $throwable){
            $this->con->rollBack();
            throw new \Exception($throwable->getMessage());
        }
    }
    /**
     * 插入数据 使用预处理更安心
     * @param $insert_data
     * @return mixed
     */
    protected function Insert($insert_data,$con=null){
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
        foreach ($insert_list as $value){
            $insert_list_string.="$value,";
        }
        $insert_list_string.=")";
        $sql="REPLACE INTO  $this->table_name $insert_list_string values $insert_string";
        $sql=str_replace("  "," ",$sql);
        $sql=str_replace(", "," ",$sql);
        $sql=str_replace(",)",")",$sql);
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
    protected function is_1_array(array $arr){
        if (count($arr) == count($arr, 1)) {
            return true;
        } else {
            return false;
        }
    }
}