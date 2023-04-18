<?php

namespace console\controllers;

use common\models\User;
use common\services\User\CreateUserService;
use Faker\Factory;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\console\widgets\Table;
use yii\di\NotInstantiableException;
use yii\helpers\Console;

class RbacController extends Controller
{
    /**
     * @throws Exception
     * @throws \Exception
     */
    public function actionInit(): void
    {
        $auth = Yii::$app->authManager;

        $readCalendar = $auth->createPermission('readCalendar');
        $readCalendar->description = 'Read the calendar';
        $auth->add($readCalendar);

        $updateCalendar = $auth->createPermission('updateCalendar');
        $updateCalendar->description = 'Update the calendar';
        $auth->add($updateCalendar);

        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $readCalendar);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updateCalendar);
        $auth->addChild($admin, $user);
    }

    /**
     * @throws NotInstantiableException
     * @throws InvalidConfigException
     * @throws \Exception
     */
    public function actionCreateAdmin(string $username, string $email, string $password): void
    {
        $authManager = Yii::$app->authManager;
        $container = Yii::$container;

        $createUserService = $container->get(class: CreateUserService::class);

        $user = $createUserService->create($username, $email, $password);

        $adminRole = $authManager->getRole('admin');

        $authManager->assign(role: $adminRole, userId: $user->id);
    }

    /**
     * @throws NotInstantiableException
     * @throws InvalidConfigException
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionCreateUsers(int $count): void
    {
        $authManager = Yii::$app->authManager;
        $container = Yii::$container;
        $faker = Factory::create();

        $createUserService = $container->get(class: CreateUserService::class);

        $userRole = $authManager->getRole('user');

        $usersData = [];
        for ($i = 0; $i < $count; $i++) {
            $userData = [
                'username' => $faker->unique()->userName(),
                'email' => $faker->unique()->email(),
                'password' => $faker->password(),
            ];

            try {
                $user = $createUserService->create(...$userData);
            } catch (\Exception) {
                $i--;

                continue;
            }

            $usersData[] = $userData;

            $authManager->assign(role: $userRole, userId: $user->id);
        }

        $this->stdout("Generated Users: \n", Console::FG_GREEN);
        echo Table::widget([
            'headers' => ['Username', 'Email', 'Password'],
            'rows' => $usersData,
        ]);
    }
}