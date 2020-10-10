<?php


namespace backend\modules\tool\models;


use yii\base\Model;

class AddTask extends Model
{
    public $run_time;
    public $intervals;
    public $task_id;
    public function rules()
    {
        return [
            [
                ['run_time', 'intervals', 'task_id'], 'required'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'run_time' => '下次运行时间',
            'intervals' => '间隔时间',
            'task_id' => '任务ID',
        ];
    }
}