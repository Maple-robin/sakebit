<?php
// セッションを開始 (ファイルの先頭に必ず記述)
session_start();

// ★★★ 本番運用向け: デバッグ関連の設定を無効化 ★★★
// ini_set('display_errors', 0); // 本番環境ではエラーを画面に表示しない
// ini_set('display_startup_errors', 0); // 本番環境では起動時のエラーも表示しない
// error_reporting(E_ALL); // 全てのエラーレベルを表示 (開発時のみ有効にする)

// PHPの最大実行時間を延長 (必要に応じて。通常はデフォルトで十分ですが、大量の画像処理などがある場合は残しても良い)
// ini_set('max_execution_time', 300); // 300秒 (5分) に延長
// set_time_limit(300); // set_time_limit も併用

// ログイン状態のチェック
// $_SESSION['admin_user_id']が設定されていない、または空の場合はログインページにリダイレクト
if (!isset($_SESSION['admin_user_id']) || empty($_SESSION['admin_user_id'])) {
    header('Location: login.php'); // ログインページのパスに修正
    exit();
}

// PHPスクリプト全体でデバッグモードを有効にする/無効にする
// ★変更点: デバッグモードをtrueに設定 (確認用)
$debug = true; 

// contents_db.phpを読み込む
require_once '../common/contents_db.php';

// 画像アップロード先のディレクトリ
$upload_dir = '../img/'; 

// アップロードディレクトリが存在しない場合は作成を試みる
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true); 
}

// POSTリクエストかどうかを確認
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin_otsumami_add.php?error=invalid_method');
    exit();
}

// データベースクラスのインスタンスを生成
$db_otumami = new cotumami();
$db_otumami_images = new cotumami_images();
$db_otumami_tags = new cotumami_otumami_tags();

// --- 入力値の取得とバリデーション ---
$otsumami_name = $_POST['otsumami_name'] ?? '';
$category_id = $_POST['category'] ?? ''; // admin_otsumami_add.php の name="category" に合わせる
$stock = $_POST['stock'] ?? '';
$price = $_POST['price'] ?? '';
$desc1 = $_POST['desc1'] ?? '';
$desc2 = $_POST['desc2'] ?? '';
$selected_tags = $_POST['tags'] ?? [];

$errors = [];

// 必須項目のチェック
if (empty($otsumami_name)) {
    $errors[] = 'おつまみ名は必須です。';
}
if (empty($category_id)) {
    $errors[] = 'おつまみカテゴリーは必須です。';
}
// 修正: filter_varで数値かつ0以上を厳密にチェック
if (!is_numeric($stock) || $stock < 0 || !ctype_digit(strval($stock))) {
    $errors[] = '在庫数は0以上の整数で入力してください。';
}
// 修正: filter_varで数値かつ0以上を厳密にチェック
if (!is_numeric($price) || $price < 0) { 
    $errors[] = '価格は0以上の数値で入力してください。';
}
if (empty($desc1)) {
    $errors[] = 'おつまみ説明1は必須です。';
}

// 画像ファイルのチェック
$uploaded_image_info = []; 

