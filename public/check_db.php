<?php
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

// 処理されていない最新の1件を取得
$stmt = $pdo->query('SELECT * FROM webhook_actions WHERE processed = FALSE ORDER BY created_at DESC LIMIT 1');
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    // データが見つかった場合、表示した後にフラグを更新して再表示されないようにする
    $updateStmt = $pdo->prepare('UPDATE webhook_actions SET processed = TRUE WHERE id = :id');
    $updateStmt->execute([':id' => $result['id']]);

    echo json_encode([
        'action' => $result['action'],
        'transaction_id' => $result['transaction_id']
    ]);
} else {
    // 新しいデータがない場合はnullを返す
    echo json_encode(['action' => null]);
}
