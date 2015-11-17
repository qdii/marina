<?php
/**
 * Test the DbCache object.
 *
 * PHP version 5.4
 *
 * @category Tests
 * @package  Tests
 * @author   Victor Lavaud (qdii) <victor.lavaud@gmail.com>
 * @license  GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://github.com/marina
 */

use \app\components\DbCache;
/**
 * TestCase for DbCache
 *
 * @category Tests
 * @package  Tests
 * @author   Victor Lavaud (qdii) <victor.lavaud@gmail.com>
 * @license  GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://github.com/marina
 */
class DbCacheTest extends \yii\codeception\DbTestCase
{
    /**
     * Tries and retrieve one object and hope it is not null.
     *
     * @return void
     */
    public function testRetrievingOneObject()
    {
        $dbCache = new DbCache;
        $user = $dbCache->findOne('\app\models\User', 1);

        // User of id 1 is the admin, it has to be present.
        $this->assertNotNull($user);
        $userId = $user->id;
        $this->assertEquals(1, $userId);
    }

    /**
     * Tries and retrieve multiple objects and hope it is not null.
     *
     * @return void
     */
    public function testRetrievingManyObjects()
    {
        $dbCache = new DbCache;
        $users = $dbCache->findAll('\app\models\User', [1, 2]);

        $this->assertNotNull($users);
        $this->assertNotEmpty($users);

        foreach ($users as $user) {
            $userId = $user->id;
            $this->assertTrue($userId == 1 || $userId == 2);
        }
    }

}
