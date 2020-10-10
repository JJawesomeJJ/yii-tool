<?php
$style=<<<css
.item-tool{
width: 280px;
min-height: 200px;
background-color: black;
list-style: none;
border-radius: 5px;
padding: 5px 5px;
margin:20px;
}
li{
list-style: none;
}
.title{
width: 100%;
margin: 0 auto;
text-align: center;
font-weight: 400;
font-size: 20px;
color: white;
}
.description{
color: white;
margin-top: 20px;
}
.into{
width: 100%;
margin: 0 auto;
color: white;
position: relative;
}
.author{
width: 100%;
margin: 0 auto;
color: white;
position: relative;
margin-top: 40px;
}
.line{
 /*text-overflow: -o-ellipsis-lastline;*/
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  height: 62px;
  -webkit-box-orient: vertical;
}
css;
$js=<<<js
$(".description").click(function() {
  layer.alert($(this).text(), {
    skin: 'layui-layer-lan'
    ,closeBtn: 0,
    title:"工具描述"
    ,anim: 4 //动画类型
  });
});
js;

\backend\Assets\BaseBundle::register($this);
$this->registerCss($style);
$this->registerJs($js);
$this->title="集成后台工具面板"
?>
<?//= $form->field($model, 'content')->widget(\yii\redactor\widgets\Redactor::className(), [
//    'clientOptions' => [
//        'imageManagerJson' => ['/redactor/upload/image-json'],
//        'imageUpload' => ['/redactor/upload/image'],
//        'fileUpload' => ['/redactor/upload/file'],
//        'lang' => 'zh_cn',
//        'rows' => 12,
//        'plugins' => ['clips', 'fontcolor','imagemanager']
//    ]
//]) ?>
<div class="container">
    <div class="pannel">
        <ul style="display: flex">
            <?php foreach ($list as $item):?>
            <li class="item-tool">
                <div class="title">
                    <i class="<?=$item['icon']??'glyphicon glyphicon-stats'?>"></i>
                    <?=$item["name"]?>
                </div>
                <div>
                    <p class="description line" onclick="show('&nbsp; &nbsp; &nbsp; &nbsp;<?=$item["description"]?>')">
                        &nbsp; &nbsp; &nbsp; &nbsp;<?=$item['description']?>
                    </p>
                </div>
                <div class="into">
                    <div style="position: absolute;right: 5px;">
                        <a href="<?=$item["path"]?>"> <i class="glyphicon glyphicon-log-in" style="margin-right: 5px;"></i>程序入口</a>
                    </div>
                </div>
                <div class="author">
                    <div style="position: absolute;right: 5px;">
                        <i class="glyphicon glyphicon-th-large"></i>
                        使用文档
                    </div>
                    <div style="position: absolute;right: 5px;margin-top: 25px;">
                        <i class="glyphicon glyphicon-time"></i>
                        更新时间 <?=$item['update']??''?>
                    </div>

                </div>
            </li>
            <?php endforeach;?>
        </ul>
    </div>
</div>
