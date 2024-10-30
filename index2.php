<?php
session_start();
require_once 'functions.php';
loginCheck();

// 2. データベース接続
$pdo = db_conn();
$stmt_t01_medrecord = $pdo->prepare('
    SELECT t01_medrecord.*, users.name
    FROM t01_medrecord 
    JOIN users ON t01_medrecord.lid = users.id
');
$status_t01_medrecord = $stmt_t01_medrecord->execute();

// 3. 薬情報マスタからデータ取得
$stmt_medlist = $pdo->prepare('SELECT * FROM t03_medicinelist');
$status_medlist = $stmt_medlist->execute();

// 4. 体調分類マスタからデータ取得
$stmt_condition = $pdo->prepare('SELECT * FROM t04_condition');
$status_condition = $stmt_condition->execute();

// 5. データ表示
if ($status_medlist == false || $status_condition == false) {
  sql_error($stmt_medlist);
  sql_error($stmt_condition);
  sql_error($stmt_t01_medrecord);
}
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
  <form id="scoreForm">
    <label for="m1">メチルフェニデート18mg:</label>
    <input type="number" id="m1" name="m1"><br>
    <label for="m2">メチルフェニデート27mg:</label>
    <input type="number" id="m2" name="m2"><br>
    <label for="m3">メチルフェニデート36mg:</label>
    <input type="number" id="m3" name="m3"><br><br>
    <button type="button" onclick="compareScores()">最新の平均値を確認</button><br><br>
  </form>
  <div id="resultTable"></div><br>
  <canvas id="myChart" width="400" height="400"></canvas>

  <script>
    // 薬別の効果持続時間を保持するオブジェクト
    const effectDurations = {
      'メチルフェニデート18mg': [],
      'メチルフェニデート27mg': [],
      'メチルフェニデート36mg': []
    };

    // 入力値を取得して平均値を計算する関数
    function compareScores() {
      // フォームから入力された点数を取得
      const m1 = parseFloat(document.getElementById('m1').value);
      const m2 = parseFloat(document.getElementById('m2').value);
      const m3 = parseFloat(document.getElementById('m3').value);

      // 入力値を配列に追加
      if (!isNaN(m1)) effectDurations['メチルフェニデート18mg'].push(m1);
      if (!isNaN(m2)) effectDurations['メチルフェニデート27mg'].push(m2);
      if (!isNaN(m3)) effectDurations['メチルフェニデート36mg'].push(m3);

      // 平均値を計算
      const averageDurations = {};
      for (const medicine in effectDurations) {
        const durations = effectDurations[medicine];
        const total = durations.reduce((sum, duration) => sum + duration, 0);
        averageDurations[medicine] = durations.length ? (total / durations.length) : 0;
      }

      // 結果を表示
      const resultTable = document.getElementById('resultTable');
      resultTable.innerHTML = `
      <table border="1">
        <tr>
          <th>薬の種類</th>
          <th>平均効果持続時間</th>
        </tr>
        <tr>
          <td>メチルフェニデート18mg</td>
          <td>${averageDurations['メチルフェニデート18mg'].toFixed(2)}</td>
        </tr>
        <tr>
          <td>メチルフェニデート27mg</td>
          <td>${averageDurations['メチルフェニデート27mg'].toFixed(2)}</td>
        </tr>
        <tr>
          <td>メチルフェニデート36mg</td>
          <td>${averageDurations['メチルフェニデート36mg'].toFixed(2)}</td>
        </tr>
      </table>
    `;

      // グラフを描画
      const ctx = document.getElementById('myChart').getContext('2d');
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['メチルフェニデート18mg', 'メチルフェニデート27mg', 'メチルフェニデート36mg'],
          datasets: [{
            label: '平均効果持続時間',
            data: [
              averageDurations['メチルフェニデート18mg'],
              averageDurations['メチルフェニデート27mg'],
              averageDurations['メチルフェニデート36mg']
            ],
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
  </script>
</body>

</html>