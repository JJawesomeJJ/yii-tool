<?php

/* @var $this yii\web\View */
/* @var $model backend\modules\tool\models\SqlConfig */

$this->title = '创建数据源';
?>
<div class="SqlConfig-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
