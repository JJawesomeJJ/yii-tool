<?php


use yii\helpers\Html;
use yii\helpers\Url;
use dsj\components\grid\ResponsiveGridView;
use backend\modules\tool\models\FillFormModel;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
\backend\Assets\BaseBundle::register($this);
$this->title = '生成模板页';
$this->params['breadcrumbs'][] = $this->title;
$js=<<<JS
function onsubmit_(){
    var is_post=true;
    $("input").each(function() {
        if($(this).val()==""&&is_post){
        layer.msg($(this).parent().find("label").text()+"不可为空");
        is_post=false;
        }
    });
    return is_post;
}
$("form").submit(function() {
    console.log("load");
    return onsubmit_();
});
JS;
$this->registerJs($js);
?>
<div class="fill-for-template-index">
        <?php $form = ActiveForm::begin(); ?>
            <div class="form-group">
                <label for="namespace">模块命名空间</label>
                <input type="text" name="namespace" class="form-control" id="namespace">
            </div>
            <div class="form-group">
                <label for="modelname">模型名称</label>
                <input type="text" name="modelname" class="form-control" id="modelname">
            </div>
            <div class="form-group">
                <label for="controllername">控制器名称</label>
                <input type="text" name="controllername" class="form-control" id="controllername">
            </div>
            <input type="submit" class="btn btn-success" value="快速生成">
        <?php ActiveForm::end(); ?>

</div>
