<?php
// 0. SESSION開始
session_start();


// 1. 関数群の読み込み⇒ ログインチェック処理
require_once('functions.php');
loginCheck();

// 2. データベース接続
$pdo = db_conn();
$stmt_t01_medrecord = $pdo->prepare('SELECT * FROM t01_medrecord');
$status_t01_medrecord = $stmt_t01_medrecord->execute();

// 3. 薬情報マスタからデータ取得
$stmt_medlist = $pdo->prepare('SELECT * FROM t03_medicinelist');
$status_medlist = $stmt_medlist->execute();

// 4. 体調分類マスタからデータ取得
$stmt_condition = $pdo->prepare('SELECT * FROM t04_condition');
$status_condition = $stmt_condition->execute();

// 5. データ表示
if ($status_t01_medrecord == false || $status_medlist == false || $status_condition == false) {
    sql_error($stmt_t01_medrecord);
    sql_error($stmt_medlist);
    sql_error($stmt_condition);
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
        <a class="navbar-brand" href="select.php">服薬記録一覧へ</a>
        <div class="navbar-header user-name">
            <p>ユーザー名：<?= $_SESSION['name'] ?>さん</p>
        </div>
        <form class="logout-form" action="logout.php" method="post" onsubmit="return confirm('本当にログアウトしますか？');">
            <button type="submit" class="logout-button">ログアウト</button>
        </form>
    </nav>

    <div class="container">
        <h1>服薬記録編集</h1>

        <form method="POST" action="update.php" enctype="multipart/form-data">
            <div class="jumbotron">
                <fieldset>
                    <legend>[編集]</legend>
                    <label for="content">内容：<textarea id="content" name="content" rows="4" cols="40"><?= h($row['content']) ?>
                    </textarea></label>

                    <?php if (!empty($row['image'])) {
                        echo '<img src="' . h($row['image']) . '" class="image-class">';
                    } ?>

                    <input type="submit" value="更新">
                    <input type="hidden" name="id" value="<?= $id ?>">

                </fieldset>
            </div>
        </form>
    </div>

</body>

</html>