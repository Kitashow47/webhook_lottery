<?php
// セッションIDをURLのパラメータから取得
if (isset($_GET['sessionId'])) {
    session_id($_GET['sessionId']);  // セッションIDをセット
}

session_start();  // セッションを開始

// セッションから抽選結果を取得
$result = $_SESSION['lotteryResult'] ?? null;
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
        // セッションIDを保存する変数を用意
        var sessionId = '<?php echo session_id(); ?>';  // PHPからセッションIDを渡す

        // Webhookの結果をポーリングして取得
        function checkForWebhook() {
            fetch('check_result.php?sessionId=' + sessionId)  // セッションIDを渡す
                .then(response => response.json())
                .then(data => {
                    console.log('Fetched result:', data);  // デバッグ用に結果を出力
                    if (data.result) {
                        // 結果があれば表示
                        document.getElementById('waiting').style.display = 'none';
                        document.getElementById('result').innerText = '抽選結果: ' + data.result;
                        document.getElementById('result').style.display = 'block';

                        // 5秒後に待機中に戻る
                        setTimeout(function() {
                            document.getElementById('result').style.display = 'none';
                            document.getElementById('waiting').style.display = 'block';
                        }, 5000);
                    }
                })
                .catch(error => console.error('Error fetching result:', error));  // エラーログを表示
        }

        // 2秒ごとに結果をチェックする
        setInterval(checkForWebhook, 2000);
    </script>
</head>
<body>
    <h1>抽選結果</h1>
    <div id="waiting">待機中...</div>
    <div id="result"><?php echo $result ? '抽選結果: ' . $result : ''; ?></div>
</body>
</html>
