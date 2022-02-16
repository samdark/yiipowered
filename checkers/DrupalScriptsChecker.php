<?php

namespace app\checkers;

final class DrupalScriptsChecker implements ContentCheckerInterface
{
    public function check(string $content): CheckerResult
    {
        if (preg_match('~drupal\\.js"~', $content)) {
            $reason = 'Drupal scripts were found.';
            $result = CheckerResult::NOT_YII;
        } else {
            $reason = 'Drupal scripts were not found.';
            $result = CheckerResult::UNCERTAIN;
        }

        return new CheckerResult($result, [$reason]);
    }
}