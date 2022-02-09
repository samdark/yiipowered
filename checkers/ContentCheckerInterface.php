<?php

namespace app\checkers;

interface ContentCheckerInterface
{
    public function check(string $content): CheckerResult;
}
