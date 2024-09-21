<?php

class Lottery {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function draw() {
        $probability = $this->getProbability();
        $isWinner = mt_rand(1, 100) <= $probability;
        
        $result = $isWinner ? 'Win' : 'Lose';
        $this->db->query('INSERT INTO results (result) VALUES (?)', [$result]);
        
        return $result;
    }

    private function getProbability() {
        $result = $this->db->query('SELECT value FROM settings WHERE name = ?', ['winning_probability'])->fetch();
        return $result ? (int)$result['value'] : 50;  // デフォルト50%
    }
}
