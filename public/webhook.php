<?php
session_start();  // セッションを開始

// Webhookからデータを受け取る
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// デバッグ: Webhookの内容をログに出力
file_put_contents('php://stderr', "webhook.php - Webhook payload: " . print_r($data, true) . "\n");

// "event"が"pos:transactions"の場合のみ処理を実行
if ($data && isset($data['event']) && $data['event'] === 'pos:transactions') {
    // 抽選を実行（50%の確率で当選）
    $lotteryResult = rand(1, 100) <= 50 ? 'Win' : 'Lose';

    // セッションに抽選結果を保存
    $_SESSION['lotteryResult'] = $lotteryResult;

    // デバッグ: セッションに保存された結果をログに出力
    file_put_contents('php://stderr', "webhook.php - Lottery Result: " . $_SESSION['lotteryResult'] . "\n");

    // Webhookのレスポンスとして結果をJSON形式で返す
    echo json_encode([
        'result' => $lotteryResult,
        'contractId' => $data['contractId'],
        'transactionHeadIds' => $data['transactionHeadIds']
    ]);
} else {
    echo json_encode(['error' => 'Invalid event or no data received']);
}
