<?php
use yii\helpers\Html;
use yii\helpers\Url;
use dsj\components\grid\ResponsiveGridView;
use backend\modules\tool\models\FillFormModel;
use backend\modules\tool\helpers\Migration;
use yii\widgets\ActiveForm;
\backend\modules\tool\Assets\SwitchBundle::register($this);
\backend\modules\tool\Assets\VueBundle::register($this);
\backend\modules\tool\Assets\BaseBundle::register($this);
\backend\modules\tool\Assets\IndexAssets::register($this);
//\backend\modules\tool\Assets\LayuiBundle::register($this);
$type=Migration::accept();//目前支持的数据库类型
$componment=\backend\modules\tool\helpers\Componmet::GetWigetName();//表单类型
$fileds=json_encode($type);
$menu_path=Url::to(["menu"]);
$componment_comment=json_encode($componment);//表单提交的类型
$params_comment=json_encode(Migration::$params_comment);//字段的默认值
$fileds_params=json_encode(Migration::GetFiledsParams());
$search_wdiget=json_encode(\backend\modules\tool\helpers\Componmet::GetWidgetSearch());
$url=Url::to(["easy"]);
$css= <<<css
#post{
display: flex;
flex-wrap: wrap;
width: 1400px;
margin-right: 100px;
}
.filed{
position: relative;
width: 300px;
padding: 5px;
margin-left: 10px;
border: 1px solid lightgrey;
transition: 0.3s ease-in-out;
animation: show 0.1s ease-in-out;
}
@keyframes show {
0%{transform: scale(0.1)}
100%{transform: scale(1)}
}
.remove{
position: absolute;
right: 5px;
top:5px;
padding: 2px 8px;
background-color: #ff1037;
color: white;
cursor: pointer;
}
.config{
position: absolute;
right: 50px;
top:5px;
padding: 2px 8px;
background-color: deepskyblue;
color: white;
cursor: pointer;
}
.search_filter{
position: absolute;
right: 137px;
top:5px;
padding: 2px 8px;
background-color: limegreen;
color: white;
cursor: pointer;
}
.filed:hover{
box-shadow: 2px 1px 1px lightgrey;
}
.right_result{
position: fixed;
color: white;
right: 10px;
width: 400px;
border: 1px solid lightgrey;
padding: 10px;
background-color: #c0c4cc;
font-size: 20px;
height: 90vh;
top:-10px;
margin-left: 10px;
}
#widget{
border: 1px solid #1c2d3f;
padding: 5px 5px;
}
.post{
padding: 10px;
}
.warning{
border:1.5px solid orange;
}
.sql_params{
transition: 1s;
}
.search_config{
position: relative;
width: 325px;
padding: 5px 5px;
margin: 0 auto;
}
.search_config_item{
position: relative;
}
.search{
border:2px solid limegreen;
}
css;
$this->registercss($css);
$this->title = '导向构建填报工具';
$js=<<<js
function get_object_1(obj) {
  for (var i in obj){
      return obj[i];
  } 
}
new Vue({
el:"#app",
data:{
    fileds:[],
    fileds_type:{$fileds},
    componment:{$componment_comment},
    unique:false,
    menu:null,
    fileds_rules:{$params_comment},//表单字段的默认值
    fileds__params:{$fileds_params},
    modelname:"",
    title:"",
    menu_id:null,
    namespace:'backend\\\modules\\\\newfillform',
    search_widget:{$search_wdiget},
    search:{
        filter:null,
        widget:null,
    },
    current_search:{"\u7cbe\u786e\u5339\u914d":{"regx":"=","widget":["textInput"]},"\u65f6\u95f4\u6bb5\u5339\u914d":{"regx":"=","widget":["date"]}}
},
updated(){
    $("input").each(function() {
      if($(this).val()==""){
          $(this).addClass("warning");
      }else {
          if($(this).parent().attr("data-target")=="sql_params"){
               $(this).addClass("reqiured");
          }
      }
    });
    $("select").each(function() {
      if($(this).find('option:selected').text()==""){
              $(this).addClass("warning");
      }else {
          if($(this).parent().attr("data-target")=="sql_params"){
               $(this).addClass("reqiured");
          }
      }
    });
},
created(){
      $("body").on("blur","input",function() {
      if($(this).val()!=""){
          $(this).removeClass("warning")
      }
    });
      $("body").on("change","select",function() {
            $(this).removeClass("warning");
      });
},
mounted(){
    var self=this;
    this.init();
     $("body").on("click","span",function(){
        if($(this).attr("id").indexOf("ztreeBox")!=-1){
            $("#input_ztreeBox").val($(this).text())
            self.menu=$(this).prev().attr('data-src');
        }
    });
    setTimeout(function() {
      $('#type').bootstrapSwitch({    //初始化按钮
       onText:"开启",
       offText:"关闭",
       onColor:"success",
       offColor:"info",
       size:"small",
       onSwitchChange:function(event,state){
          if(state==true){
               self.checked=true;
             }else{
               self.checked=false;
             }
         }
    });
    },100);
    // this.get_menu();
},
methods:{
    init(){
                                (new ztreeModal({
                            dropDom:$('#ztreeModal'),
                            direction:'up',//up 上/down 下
                            fun:function(eId){
                                /*初始化ztree*/
                                var obj = this;
                                this.objTree = $.fn.zTree.init($('#'+eId),{
                                check: {
                                    enable : false,
                                    chkStyle: "checkbox"
                                },
                                data : {
                                    simpleData: {
                                        enable: true
                                    }
                                },
                                callback:{
                                    beforeClick:function(treeId, treeNode){
                                        var check = (treeNode && !treeNode.isParent);
                                        if (!check) 
                                        return false;
                                    },
                                    onClick: function (e, treeId, treeNode, clickFlag) { 
                                             var treeObj = $.fn.zTree.getZTreeObj(eId),
                                                 nodes = treeObj.getSelectedNodes();
                                                var unitNode = nodes[0].getParentNode();
                                                var buildingNode=unitNode.getParentNode();
                                                var houseName=buildingNode.name+unitNode.name+nodes[0].name;
                                                 
                                            if(nodes[0].level == 2){
                                                obj.hideZtree(houseName);//隐藏下拉菜单
                                            }
                                        } 
                                    }
                            }, {$data});
                        }
                    }));
			    },
    check(){
        this.checked=!this.checked;
    },
    get_menu(){
        axios.get("{$menu_path}", Qs.stringify({
             "_csrf-backend":$("input[name='_csrf-backend']").val()
          })).then(function(res) {
            console.log(res)
          }).catch(function(err) {
            
          })
    },
    add(){
        var self=this;
        var index=layer.open({
        type: 1,
        shade: false,
        area: ['300px', '200px'],
        title: "选择字段类型", //不显示标题
        content: $('.choose_type'), //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
         btn: ['确定', '取消'],
        btn1:function(index) {
            var type=$("#type_choose option:selected").val();
            var data=deepCopy(self.fileds_type[type]);
            data['fun']=type;
            // data['key']="";//设置键
            var key_params={};
            data['key_params']={};
            data.hidden=false;
            for(var i of self.fileds__params[type]){
                var param_value=self.get_default(i,data);
                console.log(param_value);
                if(param_value instanceof Array){
                    data['key_params'][i]=param_value[0];
                }
                else {
                    data['key_params'][i]=param_value;
                }
            }
            // console.log(data)
            // data['inputty']
            // console.log(get_object_1(data));
            self.fileds.push(data);
              console.log(self.fileds);
            setTimeout(function() {
                self.config((self.fileds.length-1))
            },10);
            layer.close(index)
        },
        btn2:function(index) {
          layer.close(index);
        }
        });
    },
    get_default(name,object){
        if(object.hasOwnProperty("params")){
            if(object.params.hasOwnProperty(name)){
                return object.params[name];
            }
        }
        if(this.fileds_rules.hasOwnProperty(name)){
           return this.fileds_rules[name]['default'];
        }
    },
    switch_widget(index,target){
        var key=$(target.target).find("option:selected").val();
        this.fileds[index]['widget_params']={};
        for (var i of this.fileds[index]['input'][key]){
            if(i.hasOwnProperty("default")){
            this.fileds[index]['widget_params'][i.name]=i.default;
            }else {
                this.fileds[index]['widget_params'][i.name]="";
            }
        }
        delete this.fileds[index]['search'];
    },
    remove(index){
        var self=this;
        $(".filed").eq(index).animate({'width':'0px'},400);
        setTimeout(function() {
           self.fileds.splice(index,1)
        },405);
    },
    post(){
        var self=this;
        var index=layer.open({
        type: 1,
        shade: false,
        area: ['500px', '600px'],
        title: "生成CRUD", //不显示标题
        content: $('.post'), //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
        btn : ['确定', '取消'],
        btn1:function() {
          axios.post("{$url}", Qs.stringify({
              fileds:self.fileds,
              namespace:self.namespace,
              modelname:self.modelname,
              title:self.title,
              unique:self.checked,
              menu:self.menu,
             "_csrf-backend":$("input[name='_csrf-backend']").val()
          })).then(function(res) {
            
          }).catch(function(err) {
            
          })
        }
    })
    },
    config(index){
        var self=this;
         self.fileds[index]['hidden']=!self.fileds[index]['hidden'];
        $(".filed").eq(index).find(".reqiured").each(function() {
          if(self.fileds[index]['hidden']){
              $(this).hide(100);
              $(this).parent().find("label").hide(100);
          }else {
                $(this).show(100);
                 $(this).parent().find("label").show(100);
          }
        });
    },
    unique_id(i){
        return this.fileds.length+i["fun"];
    },
    find_widget(){
        if(this.current_search.hasOwnProperty(this.search['filter'])){
            return this.current_search[this.search['filter']]['widget']
        }
        return [];
    },
    open_search(index1){
        var self=this;
        if(self.fileds[index1]['type']==null){
            layer.msg("请选择表单控件");
            return;
        }
         if(!self.fileds[index1].hasOwnProperty('search')){
             self.fileds[index1]['search']={filter:null,widget:null}
         }
         if(self.search_widget[self.fileds[index1]['type']].length==0){
             layer.msg("该控件暂时不可用于设置搜索");
              delete fileds[index1]['search'];
              return;
         }
         self.search=self.fileds[index1]['search'];
         self.current_search=self.search_widget[self.fileds[index1]['type']];
         console.log(self.current_search);
        var index=layer.open({
        type: 1,
        shade: false,
        area: ['350px', '300px'],
        title: "设置筛选项", //不显示标题
        content: $('.search_config'), //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
        btn : ['确定','取消设置','返回'],
        btn1:function() {
           self.fileds[index1]['search']=self.search;
           layer.close(index)
        },
        btn2:function() {
            delete self.fileds[index1]['search'];
            layer.close(index);
        },
        btn3:function() {
          layer.close(index);
        }
    });
    },
    
}
})
js;
$this->Registerjs($js);
?>
<div class="main-container" id="app" style="position: relative;">
    <div class="elemet">
        <p>创建填报工具的字段</p>
    </div>
    <?php $form = ActiveForm::begin(); ?>
    <?php ActiveForm::end(); ?>
    <form id="post" style="position: relative;">
        <div v-bind:class="{search:i['search']!=null}" class="filed" v-for="i,index in fileds" :key="i">
            <div class="remove" @click="remove(index)">删除</div>
            <div class="config" v-if="i['hidden']" @click="config(index)">显示隐藏项</div>
            <div class="config" v-if="i['hidden']==false" @click="config(index)">关闭隐藏项</div>
            <div class="search_filter" @click="open_search(index)">
                搜索项
            </div>
            <div class="form-group">
