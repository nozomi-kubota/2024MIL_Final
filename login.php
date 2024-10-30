<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css" />
    <title>投薬管理_ログイン</title>
</head>

<header>
    <nav class="navbar">
        <a href="index.php">投薬記録の登録</a>
        <span>ログイン</span>
    </nav>
</header>

<body>
    <div class="container">
        <form name="form1" action="login_act.php" method="post">
        <h1>投薬管理アプリ Login</h1>
            <div class="form-group">
                <label for="lid">ユーザーID</label>
                <input type="text" id="lid" name="lid" required placeholder="ユーザーID">
            </div>
            <div class="form-group">
                <label for="lpw">パスワード</label>
                <input type="password" id="lpw" name="lpw" required placeholder="パスワード">
            </div>
            <input type="submit" value="ログイン">
        </form>
    </div>
</body>

</html>