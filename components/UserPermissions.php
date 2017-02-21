<?php


namespace app\components;


use app\models\Project;
use app\models\User;

class UserPermissions
{
    const MANAGE_PROJECTS = 'manage_projects';
    const MANAGE_USERS = 'manage_users';

    public static function canManageProjects()
    {
        if (\Yii::$app->user->isGuest) {
            return false;
        }

        if (\Yii::$app->user->can(self::MANAGE_PROJECTS)) {
            return true;
        }

        return false;
    }

    public static function canManageProject(Project $project)
    {
        if (\Yii::$app->user->isGuest) {
            return false;
        }

        if (self::canManageProjects()) {
            return true;
        }

        $currentUserID = \Yii::$app->user->getId();

        $users = $project->users;
        foreach ($users as $user) {
            if ((int)$user->id === (int)$currentUserID) {
                return true;
            }
        }

        return false;
    }

    public static function canManagerUsers()
    {
        if (\Yii::$app->user->isGuest) {
            return false;
        }

        if (\Yii::$app->user->can(self::MANAGE_USERS)) {
            return true;
        }

        return false;
    }

    public static function canManageUser(User $user)
    {
        if (\Yii::$app->user->isGuest) {
            return false;
        }

        $currentUserID = \Yii::$app->user->getId();

        if ((int)$user->id === $currentUserID) {
            return true;
        }

        if (self::canManagerUsers()) {
            return true;
        }

        return false;
    }
}
