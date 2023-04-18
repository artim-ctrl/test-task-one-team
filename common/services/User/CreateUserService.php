<?php

namespace common\services\User;

use common\models\User;
use Exception;

class CreateUserService
{
    /**
     * @throws Exception
     */
    public function create(string $username, string $email, string $password, int $status = User::STATUS_ACTIVE): User
    {
        $user = new User();

        $user->username = $username;
        $user->email = $email;
        $user->status = $status;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        if (! $user->save()) {
            throw new Exception('Creating user failed');
        }

        return $user;
    }
}