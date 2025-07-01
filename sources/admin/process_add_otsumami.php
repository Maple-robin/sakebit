<?php
session_start();

if (!isset($_SESSION['admin_user_id']) || empty($_SESSION['admin_user_id'])) {
    header('Location: login.php');
    exit();
}

$debug = false; 

require_once '../common/contents_db.php';

$upload_dir = '../img/'; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin_otsumami_add.php');
    exit();
}

$db_otumami = new cotumami();
$db_otumami_images = new cotumami_images();
$db_otumami_tags = new cotumami_otumami_tags();

$otsumami_name = $_POST['otsumami_name'] ?? '';
$category_id = $_POST['category'] ?? '';
$stock = $_POST['stock'] ?? '';
$price = $_POST['price'] ?? '';
$desc1 = $_POST['desc1'] ?? '';
$desc2 = $_POST['desc2'] ?? '';
$selected_tags = $_POST['tags'] ?? [];

$errors = [];
$old_input = $_POST;

if (empty($otsumami_name)) $errors[] = 'おつまみ名は必須です。';
if (empty($category_id)) $errors[] = 'おつまみカテゴリーは必須です。';
if (!is_numeric($stock) || $stock < 0 || !ctype_digit(strval($stock))) $errors[] = '在庫数は0以上の整数で入力してください。';
if (!is_numeric($price) || $price < 0) $errors[] = '価格は0以上の数値で入力してください。';
if (empty($desc1)) $errors[] = 'おつまみ説明1は必須です。';

// ★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★
// ★★★ ここからが新しい画像処理ロジック ★★★
// ★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★
$uploaded_image_info = [];
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

// 1. メイン画像の処理
if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['main_image']['tmp_name'];
    $file_name = $_FILES['main_image']['name'];
    $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (in_array($extension, $allowed_extensions)) {
        $unique_name = uniqid('otsumami_main_') . '_' . time() . '.' . $extension;
        $destination = $upload_dir . $unique_name;
        $path_for_db = 'img/' . $unique_name;

        if (move_uploaded_file($tmp_name, $destination)) {
            $uploaded_image_info[] = [
                'path' => $path_for_db,
                'type' => 'main',
                'order' => 0
            ];
        } else {
            $errors[] = 'メイン画像のアップロードに失敗しました。';
        }
    } else {
        $errors[] = 'メイン画像のファイル形式が許可されていません。';
    }
} else {
    $errors[] = 'メイン画像は必須です。';
}

// 2. サブ画像の処理
if (isset($_FILES['sub_images']['name'][0]) && !empty($_FILES['sub_images']['name'][0])) {
    $sub_image_count = count($_FILES['sub_images']['name']);
    if ($sub_image_count > 3) {
        $errors[] = 'サブ画像は3枚までです。';
    }

    $display_order_counter = 1;
    foreach ($_FILES['sub_images']['name'] as $key => $file_name) {
        if ($_FILES['sub_images']['error'][$key] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['sub_images']['tmp_name'][$key];
            $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (in_array($extension, $allowed_extensions)) {
                $unique_name = uniqid('otsumami_sub_') . '_' . time() . '.' . $extension;
                $destination = $upload_dir . $unique_name;
                $path_for_db = 'img/' . $unique_name;

                if (move_uploaded_file($tmp_name, $destination)) {
                    $uploaded_image_info[] = [
                        'path' => $path_for_db,
                        'type' => 'sub',
                        'order' => $display_order_counter
                    ];
                    $display_order_counter++;
                } else {
                    $errors[] = 'サブ画像のアップロードに失敗しました。';
                }
            } else {
                $errors[] = 'サブ画像「' . htmlspecialchars($file_name) . '」のファイル形式が許可されていません。';
            }
        }
    }
}

// ★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★
// ★★★          修正箇所ここまで          ★★★
// ★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★


if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old_input'] = $old_input; 
    header('Location: admin_otsumami_add.php');
    exit();
}

// --- データベースへの挿入処理 ---

$pdo = $db_otumami->get_pdo();

try {
    $pdo->beginTransaction();
    $otumami_id = $db_otumami->insert_otumami(
        $debug, $category_id, $otsumami_name, $price, $desc1, $desc2, $stock
    );

    if (!$otumami_id) throw new Exception('おつまみ情報の本体登録に失敗しました。');
    $pdo->commit();

    $pdo->beginTransaction();
    foreach ($uploaded_image_info as $img_info) {
        $image_insert_success = $db_otumami_images->insert_image(
            $debug, $otumami_id, $img_info['path'], $img_info['type'], $img_info['order']
        );
        if (!$image_insert_success) throw new Exception('画像情報のデータベース登録に失敗しました。');
    }

    if (!empty($selected_tags)) {
        foreach ($selected_tags as $tag_id) {
            $tag_insert_success = $db_otumami_tags->insert_otumami_tag_relation(
                $debug, $otumami_id, intval($tag_id)
            );
            if (!$tag_insert_success) throw new Exception('タグ情報のデータベース登録に失敗しました。');
        }
    }
    $pdo->commit();

    $_SESSION['message'] = '新しいおつまみが正常に登録されました。';
    header('Location: admin_otsumami.php'); 
    exit();

} catch (\Throwable $e) { 
    if ($pdo->inTransaction()) { 
        $pdo->rollBack(); 
    }
    $_SESSION['errors'] = ['おつまみの登録中にエラーが発生しました: ' . $e->getMessage()];
    $_SESSION['old_input'] = $_POST; 
    header('Location: admin_otsumami_add.php');
    exit();
}
