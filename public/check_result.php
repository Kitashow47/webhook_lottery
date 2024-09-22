<?php
session_start();  // セッションを開始

// セッションから抽選結果を取得
$result = $_SESSION['lotteryResult'] ?? null;

// デバッグ: 取得した結果をログに出力
file_put_contents('php://stderr', "check_result.php - Lottery result: " . print_r($result, true) . "\n");

// JSON形式で結果を返す
echo json_encode(['result' => $result]);

// 結果が存在していた場合、セッションの値をリセット
if ($result) {
    $_SESSION['lotteryResult'] = null;
}
