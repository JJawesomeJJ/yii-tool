<?php

/***@property string $Driver
*@property string $host
*@property integer $port
*@property string $data_base
*@property string $user
*@property string $password
*@property string $source_name
 */

namespace backend\modules\tool\models;

use backend\modules\awesome\helper\Dbhelper;
use backend\modules\tool\helpers\ArrayHelper;

class SqlConfig extends \backend\modules\tool\models\BaseModel
{
    /**
     * @inheritdoc
     */
    protected static $create_sql="create table t_sqlconfig_back_fill(id int(11) not null primary key auto_increment comment 'id',Driver varchar(50) default null comment '数据库类型',host varchar(50) default null comment '主机名称',port int(50) default '3306' comment '端口号',data_base varchar(50) default null comment '数据库名称',user varchar(50) default null comment '用户名',password varchar(50) default null comment '密码',source_name varchar(50) default null comment '数据源名称') ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    public static function tableName()
    {
        return '{{%t_sqlconfig_back_fill}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Driver','host','data_base','user','password','source_name'],'required'],
            [['Driver','host','data_base','user','password','source_name'],'string','max'=>'50'],
            [['port'],'integer']
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Driver'=>'数据库类型',
            'host'=>'主机名称',
            'port'=>'端口号',
            'data_base'=>'数据库名称',
            'user'=>'用户名',
            'password'=>'密码',
            'source_name'=>'数据源名称',
        ];
    }
    public static function GetConfig(){
        $result=self::find()->select("id,source_name")->asArray()->all();
        return ArrayHelper::array_parse_key_value($result,"id","source_name");
    }

    /**
     * @param $id
     * @return \PDO
     */
    public static function GetConfigPdo($id){
        $data=self::find()->where(["id"=>$id])->asArray()->one();
        return \backend\modules\tool\helpers\DbHelper::GetPdo($data["Driver"],$data["host"],$data["user"],$data["password"],$data["port"],$data["data_base"]);
    }
}