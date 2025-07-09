<?php
// セッションを開始
session_start();

// セッション変数をすべて解除
$_SESSION = array();

// セッションクッキーを削除（古いブラウザ用）
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// セッションを完全に破壊
session_destroy();

// ログアウト後はトップページにリダイレクト
header("Location: index.php");
exit;
?>