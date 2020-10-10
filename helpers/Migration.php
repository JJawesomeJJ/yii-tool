<?php


namespace backend\modules\tool\helpers;

class Migration
{
    protected $con;
    public $table_name;
    public $union_primary_key_string;
    protected $create_table_column_list=[];
    protected static $object;
    protected $create_sql=null;
    public static function accept(){
        return [
            "string"=>[
                "comment"=>"可变长度类型最大255",
                "input"=>[
                    "textInput"=>[],
                    "dropDownList"=>Componmet::GetComonmet("dropDownList"),
                    "date"=>Componmet::GetComonmet("date"),
                    "timer"=>Componmet::GetComonmet("timer")
                ]
            ],
            "text"=>[
                "comment"=>"无限制长度类型",
                "input"=>[
                    "textarea"=>Componmet::GetComonmet("textarea"),
                    "uploadimgs"=>Componmet::GetComonmet("uploadimgs"),
                    "dropDownList"=>Componmet::GetComonmet("dropDownList")
                ]
            ],
            "integer"=>[
                "comment"=>"输入类型为整数",
                "input"=>[
                    "textInput"=>Componmet::GetComonmet('textInput'),
                    "dropDownList"=>Componmet::GetComonmet('dropDownList'),
                    "params"=>[
                        "length"=>11
                    ]
                ]
            ],
            "number"=>[
                "comment"=>"输入类型小数",
                "input"=>[
                    "textInput"=>Componmet::GetComonmet("textInput")
                ]
            ]
        ];
    }
    public function tableName($tablename){
        $this->table_name=$tablename;
        return $this;
    }
    public static $params_comment=[
        "create_column_name"=>[
            "default"=>"",
            "comment"=>"字段名称英文,命名需按照C语言命名规范"
        ],
        "length"=>[
            "default"=>"50",
            "comment"=>"字段长度"
        ],
        "default_null"=>[
            "default"=>"null",
            "comment"=>"默认值"
        ],
        "primary_key"=>[
            "default"=>["false","true"],
            "comment"=>"是否设为主键"
        ],
        "auto_increment"=>[
            "default"=>["false","true"],
            "comment"=>"是否自动增长"
        ],
        "precison"=>[
            "default"=>0,
            "comment"=>"几位小数"
        ],
        "rows"=>[
            "default"=>6,
            "comment"=>"显示行数，行数越长这个富文本框越大"
        ],
        "required"=>[
            "default"=>["false","true"],
            "comment"=>"是否必填"
        ],
        "commemt"=>[
            "default"=>"",
            "comment"=>"字段中文注释"
        ]
    ];
    const fileds_type=[
        "string"=>[
            "type"=>"string",
            "maxlength"=>50
        ],
        "char"=>"string",
        "datetime",
        "integer",
        "tinyint",
        "unsignedinteger",
        "decimal"
    ];
    public static function GetFiledsParams(){
        return (new ClassAnalyse(Migration::class))->GetMethods(array_keys(self::accept()));
    }
    protected function __construct()
    {
        $this->con=\Yii::$app->db->pdo;
    }
    public static function SingleTon(){
        if(empty(self::$object)){
            self::$object=new self();
        }
        return self::$object;
    }
    public function string($create_column_name,$commemt,$length,$required=false,$default_null=true,$primary_key=false){
        if($default_null===true){
            $default_null="default null";
        }
        else{
            $default_null="default '$default_null'";
        }
        if($primary_key==false){
            $primary_key="";
        }
        else{
            $primary_key="primary key";
            $default_null="default not null";
        }
        $this->create_table_column_list[]="$create_column_name varchar($length) $default_null $primary_key,";
        $this->commemt($commemt);
        return $this;
    }
    public function unique(){
        $length=count($this->create_table_column_list);
        $this->create_table_column_list[$length-1]=str_replace(",,","",$this->create_table_column_list[$length-1].",unique,");
        return $this;
    }
    public function commemt($comment){
        $length=count($this->create_table_column_list);
        $this->create_table_column_list[$length-1]=str_replace(' ,',' ',str_replace(",,","",$this->create_table_column_list[$length-1]."comment '$comment',"));
        return $this;
    }
    public function char($create_column_name,$length,$default_null=true,$required=false){
        $primary_key=false;
        if($default_null===true){
            $default_null="default null";
        }
        else{
            $default_null="default '$default_null'";
        }
        if($primary_key=false){
            $primary_key="";
        }
        else{
            $primary_key="primary key";
            $default_null="default not null";
        }
        $this->create_table_column_list[]="$create_column_name char($length) $default_null $primary_key,";
        return $this;
    }
    public function datetime($create_column_name){
        $this->create_table_column_list[]="$create_column_name DATETIME,";
        return $this;
    }
    public function integer($create_column_name,$commemt,$length,$required=false,$default_null=false,$primary_key=false,$auto_increment=false){
        if($default_null===false||$default_null=="null"){
            $default_null="default null";
        }
        else{
            $default_null="default '$default_null'";
        }
        if($primary_key==false){
            $primary_key="";
        }
        else{
            $primary_key="primary key";
            $default_null="default not null";
        }
        if($auto_increment!=false){
            $auto_increment="auto_increment";
        }
        $this->create_table_column_list[]="$create_column_name int($length) $default_null $primary_key $auto_increment,";
        $this->commemt($commemt);
        return $this;
    }
    public function tinyint($create_column_name,$length,$default_null=false,$primary_key=false,$auto_increment=false){
        if($default_null===false){
            $default_null="default null";
        }
        else{
            $default_null="default '$default_null'";
        }
        if($primary_key==false){
            $primary_key="";
        }
        else{
            $primary_key="primary key";
            $default_null="default not null";
        }
        if($auto_increment!=false){
            $auto_increment="auto_increment";
        }
        $this->create_table_column_list[]="$create_column_name tinyint($length) $default_null $primary_key $auto_increment,";
        return $this;
    }
    public function unsignedinteger($create_column_name,$length,$default_null=false,$primary_key=false,$auto_increment=false){
        if($default_null===false){
            $default_null="default null";
        }
        else{
            $default_null="default '$default_null'";
        }
        if($primary_key==false){
            $primary_key="";
        }
        else{
            $primary_key="primary key";
            $default_null="default not null";
        }
        if($auto_increment!=false){
            $auto_increment="auto_increment";
        }
        $this->create_table_column_list[]="$create_column_name int($length) unsigned $default_null $primary_key $auto_increment,";
        return $this;
    }
    public function timestamp($create_column_name){
        $this->create_table_column_list[]="$create_column_name TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,";
        return $this;
    }
    public function text($create_column_name,$commemt){
        $default_null=true;
        if($default_null===true){
            $default_null="default null";
        }
        else{
            $default_null="default '$default_null'";
        }
        $this->create_table_column_list[]="$create_column_name text,";
        $this->commemt($commemt);
        return $this;
    }
    public function number($create_column_name,$commemt,$length,$precison,$required=false,$default_null=true){
        if($default_null===true){
            $default_null="default null";
        }
        else{
            $default_null="default '$default_null'";
        }
        $this->create_table_column_list[]="$create_column_name decimal($length,$precison) $default_null,";
        $this->commemt($commemt);
        return $this;
    }
    public function foreign_key($this_table_key,$foreign_table,$foreign_key){
        $this->create_table_column_list[]="foreign key($this_table_key) references $foreign_table($foreign_key),";
        return $this;
    }
    public function create(){
        $sql="create table $this->table_name(";
//        $sql="create table $this->table_name(";
        foreach ($this->create_table_column_list as $value){
            $sql.=$value ;
        }
        $sql.=$this->union_primary_key_string;
        $sql.=") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql=str_replace("  "," ",$sql);
        $sql=str_replace(",)",")",$sql);
        $sql=str_replace("default not null","not null",$sql);
        $sql=str_replace(",comment "," comment ",$sql);
        $sql=str_replace("default 'not null'","not null",$sql);
        $sql=str_replace("default 'null'","default null",$sql);
        $this->create_sql=$sql;
        try {
            $result = $this->con->exec($sql);
            return true;
        }
        catch (\Throwable $exception){
            throw new \Exception("create sql:$sql"."message: {$exception->getMessage()}");
        }
//        if(is_numeric($result)){
//            echo $sql.PHP_EOL;
//            return true;
//        }
//        echo $this->table_name." already exists".PHP_EOL;
//        return false;
    }
    public function GetRunSql(){
        return $this->create_sql;
    }
}