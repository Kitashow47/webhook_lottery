<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 確率が送信された場合、セッションに保存
    $probability = intval($_POST['probability']);
    $_SESSION['winningProbability'] = $probability;

    // 確率が設定された後、トップ画面（index.php）にリダイレクト
    header('Location: /index.php');
    exit;
}

$currentProbability = $_SESSION['winningProbability'] ?? 50; // デフォルト50%
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>抽選確率設定</title>
</head>
<body>
    <h1>抽選確率設定</h1>
    <form method="POST" action="settings.php">
        <label for="probability">当選確率（％）:</label>
        <input type="number" id="probability" name="probability" value="<?php echo $currentProbability; ?>" min="0" max="100" required>
        <button type="submit">設定</button>
    </form>

    <p>現在の確率: <?php echo $currentProbability; ?>%</p>
</body>
</html>
