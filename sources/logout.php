<?php
/*!
@file logout.php
@brief ログアウト処理
@copyright Copyright (c) 2024 Your Name.
*/

// セッションを開始
session_start();

// セッション変数を全て解除
$_SESSION = array();

// セッションクッキーを削除
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// セッションを破壊
session_destroy();

// ログアウト後、トップページ (index.php) へリダイレクト
header('Location: index.php?loggedout=true');
exit();
?>
