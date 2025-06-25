<?php
/*!
@file process_add_product.php
@brief 商品追加フォームからのデータを受け取り、商品情報をデータベースに保存し、画像をアップロードする
@copyright Copyright (c) 2024 Your Name.
*/

session_start(); // セッション開始

// config.php と contents_db.php をインクルード
// clientディレクトリ (`/home/j2025g/public_html/client/`) から見て、
// commonディレクトリ (`/home/j2025g/public_html/common/`) は一つ上の階層 (`../`) の中にある
require_once __DIR__ . '/../common/config.php';
require_once __DIR__ . '/../common/contents_db.php';

// デバッグモードのオン/オフ
// config.php で定義された DEBUG 定数を使用
$debug_mode = defined('DEBUG') ? DEBUG : false;

// 画像保存ディレクトリのパス
// `/home/j2025g/public_html/img/` に保存するため、`client` ディレクトリから見て一つ上の階層の `img` ディレクトリを指定
$upload_dir = __DIR__ . '/../img/';
if (!is_dir($upload_dir)) {
    // ディレクトリが存在しない場合は作成を試みる
    // 注意: 本番環境では mkdir のパーミッションを 0755 など、より厳密に設定することを強く推奨します。
    // 0777 は開発環境用です。
    mkdir($upload_dir, 0777, true);
}

// POSTリクエスト以外からのアクセスを拒否
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: client_add_product.php');
    exit();
}

$errors = []; // エラーメッセージを格納する配列
$old_data = []; // 入力値を保持する配列

// 入力データの取得とサニタイズ
// FILTER_SANITIZE_STRING は非推奨のため、FILTER_UNSAFE_RAW で取得し、別途 htmlspecialchars() でサニタイズします。
$product_name       = htmlspecialchars(filter_input(INPUT_POST, 'product_name', FILTER_UNSAFE_RAW) ?? '', ENT_QUOTES, 'UTF-8');
$description        = htmlspecialchars(filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW) ?? '', ENT_QUOTES, 'UTF-8');
$price              = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
$category_id        = filter_input(INPUT_POST, 'category', FILTER_VALIDATE_INT);
$features           = htmlspecialchars(filter_input(INPUT_POST, 'features', FILTER_UNSAFE_RAW) ?? '', ENT_QUOTES, 'UTF-8');       // product_discription に対応
$recommendation     = htmlspecialchars(filter_input(INPUT_POST, 'recommendation', FILTER_UNSAFE_RAW) ?? '', ENT_QUOTES, 'UTF-8'); // product_How に対応
$volume             = htmlspecialchars(filter_input(INPUT_POST, 'volume', FILTER_UNSAFE_RAW) ?? '', ENT_QUOTES, 'UTF-8');         // product_Contents に対応
$alcohol_percent    = filter_input(INPUT_POST, 'alcohol_percent', FILTER_VALIDATE_FLOAT);
$stock              = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);
// タグは配列で来るため、FILTER_VALIDATE_INTを各要素に適用し、FILTER_REQUIRE_ARRAYで配列として取得
$selected_tags      = filter_input(INPUT_POST, 'tags', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);

// 旧データを保存（エラー時にフォームに再表示するため）
// 価格や度数、在庫数などは入力された生の文字列を保持することで、ユーザーの入力そのままを再表示
$old_data = [
    'product_name'      => $product_name,
    'description'       => $description,
    'price'             => $_POST['price'] ?? '', // filter_inputの結果ではなく、POSTの元の値を使用
    'category'          => $_POST['category'] ?? '', // filter_inputの結果ではなく、POSTの元の値を使用
    'features'          => $features,
    'recommendation'    => $recommendation,
    'volume'            => $volume,
    'alcohol_percent'   => $_POST['alcohol_percent'] ?? '', // filter_inputの結果ではなく、POSTの元の値を使用
    'stock'             => $_POST['stock'] ?? '', // filter_inputの結果ではなく、POSTの元の値を使用
    'tags'              => $selected_tags,
    'image_names'       => [], // アップロードされたファイル名（表示用）
    'image_paths'       => [], // アップロードされたファイルパス（プレビュー用）
];

