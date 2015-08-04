<?php
/**
 * Displays a selector for the boat
 *
 * PHP version 5.4
 *
 * @category Components
 * @package  Components
 * @author   Victor Lavaud (qdii) <victor.lavaud@gmail.com>
 * @license  GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://github.com/marina
 *
 */

namespace app\components;
use app\assets\BoatSelectorAsset;
use yii\helpers\Html;
use yii\bootstrap\ButtonDropdown;
/**
 * Displays a selector for the boat
 *
 * PHP version 5.4
 *
 * @category Components
 * @package  Components
 * @author   Victor Lavaud (qdii) <victor.lavaud@gmail.com>
 * @license  GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://github.com/marina
 *
 */

class BoatSelector extends \yii\base\Widget
{
    /**
     * @var array the HTML attributes for the widget container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details
     * on how attributes are being rendered.
     */
    public $options = [];

    /**
     * @var \app\models\Boat The currently-selected boat
     */
    public $selectedBoat = null;

    /**
     * @var array A list of \app\models\Boat to display as selecetion
     */
    public $boats = [];

    /**
     * @var array A event => handler array. Where event is a javascript event
     * and handler is a javascript function that handles it.
     */
    public $clientEvents = [];

    /**
     * @var JsExpression $onBoatSelected a javascript function that is run
     * when a boat is selected. The id of the boat is passed as the first parameter
     */
    public $onBoatSelected = null;

    /**
     * @var JsExpression $onBoatSelected a javascript function that is run
     * when the new boat button is clicked
     */
    public $onNewBoat = null;

    /**
     * Registers the assets for this widget
     *
     * @return void
     */
    protected function registerPlugin()
    {
        BoatSelectorAsset::register($this->getView());
    }

    /**
     * Renders the widget and registers the client scripts.
     *
     * @return string The HTML code for the widget
     */
    public function run()
    {
        $this->registerPlugin();
        $this->registerClientEvents();

        return $this->render();
    }

    /**
     * Adds client-side code to handle events
     *
     * @return void
     */
    public function registerClientEvents()
    {
        $view = $this->getView();
        $myId = $this->getId();

        foreach ($this->clientEvents as $event => $handler) {
            $js[] = "jQuery('#$myId li').on('$event', $handler);";
        }

        if ($this->onNewBoat !== null) {
            $js[] = "jQuery('#$myId-new-boat').on('click', $this->onNewBoat)";
        }

        if ($this->onBoatSelected !== null) {
            $js[] = "jQuery('.$myId-selected-boat').on('click', $this->onBoatSelected)";
        }

        $view->registerJs(implode("\n", $js));
    }

    /**
     * Renders the widget
     *
     * @return string The HTML code for the widget
     */
    public function render()
    {
        $img = Html::img('@web/img/boat.png', ['width' => 26]);
        foreach ( $this->boats as $item ) {
            $info = [
                'label'   => $item->name,
                'url'     => '#',
                'options' => [
                    'data-id' => $item->id,
                    'class' => $this->getId() . '-selected-boat',
                ],
            ];

            // prevent selecting the currently selected boat
            if ($item->id == $this->selectedBoat->id) {
                Html::addCssClass($info['options'], 'disabled');
            }

            $items[] = $info;
        }

        // separator before the new boat option
        $items[]
            = [
                'label'   => '',
                'url'     => '#',
                'options' => [
                    'class' => 'divider'
                ],
            ];

        // option to create a new boat
        $plusIcon = Html::tag(
            'span',
            '',
            [ 'class' => 'glyphicon glyphicon-plus', 'aria-hidden' => 'true' ]
        );

        $items[]
            = [
                'label'   => $plusIcon,
                'url'     => '#',
                'encode'  => false,
                'options' => [
                    'id'      => $this->getId() . '-new-boat',
                ],
            ];

        $dropdownOpts = [
            'label'       => $img,
            'encodeLabel' => false,
            'options'     => [ 'class' => 'btn-default btn-lg' ],
            'dropdown'    => [ 'items' => $items ],
            'id'          => $this->getId() . '-button',
        ];

        $html = [
            Html::beginTag(
                'div',
                [
                    'class' => 'page-header',
                    'id' => $this->getId()
                ]
            ),
            Html::beginTag('h1'),
            ButtonDropdown::widget($dropdownOpts) . ' ' . $this->selectedBoat->name,
            Html::endTag('h1'),
            Html::endTag('div'),
        ];

        return implode($html, "\n");
    }
}
