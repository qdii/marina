<?php
/**
 * Helps with authenticating users
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

use \app\models\User;
use \app\models\Auth;
use \Yii;

class AuthHelper
{
    /**
     * Creates a new user and authenticates him. Use a random password
     *
     * @param string $login  The user's login
     * @param string $email  The user's email
     * @param string $client An identifier for a client (like, facebook)
     * @param string $id     The id of the user given by the client
     *
     * @return bool true on success
     */
    public function createNewUserAndAuthenticate($login, $email, $client, $id)
    {
        assert(is_string($email));
        assert(is_string($login));
        assert(is_string($client));
        assert(is_string($id));

        $pwd     = \Yii::$app->security->generateRandomString(12);
        $token   = \Yii::$app->security->generateRandomString(12);

        // creates the new user
        $user = new User([
            'username'    => $login,
            'email'       => $email,
            'accessToken' => $token,
            'password'    => Yii::$app->security->generatePasswordHash($pwd)
        ]);

        $transaction = $user->getDb()->beginTransaction();
        if (!$user->save()) {
            throw new \Exception('Cannot create new user');
        }

        $auth = new Auth([
            'user'  => $user->id,
            'src'   => $client,
            'srcid' => $id,
        ]);

        if (!$auth->save()) {
            throw new \Exception('Cannot authenticate new user');
        }

        $transaction->commit();
        return true;
    }
}
