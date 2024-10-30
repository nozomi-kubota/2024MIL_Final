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
    <title>服用管理・登録フォーム</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <nav>
            <a href="select.php">データ一覧</a>
            <a href="login.php">服用管理・登録</a>
            <!-- <form class="logout-form" action="logout.php" method="post"> -->
            <!-- <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"> -->
            <form class="logout-form" action="logout.php" method="post" onsubmit="return confirm('本当にログアウトしますか？');">
                <button type="submit" class="logout-button">ログアウト</button>
            </form>
        </nav>
    </header>

    <!-- Main[Start] -->
    <main>
        <!-- <div class="container"> -->
        <!-- insert.phpで入力した内容が送られる -->
        <form method="POST" action="insert.php">
            <fieldset>
                <legend>服用登録フォーム</legend>
                <label for="book_name">薬名</label>
                <input type="text" id="book_name" name="book_name" required placeholder="※※ 18ml">

                <label for="book_comment">コメント</label>
                <textarea id="content" name="book_comment" rows="6" required placeholder="体調に関する特記事項等を入力ください"></textarea>

                <input type="submit" value="登録する">
            </fieldset>
        </form>
    </main>
    <!-- Main[End] -->

</body>

</html>