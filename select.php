<?php
// 0. SESSION開始
session_start();


// 1. 関数群の読み込み⇒ ログインチェック処理
require_once('functions.php');
loginCheck();

// 2. データベース接続
$pdo = db_conn();
$stmt_t01_medrecord = $pdo->prepare('
    SELECT t01_medrecord.*, Users.name
    FROM t01_medrecord 
    JOIN Users ON t01_medrecord.lid = Users.lid
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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>服薬記録</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- <header>
        <nav class="navbar">
            <a href="index.php">メイン画面へ</a>
        </nav>
    </header> -->

    <nav class="navbar">
        <a class="navbar-brand" href="list.php">データ登録画面へ</a>
        <div class="navbar-header user-name">
            <p>ユーザー名：<?= $_SESSION['name'] ?>さん</p>
        </div>
        <form class="logout-form" action="logout.php" method="post" onsubmit="return confirm('本当にログアウトしますか？');">
            <button type="submit" class="logout-button">ログアウト</button>
        </form>
    </nav>


    <main>
        <h1>服薬の記録</h1>
        <form action="submit_prescription.php" method="post">
            <label for="mdate">服薬日:</label>
            <input type="date" id="mdate" name="mdate" value="">
            <br>
            <label for="timezone">服薬のタイミング:</label>
            <select name="timezone" id="timezone" required>
                <option value="朝">朝</option>
                <option value="昼">昼</option>
                <option value="夜">夜</option>
                <option value="就寝前">就寝前</option>
                <option value="頓服">頓服</option>
            </select>
            <br>
            <label for="mtime">服薬時刻を選択(30分刻み):</label>
            <select name="mtime" id="mtime" required>
                <?php echo generateTimeOptions(); ?>
            </select>
            <br>
            <label for="mname">薬を選択:</label>
            <select name="mname" id="mname">
                <?php
                while ($row = $stmt_medlist->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $row["mid"] . "'>" . $row["mname"] . "</option>";
                }
                ?>
            </select>
            <br>

            <label for="quantity">数量:</label>
            <input type="number" name="quantity" id="quantity" required>
            <br>

            <label for="ctype">体調を選択:</label>
            <select name="ctype" id="ctype">
                <?php
                while ($row = $stmt_condition->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $row["cid"] . "'>" . $row["ctype"] . "</option>";
                }
                ?>
            </select>
            <br>
            <label for="efficiency_du">効果の持続時間を選択(30分刻み):</label>
            <select name="efficiency_du" id="efficiency_du" required>
                <?php echo generateTimeOptions(); ?>
            </select>
            <br>
            <label for="remark_user">備考:</label>
            <textarea name="remark_user" id="remark_user"></textarea>
            <br><br>
            <input type="submit" value="登  録">
        </form>
        <script>
            // 今日の日付を取得して初期値に設定するスクリプト
            document.getElementById('mdate').valueAsDate = new Date();
        </script>

    </main>

</html>