<?php

namespace app\components\object;

use app\models\Project;
use yii\base\InvalidValueException;

class ClassType
{
    const PROJECT = 'project';

    public static $classes = [
        self::PROJECT => Project::class
    ];

    /**
     * @param string $type
     *
     * @return string
     * @throws \yii\base\InvalidValueException
     */
    public static function getClass($type)
    {
        if (array_key_exists($type, static::$classes)) {
            return static::$classes[$type];
        }

        throw new InvalidValueException("Object type \"{$type}\" was not found.");
    }
}
