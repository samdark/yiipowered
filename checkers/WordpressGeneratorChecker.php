<?php

namespace app\checkers;

final class WordpressGeneratorChecker implements ContentCheckerInterface
{
    public function check(string $content): CheckerResult
    {
        if (preg_match('~<meta name="generator" content="WordPress~', $content)) {
            $reason = 'Wordpress generator meta was found.';
            $result = CheckerResult::NOT_YII;
        } else {
            $reason = 'Wordpress generator meta was not found.';
            $result = CheckerResult::UNCERTAIN;
        }

        return new CheckerResult($result, [$reason]);
    }
}