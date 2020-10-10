<?php
\backend\modules\tool\Assets\LayuiBundle::register($this);
$js=<<<js
layui.use('form', function(){
  var form = layui.form;
  
  //监听提交
  form.on('submit(formDemo)', function(data){
    layer.msg(JSON.stringify(data.field));
    return false;
  });
});
js;
$this->RegisterJs($js);
$css=<<<css
.layui-form-switch{
height:25px !important;
}
.layui-input-block{
margin-left: -10px;
}
.layui-form-label{
width: 60px;
padding: 10px 16px;
white-space: nowrap;
}
css;
$this->registerCss($css);
?>
<div class="layui-form-item layui-form-text" style="display: block !important;">
    <label class="layui-form-label"><?=$title??'开关' ?></label>
    <div class="layui-input-block">
        <input type="checkbox" name="<?=$model->formName()."[".$filed."]"?>" lay-skin="switch" lay-text="ON|OFF">
    </div>
</div>
