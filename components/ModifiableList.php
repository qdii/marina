<?php
namespace app\components;

use app\components\BootstrapList;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Widget;

class ModifiableList extends Widget
{
    public $bootstrapList;
    public $buttons;

    public function init()
    {
        parent::init();
        $this->bootstrapList->init();
        $this->buttons->init();
    }

    public function run()
    {
        $buttons = $this->buttons->run();
        $this->bootstrapList->items[] = [ 'label' => $buttons ];
        $output = $this->bootstrapList->run();

        return $output;
    }
}

?>
