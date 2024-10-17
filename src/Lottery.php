<?php
class Lottery {
    private $winningProbability;

    public function __construct($winningProbability = 50) {
        $this->winningProbability = $winningProbability;
    }

    public function draw() {
        // 乱数生成で当選確率を判定
        $randomNumber = mt_rand(1, 100);
        if ($randomNumber <= $this->winningProbability) {
            return 'Win';
        }
        return 'Lose';
    }
}
