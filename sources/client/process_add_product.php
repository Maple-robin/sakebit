<?php
// ログインチェックとセッション開始を共通ファイルに任せます
require_once 'auth_check.php';

// データベース操作クラスを読み込みます
require_once '../common/contents_db.php';

// $client_id と $debug は auth_check.php で定義済みです

// POSTリクエストでなければフォームへ戻す
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: client_add_product.php');
    exit();
}


// --- バリデーション ---
$errors = [];
$old_data = $_POST;

if (empty($_POST['product_name'])) $errors[] = '商品名は必須です。';
// ... 他のテキストフィールドのバリデーションは省略 ...

// ★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★
// ★★★ ここからが新しい画像処理ロジック ★★★
// ★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★
$uploaded_image_info = [];
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
$upload_dir = '../img/';

// 1. メイン画像の処理
if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['main_image']['tmp_name'];
    $file_name = $_FILES['main_image']['name'];
    $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (in_array($extension, $allowed_extensions)) {
        $unique_name = uniqid('prod_main_') . '_' . time() . '.' . $extension;
        $destination = $upload_dir . $unique_name;
        $path_for_db = 'img/' . $unique_name;

        if (move_uploaded_file($tmp_name, $destination)) {
            $uploaded_image_info[] = [
                'path' => $path_for_db,
                'type' => 'main',
                'order' => 0
            ];
        } else {
            $errors[] = 'メイン画像のアップロードに失敗しました。ディレクトリの権限を確認してください。';
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
                $unique_name = uniqid('prod_sub_') . '_' . time() . '.' . $extension;
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
    $_SESSION['product_add_errors'] = $errors;
    $_SESSION['product_add_old_data'] = $old_data;
    header('Location: client_add_product.php');
    exit();
}

// --- DB登録処理 ---

$product_db = new cproduct_info();
$image_db = new cproduct_images();
$tag_relation_db = new cproduct_tags_relation();

$pdo = $product_db->get_pdo();

try {
    $pdo->beginTransaction();
    $new_product_id = $product_db->insert_product(
        $debug, $client_id, $_POST['product_name'], $_POST['price'], $_POST['category'],
        $_POST['description'], $_POST['features'], $_POST['recommendation'],
        $_POST['volume'], $_POST['stock'], $_POST['alcohol_percent']
    );

    if ($new_product_id === false) throw new Exception('商品情報の本体登録に失敗しました。');
    $pdo->commit();

    $pdo->beginTransaction();
    foreach ($uploaded_image_info as $img_info) {
        $image_insert_success = $image_db->insert_image(
            $debug, $new_product_id, $img_info['path'], $img_info['type'], $img_info['order']
        );
        if (!$image_insert_success) throw new Exception('画像情報のデータベース登録に失敗しました。');
    }

    foreach ($_POST['tags'] as $tag_id) {
        $tag_relation_db->insert_product_tag_relation($debug, $new_product_id, (int)$tag_id);
    }
    $pdo->commit();

    $_SESSION['product_add_success_message'] = '商品を正常に登録しました。';
    header('Location: client_top.php');
    exit();

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $errors[] = "商品登録中にエラーが発生しました: " . $e->getMessage();
    $_SESSION['product_add_errors'] = $errors;
    $_SESSION['product_add_old_data'] = $old_data;
    header('Location: client_add_product.php');
    exit();
}
