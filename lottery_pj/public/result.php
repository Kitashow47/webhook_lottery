<?php
require __DIR__ . '/../src/Database.php';

$db = new Database();
$results = $db->query("SELECT * FROM results ORDER BY created_at DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Lottery Results</title>
</head>
<body>
    <h1>Recent Lottery Results</h1>
    <ul>
        <?php foreach ($results as $result): ?>
            <li><?php echo $result['created_at']; ?>: <?php echo $result['result']; ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
