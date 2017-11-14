<?php


namespace app\components;


class Language
{
    public static function current()
    {
        /** @var array $languages */
        $languages = \Yii::$app->params['languages'];

        foreach ($languages as $language) {
            list($code, $title) = $language;

            if ($code === \Yii::$app->language) {
                return $title;
            }
        }

        return \Yii::t('app', 'Unknown');
    }
}