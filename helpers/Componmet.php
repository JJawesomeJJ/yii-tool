<?php

/**
 * Description it is a yii html form widget register center
 * author jjawesome
 * created at 2020/6/2
 */
namespace backend\modules\tool\helpers;


use backend\modules\tool\models\FillFormModel;

class Componmet
{
    public static function list()
    {
        return [
            "textInput" => [
                "name"=>"通用表单提交框",
                "template" => '<?= $form->field($model, \'{{key}}\')->textInput([\'maxlength\' => true]) ?>',
                "needs" => [

                ],
                "search" => [
                    "like" => [
                        "name" => "模糊匹配",
                        "widget" => [
                            "textInput"
                        ]
                    ],
                    "=" => [
                        "name" => "精确匹配",
                        "widget"=>[
                            "textInput",
                            "dropDownList"
                        ]
                    ]
                ]
            ],
            'textarea' => [
                "name"=>"富文本框",
                "template" => '<?= $form->field($model, \'{{key}}\')->textarea([\'rows\' =>{{rows}}]) ?>',
                'needs' => [[
                    "name" => "rows",
                    "default" => "6",
                    "commemt" => "显示行数，行数越长这个富文本框越大"
                ]],
                "search" => [
                    "like" => [
                        "name" => "模糊匹配",
                        "widget" => [
                            "textInput"
                        ]
                    ],
                    "=" => [
                        "name" => "精确匹配",
                        "widget"=>[
                            "textInput"
                        ]
                    ]
                ]
            ],
            'uploadimgs' => [
                "name"=>"图片上传框",
                "template" => '<p><?= $model->attributeLabels()["{{key}}"] ?></p>
                    <?= \backend\modules\tool\helpers\widgets\UploadImages::widget([
                        "model"=>$model,
                        "filed"=>"{{key}}",
                        "max"=>"{{max}}",
                    ]) ?>',
                "view" => false,
                "needs" => [
                    ["name" => "max", "default" => "1", "commemt" => "请输入最大上传图片的数量"]
                ],
                "search" => [

                ]
            ],
            'dropDownList' => [
                "name"=>"下拉选择框",
                "template" => ' <?= $form->field($model, \'{{key}}\')->dropDownList({{list}}) ?> ',
                "params" => function ($params) {
                    if(!empty($params['is_filter'])&&$params['is_filter']){
                        $params['list']=\Yii::$app->request->post("modelname")."::GetTypeSelect('".$params['key']."')";
                    }else {
                        $list = explode(" ", $params["list"]);
                        if (!empty($params['is_filter']) && $params['is_filter']) {
                            array_unshift($list, "");
                        }
                        $params["list"] = TemplateHelper::ParseArray(ArrayHelper::ArrayParseJson($list));
                    }
                    return $params;
                },
                "needs" => [
                    ["name" => "list", "default" => "", "commemt" => "请输入下拉框的值,以空格分割"]
                ],
                "search" => [
                    "like" => [
                        "name" => "模糊匹配",
                        "widget" => [
                            "textInput"
                        ]
                    ],
                    "=" => [
                        "name" => "精确匹配",
                        "widget" => [
                            "textInput",
                            "dropDownList"
                        ]
                    ]
                ]
            ],
            "date" => [
                "name"=>"日期选择框",
                "template" => '<?= $form->field($model, \'{{key}}\')->widget(\kartik\date\DatePicker::className(),[
                \'options\' => [\'placeholder\' => \'时间...\'],
                \'pluginOptions\' => [
                    \'format\' => \'{{format}}\',
                    \'todayHighlight\' => true
                ]
            ])?>',
                'needs' => [
                    ["name" => "format", "default" => "yyyy-mm-dd", "commemt" => "请输入日期选择器的格式"]
                ],
                "search" => [
                    "=" => [
                        "name" => "精确匹配",
                        "widget" => [
                            "textInput",
                            "date"
                        ]
                    ],
                    "in"=>[
                        "name" => "时间段匹配",
                        "widget" => [
                            "date",
                        ]
                    ]
                ]
            ],
            "timer"=>[
                "name"=>"时间选择器精确到秒",
                "template"=>'<?= $form->field($model, \'{{key}}\')->widget(\kartik\datetime\DateTimePicker::classname(), [
                            \'options\' => [\'placeholder\' => \'\'],
                    //        \'type\' => DateTimePicker :: TYPE_COMPONENT_APPEND,
                            \'removeButton\' => false,
                            \'pluginOptions\' => [
                                \'autoclose\' => true,
                                \'format\' => \'{{format}}\',
                            ]
                        ]); ?>',
                'needs' => [
                    ["name" => "format", "default" => "yyyy-mm-dd hh:ii:ss", "commemt" => "请输入时间选择器日期的格式"]
                ],
                "search" => [
                    "=" => [
                        "name" => "精确匹配",
                        "widget" => [
                            "textInput",
                            "timer"
                        ]
                    ],
                    "in"=>[
                        "name" => "时间段匹配",
                        "widget" => [
                            "timer",
                        ]
                    ]
                ]
            ],
        ];
    }

    public static function GetComonmet(string $name)
    {
        return self::list()[$name]["needs"] ?? [];
    }
    public static function GetWidgetSearch(){
        $result=[];
        foreach (self::list() as $key=>$value){
            $result[$key]=$value["search"];
        }
        return $result;
    }
    public static function GetWigetName(){
        $result=[];
        foreach (self::list() as $key=>$value){
            $result[$key]=$value["name"];
        }
        return $result;
    }
}