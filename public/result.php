<?php
// データベース接続設定
$dsn = 'mysql:host=db;dbname=webhook_db;charset=utf8'; // データベース接続情報
$user = 'webhook_user'; // データベースのユーザー名
$password = 'webhookpassword'; // データベースのパスワード

try {
    // データベース接続を確立し、PDOオブジェクトを作成
    $pdo = new PDO($dsn, $user, $password);
    // エラーモードを例外をスローする設定に変更
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // データベース接続に失敗した場合、エラーメッセージを表示してスクリプトを終了
    die('データベース接続失敗: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>抽選結果</title>
    <style>
        /* 結果表示のスタイル設定 */
        #result {
            display: none; /* デフォルトで結果は非表示にする */
        }
    </style>
    <script>
        let isDisplayingResult = false; // 結果を表示中かどうかを示すフラグ

        // データベースから新しい結果を取得して表示する関数
        function checkForNewData() {
            // 結果表示中は新しいデータをチェックしない
            if (isDisplayingResult) return;

            // 非同期通信でデータベースをチェック
            fetch('check_db.php')
                .then(response => response.json()) // サーバーからJSON形式で結果を受け取る
                .then(data => {
                    console.log('Fetched data:', data); // 受け取ったデータをコンソールに表示（デバッグ用）

                    // 抽選結果がある場合
                    if (data.lottery_result) {
                        isDisplayingResult = true; // 結果表示中のフラグを立てる
                        
                        // 待機中の表示を非表示にする
                        document.getElementById('waiting').style.display = 'none';
                        
                        // 結果を表示（抽選結果、アクション、トランザクションID、当選確率）
                        document.getElementById('result').innerText = '抽選結果: ' + data.lottery_result + 
                            ' (アクション: ' + data.action + 
                            ', トランザクションID: ' + data.transaction_id + 
                            ') 当選確率: ' + data.probability + '%';
                        
                        // 結果を表示する
                        document.getElementById('result').style.display = 'block';

                        // 5秒後に待機中に戻し、結果表示を終了する
                        setTimeout(function() {
                            document.getElementById('result').style.display = 'none'; // 結果を非表示
                            document.getElementById('waiting').style.display = 'block'; // 待機中を再表示
                            isDisplayingResult = false; // 結果表示中フラグを解除
                        }, 5000); // 5秒待つ
                    }
                })
                .catch(error => console.error('Error:', error)); // エラーが発生した場合はコンソールに表示
        }

        // 3秒ごとにデータベースをチェックし、新しい結果があるか確認
        setInterval(checkForNewData, 3000);
    </script>
</head>
<body>
    <h1>抽選結果</h1>
    
    <!-- 結果を待っている間の表示 -->
    <div id="waiting">待機中...</div>
    
    <!-- 抽選結果を表示する要素 -->
    <div id="result"></div>
</body>
</html>
