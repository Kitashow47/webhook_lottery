<?php
// データベース接続設定
$dsn = 'mysql:host=db;dbname=webhook_db;charset=utf8'; // データベース接続情報（DSN）
$user = 'webhook_user'; // データベースユーザー名
$password = 'webhookpassword'; // データベースパスワード

try {
    // データベース接続を確立し、PDOオブジェクトを作成
    $pdo = new PDO($dsn, $user, $password);
    // エラーモードを例外を投げる設定にする（エラーが発生した場合、例外をスロー）
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // データベース接続が失敗した場合はエラーメッセージを出力してスクリプトを終了
    die('データベース接続失敗: ' . $e->getMessage());
}

// 処理されていない最新の1件を取得（processedがFALSEのデータを1件だけ取得）
$stmt = $pdo->query('SELECT * FROM webhook_actions WHERE processed = FALSE ORDER BY created_at DESC LIMIT 1');
$result = $stmt->fetch(PDO::FETCH_ASSOC); // 結果を連想配列形式で取得

if ($result) {
    // データが見つかった場合、processedフラグをTRUEに更新して、表示済みにする
    $updateStmt = $pdo->prepare('UPDATE webhook_actions SET processed = TRUE WHERE id = :id');
    // idを元に該当レコードのフラグを更新
    $updateStmt->execute([':id' => $result['id']]);

    // 抽選結果と確率も一緒にJSON形式で返す
    echo json_encode([
        'action' => $result['action'], // Webhookでのアクション（例: created, updated）
        'transaction_id' => $result['transaction_id'], // トランザクションID
        'lottery_result' => $result['lottery_result'], // 抽選結果（あたり、ハズレ）
        'probability' => $result['probability'] // 当選確率
    ]);
} else {
    // 処理されていないデータがない場合、actionにnullを返す
    echo json_encode(['action' => null]);
}
