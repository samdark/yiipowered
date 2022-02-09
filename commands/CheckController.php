<?php

namespace app\commands;

use app\checkers\CheckerService;
use Yii;
use yii\console\Controller;

final class CheckController extends Controller
{
    public function actionUrl(string $url)
    {
        /** @var CheckerService $checker */
        $checker = Yii::$app->checker;
        $result = $checker->check($url);

        $this->stdout(sprintf("It is %s because:\n", $result->getResult()));
        $this->stdout('- ' . implode("\n- ", $result->getReasons()) . "\n");
    }
}