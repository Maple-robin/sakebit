<?php
// auth_check.php

// セッションがまだ開始されていない場合にのみ開始する
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ログイン状態を確認
$client_id = $_SESSION['client_id'] ?? 0;

if (empty($client_id)) {
    // ログインしていない場合はclient_login.phpへリダイレクト
    header('Location: client_login.php');
    exit();
}

// ★★★★★★★★★★★★★★★★★★★★★
// ★★★ ここに$debugの定義を追加 ★★★
// ★★★★★★★★★★★★★★★★★★★★★
$debug = defined('DEBUG') ? DEBUG : false;

// ログインしている場合は、後続の処理で client_id と debug を使えるようにしておく
?>
