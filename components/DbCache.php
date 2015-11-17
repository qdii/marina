<?php
/**
 * Enables caching object
 *
 * PHP version 5.4
 *
 * @category Components
 * @package  Components
 * @author   Victor Lavaud (qdii) <victor.lavaud@gmail.com>
 * @license  GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://github.com/marina
 */
namespace app\components;

/**
 * Database cache
 *
 * @category Components
 * @package  Components
 * @author   Victor Lavaud (qdii) <victor.lavaud@gmail.com>
 * @license  GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://github.com/marina
 */
class DbCache
{
    /**
     * Retrieves a single object from the cache.
     *
     * @param string $classname The name of the class to retrieve.
     * @param int    $objectId  The id of the object to retrieve.
     *
     * @return mixed Either null, or an object of class $classname.
     */
    public function findOne($classname, $objectId)
    {
        $cache = \Yii::$app->cache;
        $key   = $this->_getKey($classname, $objectId);

        // Updates the cache if the entry is absent.
        if ($cache[$key] == null) {
            $this->retrieve($classname, [$objectId]);
        }

        $val = $cache[$key];

        return $val;
    }

    /**
     * Retrieves multiple objects from the cache.
     *
     * @param string $classname The name of the class to retrieve.
     * @param int    $objIds    The ids of the objects to retrieve.
     *
     * @return mixed Either null, or an object of class $classname.
     */
    public function findAll($classname, $objIds)
    {
        $cache = \Yii::$app->cache;
        $vals  = [];
        $unknownIds = [];

        foreach ($objIds as $objectId) {
            $key = $this->_getKey($classname, $objectId);
            $val = $cache[$key];

            if ($val != null) {
                $vals[] = $val;
                continue;
            }

            $unknownIds[] = $objectId;
        }

        $this->retrieve($classname, $unknownIds);
        foreach ($unknownIds as $objectId) {
            $key = $this->_getKey($classname, $objectId);
            $vals[] = $cache[$key];
        }

        return $vals;
    }

    /**
     * Updates the cache with a value taken from the database.
     *
     * @param string $classname The name of the class to fetch.
     * @param array  $ids       An array of the ids to fetch.
     *
     * @return void
     */
    public function retrieve($classname, $ids)
    {
        $cache = \Yii::$app->cache;
        $vals  = $classname::find()->where(['id' => $ids])->all();

        foreach ($vals as $val) {
            $id = $val->id;
            $key = $this->_getKey($classname, $id);
            $cache[$key] = $val;
        }
    }

    /**
     * Computes a key for the cache object
     *
     * @param string $classname The class of the object to cache.
     * @param string $objectId  The id of the object to cache.
     *
     * @return mixed An object to be used as a key.
     */
    private function _getKey($classname, $objectId)
    {
        return [
            $classname,
            $objectId
        ];
    }
}
