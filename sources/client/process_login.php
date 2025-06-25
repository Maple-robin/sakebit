<?php
/*!
@file process_login.php
@brief ログインフォームからのデータを受け取り、ユーザー認証を行う
@copyright Copyright (c) 2024 Your Name.
*/

// config.php と contents_db.php をインクルード
// パスはprocess_signup.phpと同じルールで設定
require_once __DIR__ . '/../common/config.php';
require_once __DIR__ . '/../common/contents_db.php';

// デバッグモードのオン/オフ
$debug_mode = false;

// POSTリクエスト以外からのアクセスを拒否
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit();
}

// 入力データの取得とサニタイズ
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_EMAIL); // メールアドレスとしてサニタイズ
$password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW); // パスワードはハッシュ化と比較するため、ここではサニタイズしない

$errors = []; // エラーメッセージを格納する配列

// バリデーション
if (empty($username)) {
    $errors[] = 'メールアドレスを入力してください。';
} elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $errors[] = '有効なメールアドレスを入力してください。';
}
if (empty($password)) {
    $errors[] = 'パスワードを入力してください。';
}

// エラーがなければ認証処理を実行
if (empty($errors)) {
    $db_client_user_info = new cclient_user_info();

    // メールアドレスでユーザー情報を取得
    // 注: cclient_user_infoにはget_client_user_by_emailが存在しますが、
    // ログイン用にはパスワードハッシュも取得する必要があるため、
    // 必要であれば contents_db.php に get_client_user_by_email_for_login のようなメソッドを追加する方が安全です。
    // 今回は既存の get_client_user_by_email を利用し、password_hashカラムも取得できることを前提とします。
    $user_data = $db_client_user_info->get_client_user_by_email($debug_mode, $username);

    if ($user_data) {
        // パスワードの照合
        if (password_verify($password, $user_data['password_hash'])) {
            // ログイン成功
            session_start();
            $_SESSION['client_id'] = $user_data['client_id'];
            $_SESSION['client_email'] = $user_data['email'];
            $_SESSION['company_name'] = $user_data['company_name'];
            // 必要に応じてその他のユーザー情報もセッションに保存

            // ログイン後のトップページなどへリダイレクト
            header('Location: client_top.php'); // 例: ログイン成功後のページ
            exit();
        } else {
            // パスワードが一致しない場合
            $errors[] = 'メールアドレスまたはパスワードが正しくありません。';
        }
    } else {
        // ユーザーが見つからない場合
        $errors[] = 'メールアドレスまたはパスワードが正しくありません。';
    }
}

// 認証失敗またはエラーがある場合、エラーメッセージをセッションに保存してログインページに戻す
if (!empty($errors)) {
    session_start();
    $_SESSION['login_error'] = implode('<br>', $errors); // 複数のエラーを改行で結合して表示
    $_SESSION['login_old_username'] = $username; // 入力されたユーザー名を保持
    header('Location: login.php');
    exit();
}
?>
