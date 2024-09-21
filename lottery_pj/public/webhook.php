<?php
require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/Lottery.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $lottery = new Lottery($db);
    $result = $lottery->draw();

    header('Content-Type: application/json');
    echo json_encode(['result' => $result]);
} else {
    echo "<h1>Webhook Endpoint</h1><p>This endpoint only accepts POST requests.</p>";
}
