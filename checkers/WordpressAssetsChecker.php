<?php

namespace app\checkers;

final class WordpressAssetsChecker implements ContentCheckerInterface
{
    public function check(string $content): CheckerResult
    {
        if (preg_match('~wp-content/themes|wp-includes|wp-json~', $content)) {
            $reason = 'Wordpress assets were found.';
            $result = CheckerResult::NOT_YII;
        } else {
            $reason = 'Wordpress assets were not found.';
            $result = CheckerResult::UNCERTAIN;
        }

        return new CheckerResult($result, [$reason]);
    }
}
