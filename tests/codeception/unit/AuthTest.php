<?php
/**
 * Test around authentication.
 *
 * PHP version 5.4
 *
 * @category Tests
 * @package  Tests
 * @author   Victor Lavaud (qdii) <victor.lavaud@gmail.com>
 * @license  GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://github.com/marina
 */

use \app\models\User;
use \app\models\Auth;

/**
 * TestCase for AuthHelper
 *
 * @category Tests
 * @package  Tests
 * @author   Victor Lavaud (qdii) <victor.lavaud@gmail.com>
 * @license  GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://github.com/marina
 */
class AuthHelperTest extends \yii\codeception\DbTestCase
{
    public function testCreateNewUserAndAuthenticate()
    {
        $helper = new \app\components\AuthHelper;
        $nuser  = User::find()->count();
        $nauth  = Auth::find()->count();

        $user = $helper->createNewUserAndAuthenticate(
            "abcdefaui",
            "sth@truc.bar",
            "facebook",
            "123123123123"
        );

        $this->assertNotNull($user);
        $this->assertEquals($nuser+1, User::find()->count());
        $this->assertEquals($nauth+1, Auth::find()->count());
    }
}

