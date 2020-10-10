<?php
\backend\Assets\ImageUpload::register($this);
?>
<style>
    .upload_img_container{
        position: relative;
        width: 400px;
        display: flex;
    }
    .img_upload{
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
    }
    .img_file_container{
        position: absolute;
        left: 0;
        height: 100%;
        opacity: 0;
        width: 100%;
    }
    .defalut{
        width: <?= $width?$width:'auto'?>
        height: <?= $height?$height:'100%'?>;
    }
    .rel_back{
        width: <?= $width?$width:'auto'?>
        height: <?= $height?$height:'100%'?>;
    }
</style>
<div class="upload_img_container">
    <p><?= $title?$title:'' ?></p>
    <div class="img_upload">
        <div style="display: flex;position: relative;">
        <img src="<?=\common\functions\functions::GetOrDefault($default,\common\functions\functions::GetPublicPathUrl().'/img/widget/upload.png')?>" class="defalut" alt="">
        <input type="file" accept="image/*" class="img_file_container" name="<?= $name ?>">
        </div>
        <input type="hidden" id="img_base64">
        <img src="<?= \common\functions\functions::GetOrDefault($path,\common\functions\functions::GetPublicPathUrl().'/img/widget/default.jpg')?>"class="rel_back">
    </div>
</div>
