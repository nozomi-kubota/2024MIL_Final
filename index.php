<?php
// 0. SESSION開始！！
session_start();

// 1.  関数群の読み込み
require_once('functions.php');

loginCheck();

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>効果持続時間</title>
    <link rel="stylesheet" href="css/reset2.css" />
    <link rel="stylesheet" href="css/style2.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/script2.js" defer></script>
</head>

<body>
    <nav class="navbar">
        <a class="navbar-a" href="select.php">服薬記録画面へ</a>
        <br>
        <form class="logout-form" action="logout.php" method="post" onsubmit="return confirm('本当にログアウトしますか？');">
            <button type="submit" class="logout-button">ログアウト</button>
        </form>
    </nav>
    <br><br> <br>
    <h1>薬別 効果持続時間 平均値</h1>
    <br>
    <div id="resultTable"></div><br>
    <canvas id="myChart" width="400" height="400"></canvas>

    <script>
        async function fetchAverageDurations() {
            try {
                const response = await fetch('get_average_durations.php');
                const data = await response.json();
                return data;
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        async function displayChart() {
            const data = await fetchAverageDurations();

            const ctx = document.getElementById('myChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['メチルフェニデート18mg', 'メチルフェニデート27mg', 'メチルフェニデート36mg'],
                    datasets: [{
                        label: '平均効果持続時間',
                        data: [data['メチルフェニデート18mg'], data['メチルフェニデート27mg'], data['メチルフェニデート36mg']],
                        backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(75, 192, 192, 0.2)'],
                        borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(75, 192, 192, 1)'],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // ページがロードされたらグラフを表示
        window.onload = displayChart;
    </script>
</body>

</html>