<?php

use app\components\UserPermissions;
use yii\db\Migration;

class m170124_084304_init_rbac extends Migration
{
    public function up()
    {
        /** @var \yii\rbac\ManagerInterface $auth */
        $auth = Yii::$app->authManager;

        $manageProjects = $auth->createPermission(UserPermissions::MANAGE_PROJECTS);
        $manageProjects->description = 'Manage projects';
        $auth->add($manageProjects);

        $manageUsers = $auth->createPermission(UserPermissions::MANAGE_USERS);
        $manageUsers->description = 'Manage users';
        $auth->add($manageUsers);

        $moderator = $auth->createRole('moderator');
        $moderator->description = 'Moderator';
        $auth->add($moderator);
        $auth->addChild($moderator, $manageProjects);

        $admin = $auth->createRole('admin');
        $admin->description = 'Administrator';
        $auth->add($admin);
        $auth->addChild($admin, $manageProjects);
        $auth->addChild($admin, $manageUsers);
    }

    public function down()
    {
        /** @var \yii\rbac\ManagerInterface $auth */
        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }
}
