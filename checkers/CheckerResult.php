<?php

namespace app\checkers;

final class CheckerResult
{
    const YII = 'Yii';
    const YII_1_1 = 'Yii 1.1';
    const YII_2_0 = 'Yii 2.0';
    const YII_3_0 = 'Yii 3.0';
    const NOT_YII = 'Not Yii';
    const UNCERTAIN = 'Uncertain';
    const CONFLICT = 'Conflict';

    private $result;
    private $reasons;

    public function __construct(string $result, array $reasons)
    {
        $this->result = $result;
        $this->reasons = $reasons;
    }

    public function getResult(): string
    {
        return $this->result;
    }

    public function getReasons(): array
    {
        return $this->reasons;
    }
}
