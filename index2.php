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
    <div class="navbar-header user-name">
      <p>ユーザー名：<?= $_SESSION['name'] ?>さん</p>
    </div>
    <form class="logout-form" action="logout.php" method="post" onsubmit="return confirm('本当にログアウトしますか？');">
      <button type="submit" class="logout-button">ログアウト</button>
    </form>
  </nav>

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
  <canvas id="myChart" width="50" height="50"></canvas>

</body>



</html>