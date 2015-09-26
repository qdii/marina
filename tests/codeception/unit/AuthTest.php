<?php
/**
 * Unit test for the class AuthHelper
 */

use \app\models\User;
use \app\models\Auth;

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