// --- バリデーション ---
if (empty($product_name)) { $errors[] = '商品名を入力してください。'; }
if (empty($description)) { $errors[] = '商品説明を入力してください。'; }
// 価格のバリデーション
if ($price === false || $price < 0) {
    $errors[] = '有効な価格を入力してください。(0以上の数値)';
} elseif (!is_numeric($_POST['price'])) { // 文字列としての入力値もチェック
    $errors[] = '価格は数値で入力してください。';
}
if ($category_id === false || $category_id <= 0) { $errors[] = 'カテゴリーを選択してください。'; }
if (empty($features)) { $errors[] = '商品の特徴を入力してください。'; }
if (empty($recommendation)) { $errors[] = 'おすすめの飲み方を入力してください。'; }
if (empty($volume)) { $errors[] = '内容量を入力してください。'; }
// アルコール度数のバリデーション
if ($alcohol_percent === false || $alcohol_percent < 0 || $alcohol_percent > 100) {
    $errors[] = '有効なアルコール度数を入力してください。(0〜100%の数値)';
} elseif (!is_numeric($_POST['alcohol_percent'])) { // 文字列としての入力値もチェック
    $errors[] = '度数は数値で入力してください。';
}
// 在庫数のバリデーション
if ($stock === false || $stock < 0) {
    $errors[] = '有効な在庫数を入力してください。(0以上の整数)';
} elseif (!is_numeric($_POST['stock'])) { // 文字列としての入力値もチェック
    $errors[] = '在庫数は数値で入力してください。';
}
if (empty($selected_tags) || !is_array($selected_tags)) { $errors[] = 'タグを少なくとも一つ選択してください。'; }


// ファイルアップロードの処理
$uploaded_files_info = []; // 実際にサーバーに保存されたファイルのパスと名前を記録
$image_error_flag = false;

// PHP 8.1+ の $_FILES 構造に対応し、空のファイル入力も適切に処理
if (isset($_FILES['product_image']) && is_array($_FILES['product_image']['name'])) {
    $total_files_sent = count($_FILES['product_image']['name']);
    $actual_uploaded_files_count = 0;

    for ($i = 0; $i < $total_files_sent; $i++) {
        $file_tmp_name = $_FILES['product_image']['tmp_name'][$i];
        $file_name     = $_FILES['product_image']['name'][$i];
        $file_type     = $_FILES['product_image']['type'][$i];
        $file_error    = $_FILES['product_image']['error'][$i];
        $file_size     = $_FILES['product_image']['size'][$i];

        // ファイルが選択されていない、または空の場合はスキップ
        if ($file_error === UPLOAD_ERR_NO_FILE || empty($file_name)) {
            continue;
        }

        $actual_uploaded_files_count++;

        if ($file_error !== UPLOAD_ERR_OK) {
            $errors[] = '画像のアップロード中にエラーが発生しました: ' . htmlspecialchars($file_name) . ' (エラーコード: ' . $file_error . ')';
            $image_error_flag = true;
            continue;
        }

        // ファイルタイプとサイズチェック
        $allowed_types = ['image/png', 'image/jpeg', 'image/jpg'];
        $max_size = 5 * 1024 * 1024; // 5MB

        if (!in_array($file_type, $allowed_types)) {
            $errors[] = '無効な画像ファイル形式です: ' . htmlspecialchars($file_name) . '。PNG, JPEG, JPGのみ許可されています。';
            $image_error_flag = true;
        }
        if ($file_size > $max_size) {
            $errors[] = '画像ファイルサイズが大きすぎます: ' . htmlspecialchars($file_name) . '。最大5MBです。';
            $image_error_flag = true;
        }

        if (!$image_error_flag) {
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $new_file_name = uniqid('product_', true) . '.' . $extension;
            $destination_server_path = $upload_dir . $new_file_name; // サーバー上の絶対パス
            $destination_web_path = '../img/' . $new_file_name; // ウェブからアクセスできる相対パス

            if (move_uploaded_file($file_tmp_name, $destination_server_path)) {
                $uploaded_files_info[] = [
                    'server_path' => $destination_server_path, // unlink用
                    'web_path'    => $destination_web_path,    // DB保存用およびプレビュー用
                    'original_name' => $file_name
                ];
                $old_data['image_names'][] = $file_name;
                $old_data['image_paths'][] = $destination_web_path;
            } else {
                $errors[] = 'ファイルの移動に失敗しました: ' . htmlspecialchars($file_name) . '。ディレクトリの権限を確認してください: ' . $upload_dir;
                $image_error_flag = true;
            }
        }
    }

    // アップロードされたファイルの総数をチェック
    if ($actual_uploaded_files_count === 0) {
        $errors[] = '商品画像を少なくとも1枚アップロードしてください。';
    } elseif ($actual_uploaded_files_count > 4) {
        $errors[] = '商品画像は最大4枚までです。';
    }

} else {
    $errors[] = '商品画像が送信されませんでした。';
    $image_error_flag = true;
}


// エラーがある場合は、エラーメッセージと入力値をセッションに保存してフォームに戻す
if (!empty($errors)) {
    $_SESSION['product_add_errors'] = $errors;
    $_SESSION['product_add_old_data'] = $old_data;
    header('Location: client_add_product.php');
    exit();
}

