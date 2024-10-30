<?php

session_start();
require_once 'functions.php';
loginCheck();

//1. POST取得
$content = $_POST['content'];
$id     = $_POST['id'];

//2. DB接続します
$pdo = db_conn();

//３．つぶやき登録SQL作成
$stmt = $pdo->prepare('UPDATE contents SET content=:content WHERE id=:id;');
$stmt->bindValue(':content', $content, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute(); //実行

//４．つぶやき登録処理後
if ($status === false) {
    sql_error($stmt);
} else {
    redirect('select.php');
}

//３．登録SQL作成

$stmt = $pdo->prepare('UPDATE contents SET content=:content, updated_at=sysdate(),image=:image WHERE id=:id;');
$stmt->bindValue(':content', $content, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':image', $image_path, PDO::PARAM_STR);
$status = $stmt->execute(); //実行

//４．登録後の処理後：SQL実行エラー時
if (!$status) {
    sql_error($stmt);
} else {
    //５．select.phpへリダイレクト：SQL実行エラーが無ければ
    redirect('select.php');
}

//４．登録処理後
if ($status === false) {
    sql_error($stmt);
} else {
    redirect('select.php');
}
