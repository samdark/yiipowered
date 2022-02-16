<?php

namespace app\checkers;

final class JoomlaGeneratorChecker implements ContentCheckerInterface
{
    public function check(string $content): CheckerResult
    {
        if (preg_match('~<meta name="generator" content="Joomla!~', $content)) {
            $reason = 'Joomla generator meta was found.';
            $result = CheckerResult::NOT_YII;
        } else {
            $reason = 'Joomla generator meta was not found.';
            $result = CheckerResult::UNCERTAIN;
        }

        return new CheckerResult($result, [$reason]);
    }
}