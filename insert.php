<?php

session_start();
require_once 'functions.php';
loginCheck();

//1. POSTつぶやき取得
$content = $_POST['content'];


// セッションからユーザーIDを取得
$user_id = $_SESSION['user_id'];

// 画像アップロードの処理
$image_path='';

//isset⇒該当するデータが存在するかをチェックする関数
//ファイルデータが送られてきた場合のみ、画像保存に関連する処理を行う
if (isset($_FILES['image'])) {

    //imageの部分はinputtupe＝"file"のname属性に相当します
    //必要に応じて書き換えるべき"場所"
    //tmp_nameは固定
    $upload_file = $_FILES['image']['tmp_name'];

    //フォルダ名を取得。今回は直書き。
    $dir_name = 'img/';

    //画像の拡張子を取得。jpg.png等
    $extension = pathinfo($_FILES["image"]['name'], PATHINFO_EXTENSION);

    //画像名を取得。uniqid（）を使って独自のIDを付与。
    $file_name = uniqid() . '.' . $extension;

    //画像の保存場所を設定
    $image_path = $dir_name . $file_name;

    //move_upload
    if(!move_uploaded_file($upload_file, $image_path)){
        exit('ファイルの保存に失敗しました');
    }


}

//2. DB接続します
$pdo = db_conn();

//３．つぶやき登録SQL作成
$stmt = $pdo->prepare('INSERT INTO contents(user_id, content, image, created_at) VALUES(:user_id, :content, :image, NOW());');
$stmt->bindValue(':content', $content, PDO::PARAM_STR);
$stmt->bindValue(':image', $image_path, PDO::PARAM_STR);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$status = $stmt->execute(); //実行

//４．つぶやき登録処理後
if (!$status) {
    sql_error($stmt);
} else {
    redirect('select.php');
}