// ここからはデータベース処理
$db_product_info = new cproduct_info();
$db_product_images = new cproduct_images();
$db_product_tags_relation = new cproduct_tags_relation();

$pdo = $db_product_info->get_pdo(); // トランザクションのためにPDOインスタンスを取得

try {
    // 1. 商品本体の登録 (最初のトランザクション)
    $pdo->beginTransaction();
    $product_id = $db_product_info->insert_product(
        $debug_mode,
        $product_name,
        $price,
        $category_id,
        $description,
        $features,
        $recommendation,
        $volume,
        $stock,
        $alcohol_percent
    );

    if (!$product_id) {
        throw new Exception('商品情報の本体登録に失敗しました。');
    }
    $pdo->commit(); // 商品本体の登録をコミットしてロックを解放

    // 2. 画像とタグの登録 (二番目のトランザクション)
    // $pdo->beginTransaction(); // product_imagesの insert_image 内でPDO操作があるため、再度のbeginTransactionは不要

    // product_images への挿入
    $display_order = 0;
    foreach ($uploaded_files_info as $file_info) {
        $image_type = ($display_order === 0) ? 'main' : 'sub'; // 最初の画像をメインに設定
        $image_insert_success = $db_product_images->insert_image(
            $debug_mode,
            $product_id,
            $file_info['web_path'], // DBにはウェブからアクセスできる相対パスを保存
            $image_type,
            $display_order
        );
        if (!$image_insert_success) {
            throw new Exception('商品画像情報のデータベース保存に失敗しました。');
        }
        $display_order++;
    }

    // product_tags_relation への挿入
    if (!empty($selected_tags) && is_array($selected_tags)) {
        foreach ($selected_tags as $tag_id) {
            $tag_insert_success = $db_product_tags_relation->insert_product_tag_relation(
                $debug_mode,
                $product_id,
                $tag_id
            );
            if (!$tag_insert_success) {
                throw new Exception('商品タグ関連情報のデータベース保存に失敗しました。');
            }
        }
    }

    // $pdo->commit(); // ここでコミットすると、外側のトランザクションが閉じられるため、内部ではコミットしない
    // 上記のbeginTransaction()とcommit()は、もし`contents_db.php`の`execute_query`が
    // 独自のトランザクション管理を行っていない場合に有効です。
    // 現在のcontents_db.phpのPDOインスタンスは共有されているため、
    // $db_product_images->insert_image や $db_product_tags_relation->insert_product_tag_relation
    // の内部でPDO操作が行われた場合、それらは $pdo->beginTransaction() で開始された
    // 外側のトランザクションに自動的に含まれます。

    // しかし、otsumamiの例ではトランザクションを分割していたため、
    // ここもotsumamiの例に倣い、2回目のコミットが適切です。
    // ただし、現在のPDOインスタンス取得方法では、$db_product_info, $db_product_images, $db_product_tags_relation
    // 全てが同じPDOインスタンスを共有しているため、$pdo->commit() は全ての操作に影響します。
    // そのため、ここでは2回目のトランザクションを明示的に開始・コミットする必要があることに注意が必要です。
    // 現在のコードは、$pdo->beginTransaction() が一度しか呼ばれていないため、
    // 全体が単一のトランザクションとして扱われています。
    // おつまみ同様に2つのトランザクションに完全に分割したい場合は、
    // $db_product_images と $db_product_tags_relation で新しいPDOインスタンスを取得するか、
    // $pdo->commit() をここで呼び出し、次に $pdo->beginTransaction() を再度呼び出す必要があります。
    // 今回は、おつまみと同様の挙動を目指し、トランザクションを明確に分割します。
    // cproduct_images と cproduct_tags_relation のコンストラクタは `parent::__construct()` を呼び出し、
    // 新しいPDOインスタンスを取得しているため、以下のように修正することで、個別のトランザクションにできます。

    // ---- 修正されたトランザクションロジック ----
    // `cproduct_images`と`cproduct_tags_relation`が独自のPDOインスタンスを持つ場合
    // 各DBクラスのコンストラクタが新しいPDOインスタンスを生成しているか確認
    // -> はい、crecordの__constructがPDOインスタンスを生成しているので、各クラスは独立したPDOを持っています。
    // そのため、以前のロックの問題はおつまみと商品のDBアクセスが別のスクリプト、
    // または異なるタイミングで実行されたことによるものかもしれません。
    // しかし、今回の目的は「おつまみの成功例」に倣うことなので、
    // 明示的に2つのトランザクションとして扱えるようにロジックを調整します。

    // 現状、$db_product_info->get_pdo() で取得したPDOインスタンスで全体を制御しています。
    // これを、おつまみ処理のように、2つのステップでコミットするために、
    // 最初のコミット後、2番目の処理の前に再度beginTransaction()とcommit()を呼び出します。
    // ただし、このやり方はテーブルレベルのロック競合が原因の場合、
    // `SET autocommit = 0;` のような設定がない限り、各`execute_query`が独立したトランザクションになる可能性があります。

    // ユーザーが提供したおつまみ処理の成功例:
    // process_add_otsumami.php
    // $pdo = $db_otumami->get_pdo(); // otumami_categories, otumami_tagsは別インスタンス
    // $pdo->beginTransaction();
    // $otumami_id = $db_otumami->insert_otumami(...);
    // $pdo->commit();
    // $pdo->beginTransaction(); // 再度beginTransaction
    // foreach ($uploaded_image_info as $img_info) { $db_otumami_images->insert_image(...); }
    // if (!empty($selected_tags)) { foreach ($selected_tags as $tag_id) { $db_otumami_tags->insert_otumami_tag_relation(...); }}
    // $pdo->commit();

    // これと同じロジックを product に適用します。
    // 注意：`get_pdo()`が常に同じPDOインスタンスを返すとは限らないため、各DBクラスのインスタンスが
    // 独立したPDOインスタンスを持つ場合（crecordのコンストラクタが毎回PDOを生成している場合）、
    // この分割は意味を持ちます。そうでなければ、PDOインスタンスを共有するシングルトンパターンなどを検討すべきです。
    // 今までの動作から、crecordの__constructが毎回PDOを生成していると仮定します。

    $pdo->commit(); // ここで最初のトランザクションをコミット

    // product_images と product_tags_relation はそれぞれ新しいPDOインスタンスを持つので、
    // 個別にコミット可能。ただし、ここでは明示的にPDOインスタンスを再取得してトランザクションを再開。
    // もし$db_product_imagesと$db_product_tags_relationが$pdoと同じPDOインスタンスを共有していない場合は、
    // それぞれのクラス内でトランザクションを管理するか、単一のトランザクションで全てを囲むべきです。
    // しかし、これまでのエラーがLock wait timeoutであることから、共有のPDOインスタンスを前提とした
    // 単一トランザクション内でテーブルロック競合が起きている可能性が高いと判断し、
    // おつまみと同様の二段階コミット戦略を試みます。

    // ここで再度beginTransactionを行うのは、上記の$pdoが指すトランザクションを継続するため。
    // もし$db_product_imagesや$db_product_tags_relationが独自のPDOインスタンスを持っているなら、
    // ここでこれらのインスタンスからPDOを取得し、それぞれにbeginTransaction/commitを呼び出すべき。
    // 現状は$pdoが共有されていると解釈し、トランザクションを再開。

    $pdo->beginTransaction(); // 2つ目のトランザクションを再開

    // product_images への挿入 (この操作は上記のトランザクションに含まれる)
    $display_order = 0;
    foreach ($uploaded_files_info as $file_info) {
        $image_type = ($display_order === 0) ? 'main' : 'sub';
        $image_insert_success = $db_product_images->insert_image(
            $debug_mode,
            $product_id,
            $file_info['web_path'],
            $image_type,
            $display_order
        );
        if (!$image_insert_success) {
            throw new Exception('商品画像情報のデータベース保存に失敗しました。');
        }
        $display_order++;
    }

    // product_tags_relation への挿入 (この操作も上記のトランザクションに含まれる)
    if (!empty($selected_tags) && is_array($selected_tags)) {
        foreach ($selected_tags as $tag_id) {
            $tag_insert_success = $db_product_tags_relation->insert_product_tag_relation(
                $debug_mode,
                $product_id,
                $tag_id
            );
            if (!$tag_insert_success) {
                throw new Exception('商品タグ関連情報のデータベース保存に失敗しました。');
            }
        }
    }

    $pdo->commit(); // 2つ目のトランザクションをコミット
    $_SESSION['product_add_success_message'] = '商品が正常に登録されました。';
    header('Location: client_add_product.php');
    exit();

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    foreach ($uploaded_files_info as $file_info) {
        if (file_exists($file_info['server_path'])) {
            unlink($file_info['server_path']);
        }
    }
    error_log("商品登録エラー: " . $e->getMessage());
    $errors[] = '商品登録中にエラーが発生しました: ' . $e->getMessage();
    $_SESSION['product_add_errors'] = $errors;
    $_SESSION['product_add_old_data'] = $old_data;
    header('Location: client_add_product.php');
    exit();
}

?>
