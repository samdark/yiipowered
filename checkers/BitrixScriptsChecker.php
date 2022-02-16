<?php

namespace app\checkers;

final class BitrixScriptsChecker implements ContentCheckerInterface
{
    public function check(string $content): CheckerResult
    {
        if (preg_match('~bitrix(?:\\.info/|/js/main/core)~', $content)) {
            $reason = 'Bitrix scripts were found.';
            $result = CheckerResult::NOT_YII;
        } else {
            $reason = 'Bitrix scripts were not found.';
            $result = CheckerResult::UNCERTAIN;
        }

        return new CheckerResult($result, [$reason]);
    }
}