// PHP 8.1+ では $_FILES['images']['name'] が存在しない場合に警告が出るためチェック
if (!isset($_FILES['images']['name'][0]) || empty($_FILES['images']['name'][0])) {
    $errors[] = 'おつまみ画像は最低1枚必須です。';
} else {
    $uploaded_file_count = 0;
    // まず、実際にファイルが選択された数を確認
    foreach ($_FILES['images']['name'] as $name) {
        if (!empty($name)) {
            $uploaded_file_count++;
        }
    }

    if ($uploaded_file_count === 0) {
        $errors[] = 'おつまみ画像は最低1枚必須です。';
    } elseif ($uploaded_file_count > 4) {
        $errors[] = 'おつまみ画像は最大4枚までです。';
    }

    // 各ファイルのアップロードエラーをチェック
    foreach ($_FILES['images']['error'] as $key => $error) {
        if ($error !== UPLOAD_ERR_OK && $error !== UPLOAD_ERR_NO_FILE) {
            $errors[] = '画像のアップロード中にエラーが発生しました (コード: ' . $error . ')。ファイル名: ' . ($_FILES['images']['name'][$key] ?? '不明');
        }
    }

    if (empty($errors)) { 
        // ★★★ デバッグログの追加 ★★★
        error_log("--- Image Upload Processing Start ---");
        error_log("Raw FILES array: " . print_r($_FILES, true));

        $display_order_for_upload = 0;
        foreach ($_FILES['images']['name'] as $key => $image_name) {
            // UPLOAD_ERR_NO_FILE はファイルが選択されなかった場合なのでスキップ
            if (!empty($image_name) && $_FILES['images']['error'][$key] === UPLOAD_ERR_OK && is_uploaded_file($_FILES['images']['tmp_name'][$key])) {
                $tmp_name = $_FILES['images']['tmp_name'][$key];
                $ext = pathinfo($image_name, PATHINFO_EXTENSION);
                $new_file_name = uniqid('otumami_') . '.' . $ext; 
                $target_file = $upload_dir . $new_file_name;
                $image_path_for_db = 'img/' . $new_file_name; 

                // ここで、実際にこのファイルに割り当てられる display_order と image_type をログに出力
                $current_image_type = ($display_order_for_upload === 0) ? 'main' : 'sub'; 
                error_log("File: " . $image_name . ", Temp Path: " . $tmp_name . ", Assigned display_order: " . $display_order_for_upload . ", Image Type: " . $current_image_type);

                if (move_uploaded_file($tmp_name, $target_file)) {
                    $uploaded_image_info[] = [
                        'path' => $image_path_for_db,
                        'type' => $current_image_type, // 上記で決定したタイプを使用
                        'order' => $display_order_for_upload
                    ];
                    $display_order_for_upload++; // 次の画像のためにインクリメント
                } else {
                    $errors[] = 'ファイルの実体アップロードに失敗しました。アップロードディレクトリの権限を確認してください: ' . $upload_dir . ' ファイル: ' . $image_name;
                    break; 
                }
            } elseif (!empty($image_name) && $_FILES['images']['error'][$key] !== UPLOAD_ERR_NO_FILE) {
                // その他のPHPアップロードエラー
                $errors[] = '画像のアップロード中にPHPエラーが発生しました (コード: ' . $_FILES['images']['error'][$key] . ')。ファイル名: ' . $image_name;
                break; 
            }
        }
        error_log("--- Image Upload Processing End ---");
    }
}


if (!empty($errors)) {
    // エラーがある場合はセッションに保存してフォームにリダイレクト
    $_SESSION['errors'] = $errors;
    $_SESSION['old_input'] = $_POST; 
    header('Location: admin_otsumami_add.php?status=error');
    exit();
}

// --- データベースへの挿入処理 ---

// トランザクションを分割
$pdo = $db_otumami->get_pdo();

try {
    // 1. おつまみ本体の登録 (最初のトランザクション)
    $pdo->beginTransaction();
    $otumami_id = $db_otumami->insert_otumami(
        $debug, 
        $category_id, 
        $otsumami_name,
        $price,
        $desc1, 
        $desc2, 
        $stock
    );

    if (!$otumami_id) {
        throw new Exception('おつまみ情報の本体登録に失敗しました。データベースエラーを確認してください。');
    }
    $pdo->commit(); // おつまみ本体の登録をコミットしてロックを解放

    // 2. 画像とタグの登録 (二番目のトランザクション)
    $pdo->beginTransaction();
    
    // otumami_images テーブルに挿入 (ファイルアップロードは既に完了)
    foreach ($uploaded_image_info as $img_info) {
        $image_insert_success = $db_otumami_images->insert_image(
            $debug, 
            $otumami_id,
            $img_info['path'],
            $img_info['type'],
            $img_info['order']
        );

        if (!$image_insert_success) {
            throw new Exception('画像情報のデータベース登録に失敗しました。'); 
        }
    }

    // otumami_otumami_tags テーブルに挿入 (選択されたタグがある場合)
    if (!empty($selected_tags)) {
        foreach ($selected_tags as $tag_id) {
            $tag_insert_success = $db_otumami_tags->insert_otumami_tag_relation(
                $debug, 
                $otumami_id,
                intval($tag_id) 
            );
            if (!$tag_insert_success) {
                throw new Exception('タグ情報のデータベース登録に失敗しました。');
            }
        }
    }

    $pdo->commit(); // 画像とタグの登録をコミット

    // 成功メッセージをセッションに保存し、リダイレクト
    $_SESSION['message'] = '新しいおつまみが正常に登録されました。';
    header('Location: admin_otsumami.php?status=success'); 
    exit();

} catch (\Throwable $e) { 
    if ($pdo->inTransaction()) { 
        $pdo->rollBack(); 
    }

    // エラーメッセージをセッションに保存し、リダイレクト
    $_SESSION['errors'] = ['おつまみの登録中にエラーが発生しました: ' . $e->getMessage()];
    $_SESSION['old_input'] = $_POST; 
    header('Location: admin_otsumami_add.php?status=error');
    exit();
}
?>
