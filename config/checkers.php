<?php

use app\checkers\WordpressAssetsChecker;
use app\checkers\WordpressGeneratorChecker;
use app\checkers\Yii1ScriptsChecker;
use app\checkers\Yii2ScriptsChecker;
use app\checkers\Yii2CSRFChecker;

return [
    Yii1ScriptsChecker::class,
    Yii2ScriptsChecker::class,
    Yii2CSRFChecker::class,
    WordpressGeneratorChecker::class,
    WordpressAssetsChecker::class,
];
