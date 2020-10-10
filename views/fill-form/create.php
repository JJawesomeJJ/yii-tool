<?php

/* @var $this yii\web\View */
/* @var $model backend\modules\tool\models\FillFormTool */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = '创建字段';
\backend\Assets\VueBundle::register($this);
$css=<<<css
.files_container{
position: relative;
width: 300px;
height: 200px;
background-color: lightgrey;
}
.btn-success{
position: relative;
margin: 0 auto;
width: 200px;
}
css;
$js=<<<js
 new Vue({
        el:"#container",
        data:{
            keys:[""],
        },
        methods:{
            add_filed(){
                this.keys.push("");
            },
            
        }
    })
js;
$this->registerJs($js);
?>
<div class="fill-form-tool-create" style="position: relative">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?//= $form->field($model, 'unique_hash')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?//= $form->field($model, 'created_at')->textInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'updated_at')->textInput() ?>
    <div id="container" class="files_container">
        <div v-for="i,index in keys">
            <div class="form-group">
                <label for="pwd">字段{{index+1}}</label>
                <input type="text" class="form-control" id="pwd" name="fileds[]" placeholder="请输入字段名">
            </div>
        </div>
        <button type="button" class="btn-sm btn-primary" @click="add_filed"><i class="glyphicon glyphicon-plus"></i>添加</button>
    </div>
    <div class="form-group" style="position: relative;margin: 0 auto;width: 100%;">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<script>
</script>
