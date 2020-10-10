<?php
$css=<<<css
#upload_back{
position: absolute;
left: 0;
width: 100%;
height: 100%;
opacity: 0;
}
.img-item{
position: relative;
height: 112px;
}
.img-item img{
/*width: 100%;*/
height: 100%;
width: 100px;
}
#img-container-item{
display: flex;
}
.upload_img_container{
width: 700px;
}
.close{
position: absolute;
right: 0px;
}
css;
$this->registerCss($css);
\backend\modules\tool\Assets\ImageUploads::register($this);
\backend\modules\tool\Assets\BaseBundle::register($this);
?>
<div class="upload_img_container">
    <p><?= $title??'' ?></p>
    <div class="img_upload" style="display: flex">
        <div style="display: flex;position: relative;">
            <img src="<?=$default??\backend\modules\tool\helpers\functions::GetPublicPathUrl().'/img/widget/upload.png'?>" class="defalut" alt="">
            <input onchange="upload(this)" id="upload_back" type="file" accept="image/*" data-src='<?= $default_url."---".$max."---".$name?>' class="img_file_container">
        </div>
        <div class="img_container" id="img-container-item">
            <? if(!empty($model->$filed)):?>
            <?php if(is_string($model->$filed)):?>
            <?php $data=json_decode($model->$filed)?>
                <?php elseif (is_array($model->$filed)):?>
                <? $data=$model->$filed?>
            <?php endif;?>
            <? if(is_array($data)):?>
            <?php foreach ($data as $item):?>
            <div class="img-item">
                <div class=img-item>
                    <div class=close onclick=del_item(this)>
                        <span class="glyphicon glyphicon-remove"></span>
                    </div>
                    <img src=<?=$item?> alt="加载失败" onclick="show_big(this)">
                    <input type=hidden value=<?=$item?> name="<?= $name ?>">
                </div>
            </div>
            <?php endforeach;?>
            <?php endif;?>
            <?php endif;?>
        </div>
    </div>
</div>