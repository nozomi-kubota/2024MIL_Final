<?php
// 0. SESSION開始！！
session_start();

//POST値を受け取る
if (isset($_POST['lid'])) {
    $lid = $_POST['lid']; //lid
} else {
    // lidがPOSTされていない場合の処理
    // 例えば、エラーメッセージを表示して終了する
    die('Error: lid is not set.');
}

$lpw = $_POST['lpw']; //lpw


// 1. 関数群の読み込み⇒ ログインチェック処理
require_once('functions.php');
loginCheck();

// 2. POSTデータ取得
$mdate = $_POST['mdate'];
$timezone = $_POST['timezone'];
$mtime = $_POST['mtime'];
$mname = $_POST['mname'];
$quantity = $_POST['quantity'];
$ctype = $_POST['ctype'];
$ctype = $_POST['ctype'];
$remark_user = $_POST['remark_user'];
$efficiency_du = $_POST['efficiency_du'];

$lid = $_SESSION['lid']; // ユーザーIDをセッションから取得

// 3. データベース接続
$pdo = db_conn();

// 4. データ登録SQL作成
$stmt = $pdo->prepare('INSERT INTO t01_medrecord(mdate, timezone, mtime, mname, quantity, ctype, remark_user,efficiency_du) VALUES(:mdate, :timezone, :mtime, :mname, :quantity, :ctype, :remark_user,:efficiency_du)');
$stmt->bindValue(':mdate', $mdate, PDO::PARAM_STR);
$stmt->bindValue(':timezone', $timezone, PDO::PARAM_STR);
$stmt->bindValue(':mtime', $mtime, PDO::PARAM_STR);
$stmt->bindValue(':mname', $mname, PDO::PARAM_STR);
$stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
$stmt->bindValue(':ctype', $ctype, PDO::PARAM_STR);
$stmt->bindValue(':remark_user', $remark_user, PDO::PARAM_STR);
$stmt->bindValue(':efficiency_du', $efficiency_du, PDO::PARAM_STR);
$status = $stmt->execute();

// 5. データ登録処理後
if ($status == false) {
    sql_error($stmt);
} else {
    redirect('list.php');
}
