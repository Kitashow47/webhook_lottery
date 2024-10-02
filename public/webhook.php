<?php
session_start(); // セッションを開始して確率を取得

// データベース接続設定
$dsn = 'mysql:host=db;dbname=webhook_db;charset=utf8';
$user = 'webhook_user';
$password = 'webhookpassword';

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('データベース接続失敗: ' . $e->getMessage());
}

// 抽選確率をセッションから取得（デフォルト50%）
$winningProbability = $_SESSION['winningProbability'] ?? 50;
error_log("Current winning probability: $winningProbability"); // ログに出力

// Webhookからデータを受け取る
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// データが正しく受信された場合
if (isset($data['transactionHeadIds']) && isset($data['action'])) {
    // 確率に基づいて抽選結果を決定
    $lotteryResult = (rand(1, 100) <= $winningProbability) ? 'あたり' : 'ハズレ'; 

    // データベースに保存
    $stmt = $pdo->prepare('INSERT INTO webhook_actions (action, transaction_id, lottery_result) VALUES (:action, :transaction_id, :lottery_result)');
    $stmt->execute([
        ':action' => $data['action'],
        ':transaction_id' => $data['transactionHeadIds'][0],
        ':lottery_result' => $lotteryResult
    ]);

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data received']);
}
