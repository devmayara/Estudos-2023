<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class HelloWidget extends Widget
{
    public $message;
    public $submessage;

    public function init()
    {
        parent::init();

        if($this->message === null)
            $this->message = 'Hello Word';

        if($this->submessage === null)
            $this->submessage = "I'm here!";
    }   

    public function run()
    {
        $output = "<h2>". Html::encode($this->message) ."</h2>";
        $output .= "<p>". Html::encode($this->submessage) ."</p>";

        return $output;
    }
}
