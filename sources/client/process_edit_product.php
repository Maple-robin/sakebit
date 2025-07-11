<?php
session_start();

// ログインチェックと共通設定の読み込み
require_once 'auth_check.php';
require_once '../common/contents_db.php';

// POSTリクエストでなければトップに戻す
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: client_top.php');
    exit();
}

// --- フォームデータの受け取りとバリデーション ---
$product_id = $_POST['product_id'] ?? null;
$product_name = $_POST['product_name'] ?? '';
$product_price = $_POST['price'] ?? '';
$product_category = $_POST['category'] ?? '';
$product_description = $_POST['description'] ?? '';
$product_discription = $_POST['features'] ?? '';
$product_How = $_POST['recommendation'] ?? '';
$product_Contents = $_POST['volume'] ?? '';
$product_stock = $_POST['stock'] ?? '';
$product_degree = $_POST['alcohol_percent'] ?? '';
$selected_tags = $_POST['tags'] ?? [];

$errors = [];
if (empty($product_id) || !is_numeric($product_id)) {
    $errors[] = '商品IDが不正です。';
}
if (empty($product_name)) $errors[] = '商品名は必須です。';
// ... (他の必須項目のバリデーションを追加) ...


// --- 画像アップロード処理 ---
$uploaded_image_info = [];
$upload_dir = '../img/'; 
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

// メイン画像が選択されているかチェック
$is_main_image_uploaded = isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK;
if ($is_main_image_uploaded) {
    $tmp_name = $_FILES['main_image']['tmp_name'];
    $file_name = $_FILES['main_image']['name'];
    $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    if (in_array($extension, $allowed_extensions)) {
        $unique_name = uniqid('prod_main_') . '_' . time() . '.' . $extension;
        if (move_uploaded_file($tmp_name, $upload_dir . $unique_name)) {
            $uploaded_image_info[] = ['path' => 'img/' . $unique_name, 'type' => 'main', 'order' => 0];
        } else { $errors[] = 'メイン画像のアップロードに失敗しました。'; }
    } else { $errors[] = 'メイン画像のファイル形式が許可されていません。'; }
}

// サブ画像が選択されているかチェック
$are_sub_images_uploaded = isset($_FILES['sub_images']['name'][0]) && !empty($_FILES['sub_images']['name'][0]);
if ($are_sub_images_uploaded) {
    $sub_image_count = count($_FILES['sub_images']['name']);
    if ($sub_image_count > 3) {
        $errors[] = 'サブ画像は3枚までです。';
    } else {
        $display_order_counter = 1;
        foreach ($_FILES['sub_images']['name'] as $key => $file_name) {
            if ($_FILES['sub_images']['error'][$key] === UPLOAD_ERR_OK) {
                $tmp_name = $_FILES['sub_images']['tmp_name'][$key];
                $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                if (in_array($extension, $allowed_extensions)) {
                    $unique_name = uniqid('prod_sub_') . '_' . time() . '.' . $extension;
                    if (move_uploaded_file($tmp_name, $upload_dir . $unique_name)) {
                        $uploaded_image_info[] = ['path' => 'img/' . $unique_name, 'type' => 'sub', 'order' => $display_order_counter++];
                    } else { $errors[] = 'サブ画像のアップロードに失敗しました。'; }
                } else { $errors[] = 'サブ画像「' . htmlspecialchars($file_name) . '」のファイル形式が許可されていません。'; }
            }
        }
    }
}


// バリデーションエラーがあれば、編集ページにリダイレクト
if (!empty($errors)) {
    $_SESSION['edit_errors'] = $errors;
    $_SESSION['edit_old_data'] = $_POST;
    header('Location: client_edit_product.php?id=' . $product_id);
    exit();
}

// --- データベース更新処理 ---
$db_product = new cproduct_info();
$db_images = new cproduct_images();
$db_tags_relation = new cproduct_tags_relation();
$pdo = $db_product->get_pdo();

try {
    // --- トランザクション1: 商品本体のテキスト情報更新 ---
    $pdo->beginTransaction();
    $db_product->update_product(
        $debug, $product_id, $product_name, $product_price, $product_category,
        $product_description, $product_discription, $product_How,
        $product_Contents, $product_stock, $product_degree
    );
    $pdo->commit();


    // --- トランザクション2: 画像とタグの更新 ---
    $pdo->beginTransaction();

    // ★★★ ここからが修正箇所 ★★★
    // 新しい画像が1枚でもアップロードされた場合のみ、既存の画像を削除して再登録
    if ($is_main_image_uploaded || $are_sub_images_uploaded) {
        $db_images->delete_images_by_product_id($debug, $product_id);
        foreach ($uploaded_image_info as $img_info) {
            $db_images->insert_image(
                $debug, $product_id, $img_info['path'], $img_info['type'], $img_info['order']
            );
        }
    }

    // タグ情報を更新 (一旦全削除して再登録)
    $db_tags_relation->delete_tags_by_product_id($debug, $product_id);
    if (!empty($selected_tags)) {
        foreach ($selected_tags as $tag_id) {
            $db_tags_relation->insert_product_tag_relation($debug, $product_id, (int)$tag_id);
        }
    }
    $pdo->commit();

    $_SESSION['message'] = ['type' => 'success', 'text' => '商品情報が正常に更新されました。'];
    header('Location: client_top.php');
    exit();

} catch (Exception $e) {
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['edit_errors'] = ['更新中にデータベースエラーが発生しました: ' . $e->getMessage()];
    $_SESSION['edit_old_data'] = $_POST;
    header('Location: client_edit_product.php?id=' . $product_id);
    exit();
}
