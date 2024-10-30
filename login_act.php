<?php
session_start();

//POST値を受け取る
$lid = $_POST['lid']; //lid
$lpw = $_POST['lpw']; //lpw
$_SESSION['user_id'] = $val['id']; // ユーザーIDをセッションに保存

//1.  DB接続
require_once('functions.php');
$pdo = db_conn();

//2. データ登録SQL作成
// usersに、IDがあるか確認する。
$stmt = $pdo->prepare('SELECT * FROM users WHERE lid = :lid');
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$status = $stmt->execute();

//3. SQL実行時にエラーがある場合STOP
if ($status === false) {
    sql_error($stmt);
}

//4. 抽出データ数を取得
$val = $stmt->fetch();  //1レコードだけ取得する方法

//5.該当１レコードがあればSESSIONに値を代入
// パスワードの照合
if ($val['lid'] != ''  && password_verify($lpw, $val['lpw'])) {
    //Login成功時 該当レコードがあればSESSIONに値を代入
    $_SESSION['chk_ssid'] = session_id();
    $_SESSION['name'] = $val['name'];
    $_SESSION['user_id'] = $val['lid']; // ユーザーIDをセッションに保存
    header('Location: select.php');
} else {
    //Login失敗時(Logout経由)login.php
    header('Location: login.php');
    //redirect('login.php');
}

exit();
