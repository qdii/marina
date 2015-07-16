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
use \app\models\Dish;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * Checks if the dish is valid
 *
 * @param \app\models\Dish $dish The dish to check the validity of
 *
 * @return true if the dish is valid
 */
function isDishValid(Dish $dish)
{
    return $dish->name !== "nothing";
}

/**
 * Returns the name of a dish
 *
 * @param \app\models\Dish $dish The dish we want to extract the name of
 *
 * @return string The name of the dish
 */
function getDishName(Dish $dish)
{
    return $dish->name;
}

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

        $dishes = [ $firstCourse, $secondCourse, $dessert, $drink ];
        $validDishes = array_filter($dishes, '\app\components\isDishValid');
        $list = array_map('\app\components\getDishName', $validDishes);

        return '&#x1f52a; ' . $user->username . '<br/>&#x1f60b; '
               . $meal->nbGuests . Html::ul($list);
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
        $start = $this->_getTimeOfMeal($meal->type);

        return [
            'id'    => $meal->id,
            'title' => $this->getTitleFromMeal($meal),
            'start' => $meal->date . "T" . $start,
            'end'   => $meal->date . "T" . $start,
            'backgroundColor'   => '#2a4f6e'
        ];
    }

    /**
     * Returns the time at which a meal is usually taken
     *
     * @param string $type The type of meal (lunch, breakfast, snack, dinner)
     *
     * @return string A string in format HH:MM:SS followed by the character Z
     */
    private function _getTimeOfMeal($type)
    {
        $time = "00:00:00";

        switch ($type) {
        case 'lunch':
            $time = "13:00:00";
            break;

        case 'dinner':
            $time = "21:00:00";
            break;

        case 'breakfast':
            $time = "09:00:00";
            break;
        }

        return $time . "Z";
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
            $list[]
                = $this->_getNameOfProperty($prop) . ': ' . $val . " " .
                  $this->_getUnityOfProperty($prop);
        }

        return 'Daily intake per person:<br/>' . Html::ul($list);
    }

    /**
     * Sometimes it is more user-friendly to display another
     * name of a property.
     *
     * @param string $property The property to get the name of
     *
     * @return string The name of the property
     */
    private function _getNameOfProperty($property)
    {
        $name = $property;

        switch($property)
        {
        case "energy_kcal":
            $name = "energy";
            break;

        case "energy_kj":
            $name = "energy";
            break;

        default:
            break;
        }

        return $name;
    }

    /**
     * Proteins are expressed in grams, but energy_kcal is in kcal.
     *
     * @param string $property The property to get the unity of
     *
     * @return string The name of the unity
     */
    private function _getUnityOfProperty($property)
    {
        $unity = "g";
        switch($property)
        {
        case "energy_kcal":
            $unity = "kcal";
            break;

        case "energy_kj":
            $unity = "kj";
            break;

        default:
            $unity = "g";
            break;
        }

        return $unity;
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
