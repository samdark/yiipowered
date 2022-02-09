<?php

namespace app\checkers;

final class Yii2CSRFChecker implements ContentCheckerInterface
{
    public function check(string $content): CheckerResult
    {
        if (preg_match('~meta name=\"csrf-(param|token)\"~', $content)) {
            $reason = 'Yii2 CSRF meta tag was found.';
            $result = CheckerResult::YII_2_0;
        } else {
            $reason = 'Yii2 CSRF meta tag was not found.';
            $result = CheckerResult::UNCERTAIN;
        }

        return new CheckerResult($result, [$reason]);
    }
}