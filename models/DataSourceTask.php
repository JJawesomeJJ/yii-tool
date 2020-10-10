<?php

/***@property string $class_path
*@property integer $pid
*@property integer $task_id
*@property string $run_time
*@property string $intervals
*@property string $status
*@property string $description
 */

namespace backend\modules\tool\models;

use backend\modules\tool\DataSource\DataAdapter;
use backend\modules\tool\Job\SqlNode;

class DataSourceTask extends \backend\modules\tool\models\BaseModel
{
    /**
     * @inheritdoc
     */
    const tick=DataAdapter::tick;
    protected static $create_sql="create table t_datasourcetask_back_fill(id int(11) not null primary key auto_increment comment 'id',class_path varchar(50) default null comment '任务类的命名空间',pid int(50) default null comment '当前任务的pid',task_id int(50) default null comment '任务ID',run_time varchar(50) default null comment '运行时间',intervals varchar(50) default null comment '程序运行间隔时间minute-hour-day-week-month-year',status varchar(50) default null comment '是否启用',description varchar(50) default null comment '任务描述') ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    public static function tableName()
    {
        return '{{%t_datasourcetask_back_fill}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class_path','task_id','intervals'],'required'],
            [['class_path','run_time','intervals','description'],'string','max'=>'50'],
            [['pid','task_id','status'],'integer']
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'class_path'=>'任务类的命名空间',
            'pid'=>'当前任务的pid',
            'task_id'=>'任务ID',
            'run_time'=>'运行时间',
            'intervals'=>'程序运行间隔时间minute-hour-day-week-month-year',
            'status'=>'是否启用',
            'description'=>'任务描述',
        ];
    }
    public static function NameSpace(){
        return [
            "SQL节点"=>SqlNode::class
        ];
    }
    public static function join($id,$time,$run_time){
        $model=new self();
        $model->task_id=$id;
        $model->intervals=$time;
        $model->class_path=SqlNode::class;
        $model->run_time=strtotime($run_time);
        $model->save();
    }
    public function nextTick(){
        $interval_info=explode("*",$this->intervals);
        $tick_falg=self::tick[$interval_info[1]];//获取当前的时间维度标识 week
        if(is_numeric($tick_falg)){
            $this->run_time=date("Y-m-d H:i:s",strtotime($this->run_time)+$tick_falg*$interval_info[0]);
        }else{
            $this->run_time=date("Y-m-d H:i:s",strtotime($this->run_time))." +{$interval_info[0]} {$tick_falg}";
        }
        $this->save();
    }
}