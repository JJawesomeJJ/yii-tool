<?php


namespace backend\modules\tool\models;


use yii\base\Model;

class Image extends Model
{
    public $img;
    public function rules()
    {
        return [
            [
                ['img'], 'required'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'img' => '图片地址',
        ];
    }
}