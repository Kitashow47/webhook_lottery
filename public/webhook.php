<?php
session_start(); // セッションを開始して、セッションデータ（確率など）を取得

// データベース接続設定
$dsn = 'mysql:host=db;dbname=webhook_db;charset=utf8'; // データベースのDSN（接続情報）
$user = 'webhook_user'; // データベースのユーザー名
$password = 'webhookpassword'; // データベースのパスワード

try {
    // データベースに接続し、PDOオブジェクトを作成
    $pdo = new PDO($dsn, $user, $password);
    // エラーモードを例外に設定（エラーが発生した場合に例外をスロー）
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    error_log("データベース接続成功"); // デバッグ用に接続成功をログに出力
} catch (PDOException $e) {
    // データベース接続に失敗した場合、エラーメッセージを出力してスクリプトを終了
    error_log("データベース接続失敗: " . $e->getMessage());
    die('データベース接続失敗: ' . $e->getMessage());
}

// admin/settings.phpで設定された当選確率をセッションから取得（設定されていない場合は50%をデフォルトに）
$winningProbability = $_SESSION['winningProbability'] ?? 50;
error_log("Current winning probability in webhook.php: $winningProbability"); // デバッグ用に確率をログに出力

// Webhookから送信されたデータを受け取る
$input = file_get_contents('php://input'); // Webhookリクエストの本文を取得
$data = json_decode($input, true); // JSON形式のデータを配列に変換
error_log("Received Webhook data: " . print_r($data, true)); // 受け取ったデータをログに出力（デバッグ用）

// Webhookデータが正しく受信されたか確認
if (isset($data['transactionHeadIds']) && isset($data['action'])) {
    error_log("Valid Webhook data received"); // データが有効であることをログに出力

    // 設定された確率に基づいて抽選結果を決定
    $lotteryResult = (rand(1, 100) <= $winningProbability) ? 'あたり' : 'ハズレ'; 
    error_log("Lottery result: $lotteryResult"); // 抽選結果をログに出力

    // Webhookデータと抽選結果をデータベースに保存
    try {
        // SQL文を準備して、データを挿入する
        $stmt = $pdo->prepare('INSERT INTO webhook_actions (action, transaction_id, lottery_result, probability) VALUES (:action, :transaction_id, :lottery_result, :probability)');
        $stmt->execute([
            ':action' => $data['action'], // Webhookで受け取ったアクション（例: created, updated）
            ':transaction_id' => $data['transactionHeadIds'][0], // トランザクションID
            ':lottery_result' => $lotteryResult, // 抽選結果
            ':probability' => $winningProbability // 当選確率
        ]);
        error_log("Database insert successful: action=" . $data['action']); // データ挿入が成功したことをログに出力
        echo json_encode(['status' => 'success']); // 成功レスポンスを返す
    } catch (PDOException $e) {
        // データベースへの挿入が失敗した場合、エラーメッセージをログに出力し、エラーを返す
        error_log("Database insert failed: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Database insert failed']);
    }
} else {
    // Webhookデータが不正な場合、エラーメッセージをログに出力し、エラーを返す
    error_log("Invalid Webhook data received");
    echo json_encode(['status' => 'error', 'message' => 'Invalid data received']);
}
