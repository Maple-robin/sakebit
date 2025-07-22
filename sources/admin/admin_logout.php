<?php
/*!
@file admin_logout.php
@brief 管理画面：ログアウト処理
@copyright Copyright (c) 2024 Your Name.
*/

// セッションを開始します。
session_start();

// セッション変数をすべて空にします。
$_SESSION = array();

// セッションIDを保存しているクッキーを無効化します。
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 最終的にサーバー上のセッションファイルを破壊します。
session_destroy();

// ログインページへリダイレクトします。
header('Location: admin_login.php');
exit();