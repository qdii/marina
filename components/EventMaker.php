<?php
/**
 * Creates a fullcalendar 2 event from input data
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

use \app\models\Meal;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * Creates a fullcalendar 2 event from input data
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
class EventMaker
{
    private $_dessertById;
    private $_firstById;
    private $_secondById;
    private $_drinkById;
    private $_userById;

    /**
     * Constructs an EventMaker
     *
     * @param array $dessertById An array of desserts indexed by their id
     * @param array $firstById   An array of firsts indexed by their id
     * @param array $secondById  An array of seconds indexed by their id
     * @param array $drinkById   An array of drinks indexed by their id
     * @param array $userById    An array of users indexed by their id
     *
     * @return EventMaker A new EventMaker object
     */
    public function __construct(
        $dessertById,
        $firstById,
        $secondById,
        $drinkById,
        $userById
    ) {
        $this->_dessertById = $dessertById;
        $this->_firstById = $firstById;
        $this->_secondById = $secondById;
        $this->_drinkById = $drinkById;
        $this->_userById = $userById;
    }

    /**
     * Creates the text of a fullcalendar event
     *
     * @param app\models\Meal $meal The meal to extract the information of
     *
     * @return string The text of the fullcalendar event
     */
    public function getTitleFromMeal(\app\models\Meal $meal)
    {
        $user         = $this->_userById   [ $meal->cook ];
        $firstCourse  = $this->_firstById  [ $meal->firstCourse ];
        $secondCourse = $this->_secondById [ $meal->secondCourse ];
        $dessert      = $this->_dessertById[ $meal->dessert ];
        $drink        = $this->_drinkById  [ $meal->drink ];

        $list = [
            $firstCourse->name,
            $secondCourse->name,
            $dessert->name,
            $drink->name
        ];

        return 'Cuisinier : ' . $user->username . '<br/>'
               . $meal->nbGuests . ' personne(s)' .  Html::ul($list);
    }

    /**
     * Creates a fullcalendar event from a meal
     *
     * @param app\models\Meal $meal The meal to extract the information of
     *
     * @return mixed A fullcalendar object containing info about the meal
     */
    public function getEventFromMeal(\app\models\Meal $meal)
    {
        $isLunch = $meal->type == 'lunch';

        return [
            'id'    => $meal->id,
            'title' => $this->getTitleFromMeal($meal),
            'start' => $meal->date . "T" . ( $isLunch ? "13:00:00Z" : "19:00:00Z" ),
            'end'   => $meal->date . "T" . ( $isLunch ? "16:00:00Z" : "22:00:00Z" ),
            'backgroundColor'   => '#2a4f6e'
        ];
    }

    /**
     * Produces a bilan in terms of proteins, calories, etc.
     *
     * @param array $meals The meals which to make a bilan from
     *
     * @return string A text giving the full bilan of the meals
     */
    public function getIntakeBilan($meals)
    {
        $computer = new PriceComputer;
        $properties = [ 'protein', 'energy_kcal' ];

        $list = [];
        foreach ( $properties as $prop ) {
            $val = round($computer->getIntakeOfMeals($meals, $prop), 1);
            $list[]  = $prop . ': ' . $val . 'g';
        }

        return 'Daily intake per person:<br/>' . Html::ul($list);
    }

    /**
     * Produces an array of events which includes meals and bilans
     *
     * @param array $meals The meals to fetch the information from
     *
     * @return $array An array of fullcalendar events
     */
    public function getEventsAndBilanFromMeals($meals)
    {
        $bilans       = [];
        $events       = [];
        $mealsPerDate = [];

        foreach ( $meals as $meal ) {
            // sort the meals per date to create daily bilans later
            $mealsPerDate[$meal->date][] = $meal;

            $events[] = $this->getEventFromMeal($meal);
        }

        foreach ( $mealsPerDate as $date => $mealsOnThisDate ) {
            $bilans[] = [
                'title' => $this->getIntakeBilan($mealsOnThisDate),
                'start' => $date . "T" . "23:58:00Z",
                'end'   => $date . "T" . "23:59:00Z",
                'backgroundColor'   => '#267257',
            ];
        }

        return array_merge($bilans, $events);
    }
}
