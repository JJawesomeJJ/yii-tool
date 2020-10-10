<?php

namespace backend\modules\tool;

/**
 * test module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\tool\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
    public function __construct($id, $parent = null, $config = [])
    {
        parent::__construct($id, $parent, $config);
    }
}
