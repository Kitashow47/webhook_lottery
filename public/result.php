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
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>抽選結果</title>
    <style>
        #result {
            display: none;
        }
    </style>
    <script>
        let isDisplayingResult = false; // 結果表示中かどうかを管理

        // データベースから新しい結果を取得するための関数
        function checkForNewData() {
            if (isDisplayingResult) return; // 結果表示中はチェックを停止

            fetch('check_db.php')
                .then(response => response.json())
                .then(data => {
                    console.log('Fetched data:', data); // 取得したデータをログに表示
                    if (data.action) {
                        // 結果があれば表示
                        isDisplayingResult = true; // 結果表示中フラグを立てる
                        document.getElementById('waiting').style.display = 'none';
                        document.getElementById('result').innerText = '抽選結果: ' + data.lottery_result + ' (アクション: ' + data.action + ', トランザクションID: ' + data.transaction_id + ')';
                        document.getElementById('result').style.display = 'block';

                        // 5秒後に待機中に戻す
                        setTimeout(function() {
                            document.getElementById('result').style.display = 'none';
                            document.getElementById('waiting').style.display = 'block';
                            isDisplayingResult = false; // 結果表示終了後にフラグを解除
                        }, 5000);
                    }
                })
                .catch(error => console.error('Error:', error));
        }  

        // 3秒ごとにデータベースをチェック
        setInterval(checkForNewData, 3000);
    </script>
</head>
<body>
    <h1>抽選結果</h1>
    <div id="waiting">待機中...</div>
    <div id="result"></div>
</body>
</html>
