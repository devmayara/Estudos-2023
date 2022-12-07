<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class HelloBeginEndWidget extends Widget
{
    public $encode = false;

    public function init()
    {
        parent::init();
        ob_start();
        
    }   

    public function run()
    {
        $content = ob_get_clean();

        if($this->encode)
            return Html::encode($content);
        else
            return $content;
    }
}