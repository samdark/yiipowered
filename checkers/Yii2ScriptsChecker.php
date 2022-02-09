<?php

namespace app\checkers;

final class Yii2ScriptsChecker implements ContentCheckerInterface
{

    public function check(string $content): CheckerResult
    {
        if (preg_match('~yii\.js|yii\.validation\.js|yii\.activeForm\.js~', $content)) {
            $reason = 'Yii2 scripts were found.';
            $result = CheckerResult::YII_2_0;
        } else {
            $reason = 'Yii2 scripts were not found.';
            $result = CheckerResult::UNCERTAIN;
        }

        return new CheckerResult($result, [$reason]);
    }
}