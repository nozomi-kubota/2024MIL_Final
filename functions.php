<?php

//XSS対応（ echoする場所で使用！それ以外はNG ）
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}


//DB接続
function db_conn()
{
    try {
        $db_name = 'gs_db_mil';    //データベース名
        $db_id   = 'root';      //アカウント名
        $db_pw   = '';      //パスワード：XAMPPはパスワード無し
        $db_host = 'localhost'; //DBホスト
        $pdo = new PDO('mysql:dbname=' . $db_name . ';charset=utf8;host=' . $db_host, $db_id, $db_pw);
        return $pdo;
    } catch (PDOException $e) {
        exit('DB Connection Error:' . $e->getMessage());
    }
}

//SQLエラー
function sql_error($stmt)
{
    //execute（SQL実行時にエラーがある場合）
    $error = $stmt->errorInfo();
    exit('SQLError:' . $error[2]);
}

//リダイレクト
function redirect($file_name)
{
    header('Location: ' . $file_name);
    exit();
}

// ログインチェク処理 loginCheck()
function loginCheck()
{
// セッション変数が設定されていない 或いは セッション変数の値が現在のIDと一致しない場合
    if (!isset($_SESSION['chk_ssid'])  ||  $_SESSION['chk_ssid']  !==  session_id()) {
        // header 関数を使用して、ユーザーを login.php ページにリダイレクト
        header('Location: login.php');
        exit('LOGIN ERROR');
    } else {
        // ログイン済み処理//session_regenerate_id(true) 関数を使用して、現在のセッションIDを再生成:なりすまし対策
        session_regenerate_id(true);
        $_SESSION['chk_ssid'] = session_id();
    }
}

//時間を30分刻みで選択できるようにする
function generateTimeOptions() {
    $options = '';
    for ($hour = 0; $hour < 24; $hour++) {
        for ($minute = 0; $minute < 60; $minute += 30) {
            $time = sprintf('%02d:%02d', $hour, $minute);
            $options .= "<option value='$time'>$time</option>";
        }
    }
    return $options;
}