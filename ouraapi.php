<?php
// PHPファイルの開始

// アクセストークンをPHP変数に設定します
$accessToken = 'RZMOA7OPNJUV3MSHADRJU5YAB65C3ARG';
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OURA Ring Sleep Data</title>
</head>

<body>
    <h1>OURA Ring Sleep Data</h1>
    <div id="sleep-data"></div>

    <script>
        // PHPからアクセストークンをJavaScriptに渡します
        const accessToken = '<?php echo $accessToken; ?>';

        // APIエンドポイント
        const apiUrl = 'https://api.ouraring.com/v2/usercollection/sleep';

        // リクエストオプション
        const requestOptions = {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${accessToken}`,
                'Content-Type': 'application/json'
            }
        };

        // データを取得する関数
        async function fetchSleepData() {
            try {
                const response = await fetch(apiUrl, requestOptions);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                console.log('Sleep Data:', data);
                document.getElementById('sleep-data').innerText = JSON.stringify(data, null, 2);
            } catch (error) {
                console.error('Error fetching sleep data:', error);
                document.getElementById('sleep-data').innerText = 'Error fetching sleep data: ' + error.message;
            }
        }

        // 関数を呼び出してデータを取得します
        fetchSleepData();
    </script>
</body>

</html>