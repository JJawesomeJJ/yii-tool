<?php

/* @var $this yii\web\View */
/* @var $model backend\modules\tool\models\FillForTemplate */

$this->title = '创建填报模版template';
?>
<div class="fill-for-template-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
