<?php

namespace app\checkers;

use Yii;

final class CheckerService
{
    /**
     * @var string[]
     */
    public $checkers = [];

    private $resultMatrix = [
        // current overall result => [
        //     checkerResult => new overall result,
        //     checkerResult => new overall result,
        // ]
        CheckerResult::UNCERTAIN => [
            CheckerResult::UNCERTAIN => CheckerResult::UNCERTAIN,
            CheckerResult::NOT_YII => CheckerResult::NOT_YII,
            CheckerResult::CONFLICT => CheckerResult::CONFLICT,
            CheckerResult::YII => CheckerResult::YII,
            CheckerResult::YII_1_1 => CheckerResult::YII_1_1,
            CheckerResult::YII_2_0 => CheckerResult::YII_2_0,
            CheckerResult::YII_3_0 => CheckerResult::YII_3_0,
        ],
        CheckerResult::NOT_YII => [
            CheckerResult::UNCERTAIN => CheckerResult::NOT_YII,
            CheckerResult::NOT_YII => CheckerResult::NOT_YII,
            CheckerResult::CONFLICT => CheckerResult::CONFLICT,
            CheckerResult::YII => CheckerResult::CONFLICT,
            CheckerResult::YII_1_1 => CheckerResult::CONFLICT,
            CheckerResult::YII_2_0 => CheckerResult::CONFLICT,
            CheckerResult::YII_3_0 => CheckerResult::CONFLICT,
        ],
        CheckerResult::YII_1_1 => [
            CheckerResult::UNCERTAIN => CheckerResult::YII_1_1,
            CheckerResult::NOT_YII => CheckerResult::CONFLICT,
            CheckerResult::CONFLICT => CheckerResult::CONFLICT,
            CheckerResult::YII => CheckerResult::YII_1_1,
            CheckerResult::YII_1_1 => CheckerResult::YII_1_1,
            CheckerResult::YII_2_0 => CheckerResult::YII,
            CheckerResult::YII_3_0 => CheckerResult::YII,
        ],
        CheckerResult::YII_2_0 => [
            CheckerResult::UNCERTAIN => CheckerResult::YII_2_0,
            CheckerResult::NOT_YII => CheckerResult::CONFLICT,
            CheckerResult::CONFLICT => CheckerResult::CONFLICT,
            CheckerResult::YII => CheckerResult::YII_2_0,
            CheckerResult::YII_1_1 => CheckerResult::YII,
            CheckerResult::YII_2_0 => CheckerResult::YII_2_0,
            CheckerResult::YII_3_0 => CheckerResult::YII,
        ],
        CheckerResult::YII_3_0 => [
            CheckerResult::UNCERTAIN => CheckerResult::YII_3_0,
            CheckerResult::NOT_YII => CheckerResult::CONFLICT,
            CheckerResult::CONFLICT => CheckerResult::CONFLICT,
            CheckerResult::YII => CheckerResult::YII_3_0,
            CheckerResult::YII_1_1 => CheckerResult::YII,
            CheckerResult::YII_2_0 => CheckerResult::YII,
            CheckerResult::YII_3_0 => CheckerResult::YII_3_0,
        ],
    ];

    public function check(string $url): CheckerResult
    {
        $reasons = [];
        $result = CheckerResult::UNCERTAIN;

        $content = $this->fetchContent($url);

        foreach ($this->checkers as $checker) {
            $checker = Yii::createObject($checker);
            /** @var ContentCheckerInterface $checker */
            $checkerResult = $checker->check($content);
            $result = $this->resultMatrix[$result][$checkerResult->getResult()];
            $reasons[] = $checkerResult->getReasons();
        }

        return new CheckerResult($result, array_merge(...$reasons));
    }

    private function fetchContent(string $url): string
    {
        return @file_get_contents($url);
    }
}
