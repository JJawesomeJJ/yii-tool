<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\tool\models\Node */
/* @var $form yii\widgets\ActiveForm */
\backend\modules\tool\Assets\VueBundle::register($this);
$fileds_url=\yii\helpers\Url::to(["reflection"]);
$create_sql_url=\yii\helpers\Url::to(["create-sql"]);
\backend\modules\tool\Assets\BaseBundle::register($this);
\backend\modules\tool\Assets\LineBundle::register($this);
$js=<<<js
$(document).ready(function() {
   var clipboard = new ClipboardJS('.btn');
clipboard.on('success', function(e) {
console.log(e);
})   
});
new Vue({
el:"#app",
data:{
    source_fileds:[],
    desc_fileds:[],
    line:null
},
methods:{
    show(){
        layer.open({
          type: 1,
          shade: false,
          title: false, //不显示标题
          content: $('.get_create_sql'), //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
        });
    },
    get_create_sql(){
         axios.post('{$create_sql_url}',data={table:$("#table_name").val(),source_config:$("#node-source_config").val()}).then(function(res) {
             res=res['data']['data'];
             $("#foo").val(res);
             setTimeout(function() {
             },100)
             
         }).catch(function(err) {
           console.log(err)
         });
    },
    get_map(){
        var self=this;
        var t = $('form').serialize();
        console.log(t);
        axios.post('{$fileds_url}',data=t).then(function(res) {
          self.source_fileds=res['data']['source'];
          self.desc_fileds=res['data']['desc'];
          layer.open({
            type: 1,
            content: $('#show_data'), //这里content是一个DOM，注意：最好该元素要存放在body最外层，否则可能被其它的相对元素所影响
            area: ['800px', '600px'], //宽高
        });
          setTimeout(function() {
              config=[];
              for(var i of self.source_fileds){
                  if(self.desc_fileds.indexOf(i)!=-1){
                      config.push({
                       source: i+"_source",
                       target: i+"_desc",
                        endpoint: 'Rectangle',
                         connector: ['Straight'],
                      })
                  }
              }
              for(var i of config){
                  self.line.connect(i)
              }
            
          },100)
        }).catch(function(err) {
          
        })
    }
},
created(){
    this.line=jsPlumb;
}
})
js;
$this->registerJs($js);
?>

<div class="Node-form" id="app">

    <div class="get_create_sql" style="display: none;width: 300px;height: 200px;padding: 10px 10px;">

        <div class="form-group" style="position: relative;display: block;">
            <label for="email">表名称</label>
            <input type="email" class="form-control" id="table_name">
        </div>
        <div class="form-group" style="position: relative;display: flex;">
            <input id="foo" type="text" readonly="true" placeholder="创建语句">
            <button class="btn" data-clipboard-action="copy"  data-clipboard-target="#foo" id="copy">Copy</button>
        </div>
        <button type="button" @click="get_create_sql" class="btn btn-info btn-sm" style="height: 40px;position: relative;margin-left:100px;margin-top: 10px;">获取</button>
    </div>
    <div id="show_data" style="display: none">
        <div style="display: flex">
        <table class="table">
            <caption>数据源</caption>
            <thead>
            <tr>
                <th>字段名称</th>
            </tr>
            </thead>
            <tbody>
            <tr >
                <td v-for="i in source_fileds" style="display: flex;position: relative"><p>{{i}}</p> <div :id=i+"_source"></div></td>
            </tr>
            </tbody>
        </table>
        <table class="table">
            <caption>数据去向</caption>
            <thead>
            <tr>
                <th>字段名称</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td  v-for="i in desc_fileds" style="display: flex;position: relative"><div :id=i+"_desc"></div><p>{{i}}</p> </td>
            </tr>
            </tbody>
        </table>
        </div>
    </div>
    <?php $form = ActiveForm::begin(); ?>
<?= $form->field($model, 'id')->hiddenInput(["id"=>"id"])->label(false) ?>
            <?= $form->field($model, 'sql_string')->textarea(['rows' =>12]) ?>
    <?= $form->field($model, 'desc_table')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'source_config')->dropDownList(\backend\modules\tool\models\SqlConfig::GetConfig()) ?>
            <?= $form->field($model, 'desc_config')->dropDownList(\backend\modules\tool\models\SqlConfig::GetConfig()) ?>
<!--            <div style="display: flex">-->
<!--                --><?//= $form->field($model, 'fileds_mapping')->textarea(['rows' =>6]) ?>
<!--                <botton class="btn btn-sm btn-success" @click="get_map" style="height:30px;">映射</botton>-->
<!--            </div>-->
    <div style="display: flex;position:relative;">
        <div style="width: 100%">
        <?= $form->field($model, 'before_sql')->textarea(['rows' =>12]) ?>
        </div>
            <button type="button" class="btn btn-info btn-sm" @click="show" style="height: 40px;margin-top: 100px;">获取建表语句</button>
    </div>

    <?= $form->field($model, 'run_fun')->textarea(['rows' =>10])  ?>
            <?= $form->field($model, 'node_name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'node_desc')->textInput(['maxlength' => true]) ?>

            
    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
