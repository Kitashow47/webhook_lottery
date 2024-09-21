<?php
require __DIR__ . '/../src/Database.php';

$db = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $probability = (int)$_POST['probability'];
    $db->query('UPDATE settings SET value = ? WHERE name = ?', [$probability, 'winning_probability']);
}

$currentProbability = $db->query('SELECT value FROM settings WHERE name = ?', ['winning_probability'])->fetch();
$currentProbability = $currentProbability ? (int)$currentProbability['value'] : 50;

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Set Lottery Probability</title>
</head>
<body>
    <h1>Set Lottery Probability</h1>
    <form method="POST">
        <label for="probability">Winning Probability (%):</label>
        <input type="number" id="probability" name="probability" value="<?php echo $currentProbability; ?>" min="0" max="100">
        <button type="submit">Save</button>
    </form>
</body>
</html>
