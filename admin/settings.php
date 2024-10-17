<?php
session_start(); // セッションを開始（セッションがない場合は新しく作成）

// POSTリクエストが送信されたかどうかを確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームから送信された確率を整数に変換して取得
    $probability = intval($_POST['probability']);
    
    // セッションに当選確率を保存
    $_SESSION['winningProbability'] = $probability;
    
    // セッションに保存した確率をログに出力（デバッグ用）
    error_log("セッションに保存された当選確率: " . $_SESSION['winningProbability']); 

    // 確率が設定された後、トップ画面（index.php）にリダイレクト
    header('Location: /index.php');
    exit; // スクリプトの実行をここで終了
}

// セッションから現在の確率を取得（もしセッションがない場合はデフォルトで50%）
$currentProbability = $_SESSION['winningProbability'] ?? 50;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>抽選確率設定</title>
</head>
<body>
    <h1>抽選確率設定</h1>
    
    <!-- 当選確率を設定するフォーム -->
    <form method="POST" action="settings.php">
        <label for="probability">当選確率（％）:</label>
        <!-- 入力欄に現在の確率を初期表示し、0～100の範囲で設定できる -->
        <input type="number" id="probability" name="probability" value="<?php echo $currentProbability; ?>" min="0" max="100" required>
        <button type="submit">設定</button> <!-- 確率を設定するボタン -->
    </form>

    <!-- 現在設定されている当選確率を表示 -->
    <p>現在の確率: <?php echo $currentProbability; ?>%</p>
</body>
</html>
