<?php

namespace app\checkers;

final class Yii1ScriptsChecker implements ContentCheckerInterface
{
    public function check(string $content): CheckerResult
    {
        if (preg_match('~jquery\.yii\.js|jQuery\.yii\.submitForm~', $content)) {
            $reason = 'Yii1 scripts were found.';
            $result = CheckerResult::YII_1_1;
        } else {
            $reason = 'Yii1 scripts were not found.';
            $result = CheckerResult::UNCERTAIN;
        }

        return new CheckerResult($result, [$reason]);
    }
}