<!--                控件类型-->
                <label for="type">表单控件-{{i['fun']}}</label>
                <select @change="switch_widget(index,$event)" class="form-control" id="type" v-model="i['type']" name="type" >
                    <option v-for="type,type_index in i['input']" :value="type_index">{{componment[type_index]}}</option>
                </select>
<!--                控件类型-->
            </div>
<!--            控件参数-->
            <div class="widget_params" v-if="i['input'][i['type']] instanceof Array&&i['input'][i['type']].length>0" id="widget">
                <div class="item" v-for="widget in i['input'][i['type']]">
                    <div class="form-group">
                        <label for="usr">{{widget['commemt']}}</label>
                        <div class="">
                            <label for="usr"></label>
                            <input type="text" v-model="i['widget_params'][widget['name']]" class="form-control" id="usr">
                        </div>
                    </div>
                </div>
            </div>
<!--            <div class="form-group">-->
<!--                <label for="key">字段名称</label>-->
<!--                <input type="text" v-model="i['key']" class="form-control" id="key" placeholder="请输入字段名称,名称规范符合C语言变量命名规范">-->
<!--            </div>-->
            <div class="form-group" data-target="sql_params" v-for="param,name in i['key_params'] ">
                <label for="key">{{fileds_rules[name]['comment']}}</label>
                <input v-if="fileds_rules[name]['default'] instanceof Array==false" v-model="i['key_params'][name]" type="text" class="form-control" id="key" placeholder="请输入参数">
                <select class="form-control" v-model="i['key_params'][name]" id="type" name="type" v-else >
                    <option v-for="val in fileds_rules[name]['default']">{{val}}</option>
                </select>
            </div>
        </div>
        <div style="display: flex">
            <button style="width: 80px;height: 40px;margin-top: 80px;" type="button" class="btn btn-sm btn-primary" @click="add">添加字段</button>
            <button style="width: 80px;height: 40px;margin-top: 80px;" type="button" class="btn btn-sm btn-success" @click="post">生成CRUD</button>
        </div>
    </form>
    <div class="right_result" style="display: none">
        <p style="width: 100%;text-align: center;font-weight: 800">拟生成界面</p>
    </div>
    <div class="choose_type" style="display: none;margin-top: 40px;">
        <div class="form-group">
            <select class="form-control" id="type_choose" name="type">
                <option :value="index" v-for="i,index in fileds_type">{{i['comment']}}</option>
            </select>
        </div>
    </div>
    <div class="post" style="display: none">
        <form>
            <div class="form-group">
                <label for="email">模块命名空间</label>
                <input type="text" v-model="namespace" class="form-control" id="email">
            </div>
            <div class="form-group">
                <label for="pwd">模型名</label>
                <input type="text" v-model="modelname" class="form-control" id="pwd">
            </div>
            <div class="form-group">
                <label for="pwd">标题</label>
                <input type="text" v-model="title" class="form-control" id="pwd">
            </div>
            <div class="form-group">
                <input id="type" @click="check()" style="margin-right: 5px;" name="unique"  type="checkbox">   仅作为配置页面 当数据量很小的时候使用
            </div>
            选择生成的菜单目录
            <div style="width:250px;padding-top:1px;padding-left:0px;border: 1px solid lightgrey">
                <!--data-ztreeId必填，ztree的id-->
                <div class="ztreeModal" id="ztreeModal" data-ztreeId = 'ztreeBox'></div>
                <div class="ztreeModal" id='ztreeModal01' data-ztreeId = 'ztreeBox01'></div>
            </div>
        </form>
    </div>
    <div class="search_config" style="display: none">
        <div class="search_config_item">
            <div class="form-group">
                <label for="pwd">匹配方式</label>
                <select class="form-control" id="type" v-model="search['filter']" name="type" >
                    <option v-for="i,index in current_search" :value="index">{{i['name']}}</option>
                </select>
            </div>
            <div class="form-group">
                <label for="pwd">搜索控件</label>
                <select class="form-control" id="type" v-model="search['widget']" name="type" >
                    <option v-for="i in find_widget()" :value="i">{{componment[i]}}</option>
                </select>
            </div>
        </div>
<!--        <div class="search_config_item">-->
<!--            精准匹配-->
<!--        </div>-->
    </div>
</div>
