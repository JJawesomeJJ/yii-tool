<?php

namespace backend\modules\tool\models;

use Yii;

/**
 * This is the model class for table "{{%fill_form_tool}}".
 *
 * @property int $id
 * @property string $key 字段的KEY
 * @property string $value 字段的具体值
 * @property string $unique_hash 字段的唯一hash值
 * @property int $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class FillFormTool extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fill_form_tool}}';
    }
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->template_path=dirname(dirname(__FILE__))."/template/";
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key'], 'required'],
            [['created_at'], 'integer'],
            [['updated_at','template_id'], 'safe'],
            [['key'], 'string', 'max' => 50],
            [['value'], 'string', 'max' => 255],
            [['unique_hash'], 'string', 'max' => 100],
            [['key'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => '字段的KEY',
            'value' => '字段的具体值',
            'unique_hash' => '字段的唯一hash值',
            'template_id'=>"模版的id",
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
