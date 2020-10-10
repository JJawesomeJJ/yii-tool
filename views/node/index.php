<?php

use yii\helpers\Html;
use yii\helpers\Url;
use dsj\components\grid\ResponsiveGridView;
use yii\widgets\ActiveForm;
use backend\modules\tool\models\Node;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model backend\modules\tool\models\Node */
\backend\modules\tool\Assets\SeachBundle::register($this);
\backend\modules\tool\Assets\VueBundle::register($this);
\backend\modules\tool\Assets\BaseBundle::register($this);
$source_config=\backend\modules\tool\helpers\ArrayHelper::array_parse_key_value(backend\modules\tool\models\SqlConfig::find()->select("id,source_name")->asArray()->all(),"id","source_name");
$SQL_URL=Url::to(["parse-sql"]);
$this->title = '节点配置';
$this->params['breadcrumbs'][] = $this->title;
$js=<<<js
new Vue({
el:"#app",
data:{
    page_data:{
        "data":[
            
        ],
        "total":0,
        "current_page":5,
        "total_page":1
    },
    current_page:1,
    showPages: 	5,
	totalPages: 20,
	id:null,
},
watch: {
  current_page: {
    handler(newName, oldName) {
        if(newName==null){
            return;
        }
        if(newName==oldName){
            return;
        }
        if(this.id==null){
            return;
        }
        this.get_data(this.id,this.current_page)
    },
    // 代表在wacth里声明了firstName这个方法之后立即先去执行handler方法
    immediate: true
  }
},
components: {
			'pagination': pagination
		},
methods:{
    get_form_title(){
      if(this.page_data['data'].length>0){
          return Object.keys(this.page_data['data'][0]);
      }
      return [];
    },
  get_data(id,page=1){
        this.id=id;
        var self=this;
      axios.get('{$SQL_URL}'+"&id="+id+"&page="+page)
  .then(function (response) {
      self.page_data=response.data;
      if(page!=1){
          return;
      }
      layer.open({
        type: 1,
        content: $('#show_data'), //这里content是一个DOM，注意：最好该元素要存放在body最外层，否则可能被其它的相对元素所影响
          area: ['1200px', '600px'], //宽高
        });
    console.log(response);
  })
  .catch(function (error) {
    console.log(error);
  });
  }
  }
,
created(){
    var self=this;
    setInterval(function() {
        if(self.current_page!=$("#current_page").text()){
            // alert($("#current_page").text());
      self.current_page=$("#current_page").text();
      }
    },10);
},
})
js;
$this->registerJs($js);
//print_r(Node::GetTypeSelect("source_config"));die();
function get_config($filed){
    $data=\common\functions\ArrayUtil::array_parse_key_value(\backend\modules\tool\models\SqlConfig::find()
        ->select("source_name,id")
        ->andWhere(['in','id',array_values(Node::GetTypeSelect($filed))])
        ->asArray()
        ->all(),"id","source_name");
    $result=[""=>""];
    foreach ($data as $key=>$value){
        $result[$key]=$value;
    }
    return $result;
}
?>
<div class="Node-index" id="app">
    <div id="show_data" style="display: none">
        <table class="table">
            <caption>数据源</caption>
            <thead>
            <tr>
                <th  v-for="i in get_form_title()">{{i}}</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="i in page_data['data']">
                <td v-for="name of i">{{name}}</td>
            </tr>
            </tbody>
        </table>
        <pagination :show-pages="showPages" :curPage="5" :total-pages="page_data['total_page']" ref="pagination"></pagination>
        <p>{{this.$refs.curPage}}</p>
    </div>
    <?= \backend\modules\tool\helpers\widgets\ExcelButton::widget([])?>
<?php $form = ActiveForm::begin(['method'=>'get']); ?>
<?= $form->field($model, 'node_name')->textInput(['maxlength' => true]); ?>
    <?= $form->field($model, 'source_config')->dropDownList(get_config("desc_config")) ?>
    <?= $form->field($model, 'desc_config')->dropDownList(get_config("source_config"))?>
    <?= $form->field($model, 'desc_table')->textInput(['maxlength' => true]) ?>
<!--    --><?//= $form->field($model, 'source_conifg')->dropDownList(\backend\modules\tool\models\SqlConfig::find()->select()) ?>
<?= Html::submitButton('搜索', ['class' => 'btn btn-primary btn-search']) ?>
    <button type="button" class="btn btn-default reload">重置</button>
<?php ActiveForm::end(); ?>
    <?= ResponsiveGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'node_name',
            [
                'attribute' => 'sql_string',
                'label' => 'SQL源语句',
                'value' => function($model)use($source_config){
                    return mb_substr($model->sql_string,0,30)."...";
                }
            ],
            [
                'attribute' => 'source_config',
                'label' => '数据来源',
                'value' => function($model)use($source_config){
                    return $source_config[$model->source_config];
                }
            ],
            [
                'attribute' => 'desc_config',
                'label' => '数据去向',
                'value' => function($model)use($source_config){
                    return $source_config[$model->desc_config];
                }
            ],
            'fileds_mapping',
//            'before_sql',
            'node_desc',

            ['class' => 'dsj\components\grid\ResponsiveActionColumn',
             'template'=> '{parse-sql} {add-task} {create} {update} {view} {delete} {switch}',
                    'buttons' => [
                        'parse-sql' => function ($url, $model, $key) {
                            return "<button type='button' @click='get_data({$model->id})' class='btn btn-sm btn-success'>查看数据</button>";
                        },
                        'add-task'=>function($url, $model, $key){
                            return sprintf("<button type=\"button\" class=\"btn btn-primary btn-sm data-view\" title=\"查看\" aria-label=\"查看\" data-pjax=\"0\" url='%s'>节点运行配置</button>",Url::to(["add-task"])."&task_id=".$model->id);
                        },
                    ]],],
    ]); ?>
</div>
