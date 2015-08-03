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
     * Initializes the widget.
     * This method will register the boat asset bundle. If you override this method,
     * make sure you call the parent implementation first.
     *
     * @return void
    */
    public function init()
    {
        parent::init();
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
    }

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
        return $this->render();
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
                    'data-id' => 0,
                ],
            ];


        $dropdownOpts = [
            'label'       => $img,
            'encodeLabel' => false,
            'options'     => [ 'class' => 'btn-default btn-lg' ],
            'dropdown'    => [ 'items' => $items ]
        ];

        $html = [
            Html::beginTag('div', ['class' => 'page-header']),
            Html::beginTag('h1'),
            ButtonDropdown::widget($dropdownOpts) . ' ' . $this->selectedBoat->name,
            Html::endTag('h1'),
            Html::endTag('div'),
        ];

        return implode($html, "\n");
    }
}
