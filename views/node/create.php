<?php

/* @var $this yii\web\View */
/* @var $model backend\modules\tool\models\Node */

$this->title = '创建节点配置';
?>
<div class="Node-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
