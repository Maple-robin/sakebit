<?php
/*!
@file process_signup.php
@brief 新規登録フォームからのデータを受け取り、ユーザー情報をデータベースに保存する
@copyright Copyright (c) 2024 Your Name.
*/

// config.php をインクルードしてDB接続定数を読み込む
// __DIR__ は現在のファイルのディレクトリを示します。
// config.php は一つ上の階層の 'common' ディレクトリ内にあるため、パスを修正します。
require_once __DIR__ . '/../common/config.php';

// contents_db.php をインクルードしてデータベース操作クラスを読み込む
// contents_db.php も一つ上の階層の 'common' ディレクトリ内にあるため、パスを修正します。
require_once __DIR__ . '/../common/contents_db.php';

// デバッグモードのオン/オフ
// config.php で定義されている DEBUG 定数を使用
$debug_mode = false;

// contents_db.php が正しく読み込まれ、cclient_user_info クラスが定義されているかチェック
if (!class_exists('cclient_user_info')) {
    // デバッグ情報としてエラーをログに出力
    error_log("FATAL ERROR: Class 'cclient_user_info' not found after including contents_db.php. " .
              "Check contents_db.php for syntax errors or incorrect internal paths.");
    // ユーザーには一般的なエラーメッセージを表示
    echo "<h1>システムエラーが発生しました。</h1>";
    echo "<p>現在、新規登録を受け付けることができません。時間をおいて再度お試しください。</p>";
    if ($debug_mode) {
        echo "<p>開発者向け情報: Class 'cclient_user_info' が見つかりませんでした。contents_db.phpのパスまたはファイル内容を確認してください。</p>";
    }
    exit(); // スクリプトを停止
}


// POSTリクエスト以外からのアクセスを拒否
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // 不正なアクセスの場合、サインアップページにリダイレクト
    header('Location: signup.php');
    exit();
}

// 入力データの取得とサニタイズ
// filter_input_array を使用して一括で安全に取得
// HTMLのname属性に合わせてキー名を変更
$input = filter_input_array(INPUT_POST, [
    'company-name'      => FILTER_SANITIZE_STRING,      // ハイフンに修正
    'representative-name' => FILTER_SANITIZE_STRING,    // ハイフンに修正
    'email'             => FILTER_SANITIZE_EMAIL,
    'phone'             => FILTER_SANITIZE_STRING,
    'address'           => FILTER_SANITIZE_STRING,
    'password'          => FILTER_UNSAFE_RAW,
    'password-confirm'  => FILTER_UNSAFE_RAW,
    'terms'             => FILTER_UNSAFE_RAW,           // チェックボックスはissetで確認するため、数値フィルタリングは不要
]);

// 各変数を抽出し、存在しない場合は空文字列を設定
// 抽出する変数名もハイフンからアンダースコアに変換（内部で扱いやすくするため）
$company_name       = $input['company-name'] ?? '';      // キー名を修正
$representative_name = $input['representative-name'] ?? ''; // キー名を修正
$email              = $input['email'] ?? '';
$phone              = $input['phone'] ?? '';
$address            = $input['address'] ?? '';
$password           = $input['password'] ?? '';
$password_confirm   = $input['password-confirm'] ?? '';
// 利用規約の同意チェックは、$_POST['terms']の存在で判断する
$terms_agreed       = isset($input['terms']); // チェックボックスが送信されたらtrue

$errors = []; // エラーメッセージを格納する配列

// --- バリデーション ---

// 必須項目のチェック
if (empty($company_name)) {
    $errors[] = '会社名を入力してください。';
}
if (empty($representative_name)) {
    $errors[] = '代表者名を入力してください。';
}
if (empty($email)) {
    $errors[] = 'メールアドレスを入力してください。';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = '有効なメールアドレスを入力してください。';
}
if (empty($phone)) {
    $errors[] = '電話番号を入力してください。';
}
if (empty($address)) {
    $errors[] = '住所を入力してください。';
}
if (empty($password)) {
    $errors[] = 'パスワードを入力してください。';
}
if (empty($password_confirm)) {
    $errors[] = '確認用パスワードを入力してください。';
}

// パスワードの一致チェック
if ($password !== $password_confirm) {
    $errors[] = 'パスワードと確認用パスワードが一致しません。';
}

// 利用規約の同意チェック
if (!$terms_agreed) {
    $errors[] = '利用規約に同意してください。';
}

// データベースオブジェクトの初期化
$db_client_user_info = new cclient_user_info();

// メールアドレスの重複チェック
if (empty($errors)) { // ここまでのエラーがない場合のみ実行
    $existing_user = $db_client_user_info->get_client_user_by_email($debug_mode, $email);
    if ($existing_user) {
        $errors[] = 'このメールアドレスは既に登録されています。';
    }
}

// エラーがなければ登録処理を実行
if (empty($errors)) {
    // パスワードのハッシュ化
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // データベースへの挿入
    $client_id = $db_client_user_info->insert_client_user(
        $debug_mode,
        $company_name,
        $representative_name,
        $email,
        $phone,
        $address,
        $password_hash
    );

    if ($client_id) {
        // 登録成功
        // セッションを開始し、ユーザーIDなどを保存することも検討
        // session_start();
        // $_SESSION['client_id'] = $client_id;
        // $_SESSION['client_email'] = $email;

        // 成功メッセージを表示するページ、またはログインページへリダイレクト
        header('Location: registration_success.php'); // 例: 登録成功ページ
        exit();
    } else {
        // 登録失敗（データベースエラーなど）
        $errors[] = 'ユーザー登録に失敗しました。時間をおいて再度お試しください。';
        // エラーをログに記録するなどの処理
        error_log("Client registration failed for email: " . $email);
    }
}

// エラーがある場合は、エラーメッセージをセッションに保存してサインアップページに戻す
if (!empty($errors)) {
    session_start(); // セッションを開始
    $_SESSION['signup_errors'] = $errors;
    $_SESSION['signup_old_data'] = [ // 入力値を保持して再表示するために保存
        'company_name'      => $company_name,
        'representative_name' => $representative_name,
        'email'             => $email,
        'phone'             => $phone,
        'address'           => $address,
    ];
    header('Location: signup.php');
    exit();
}
?>
