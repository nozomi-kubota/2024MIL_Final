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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>服薬記録</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body id="main">
    <nav class="navbar">
        <a class="navbar-brand" href="select.php">服薬記録画面へ</a>
        <br>
        <a class="navbar-brand" href="index2.php">効果持続時間確認画面へ</a>
        <div class="navbar-header user-name">
            <p>ユーザー名：<?= $_SESSION['name'] ?>さん</p>
        </div>
        <form class="logout-form" action="logout.php" method="post" onsubmit="return confirm('本当にログアウトしますか？');">
            <button type="submit" class="logout-button">ログアウト</button>
        </form>
    </nav>
    <main>
        <!-- <div class="container"> -->
        <div>
            <h1>服薬記録一覧</h1>
            <!-- <div class="card-container"> -->
            <table>
                <tr>
                    <th>服薬日</th>
                    <th>服薬のタイミング</th>
                    <th>服薬時刻</th>
                    <th>薬名</th>
                    <th>数量</th>
                    <th>体調</th>
                    <th>備考</th>
                    <th>編集・削除</th>
                </tr>
                <?php while ($result = $stmt_t01_medrecord->fetch(PDO::FETCH_ASSOC)) : ?>

                    <tr>
                        <td><?= htmlspecialchars($result['mdate'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($result['timezone']) ?></td>
                        <td><?= htmlspecialchars($result['mtime']) ?></td>
                        <td><?= htmlspecialchars($result['mname']) ?></td>
                        <td><?= htmlspecialchars($result['quantity']) ?></td>
                        <td><?= htmlspecialchars($result['ctype']) ?></td>
                        <td><?= htmlspecialchars($result['remark_user']) ?></td>
                        <td>

                            <a href="select.php?id=<?= htmlspecialchars($result['id'], ENT_QUOTES, 'UTF-8') ?>">編集</a>
                            <a href="delete.php?id=<?= htmlspecialchars($result['id'], ENT_QUOTES, 'UTF-8') ?>" onclick="return confirm('本当に削除しますか？')">削除</a>

                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </main>
    <script src='js/script.js'></script>
</body>

</html>