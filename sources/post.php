<?php
/*!
@file post.php
@brief 新規投稿作成ページと処理
@copyright Copyright (c) 2024 Your Name.
*/

// ★注意: このページのPHPロジックはヘッダー出力前に実行する必要があるため、
// DB接続とセッション開始はこのファイルで先に行います。
// header.php内のrequire_onceとsession_start()は、重複実行が防止されるので問題ありません。

// セッションを開始 (HTML出力の前に置く)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// エラー表示設定 (開発中のみ)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 必要なファイルをインクルード
require_once __DIR__ . '/common/contents_db.php';
require_once __DIR__ . '/common/config.php';

// DEBUG_MODE が config.php で定義されていない場合はここで定義
if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', false); // 本番環境ではfalseに設定
}

// ログイン状態をチェック
if (!isset($_SESSION['user_id'])) {
    $_SESSION['login_message'] = ['text' => '投稿するにはログインが必要です。', 'type' => 'error'];
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$post_message = '';
$message_type = '';

if (isset($_SESSION['post_message'])) {
    $post_message = $_SESSION['post_message']['text'];
    $message_type = $_SESSION['post_message']['type'];
    unset($_SESSION['post_message']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_title = $_POST['post_title'] ?? '';
    $post_content = $_POST['post_content'] ?? '';
    $uploaded_files = $_FILES['post_images'] ?? null;

    if (empty($post_title) || empty($post_content)) {
        $_SESSION['post_message'] = ['text' => 'タイトルと内容の両方を入力してください。', 'type' => 'error'];
        header('Location: post.php');
        exit();
    }

    $uploaded_image_paths = [];
    $upload_dir = __DIR__ . '/img/'; 
    
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    if ($uploaded_files && isset($uploaded_files['name'][0]) && $uploaded_files['error'][0] !== UPLOAD_ERR_NO_FILE) {
        $max_files = 4;
        $total_files = count($uploaded_files['name']);

        if ($total_files > $max_files) {
            $_SESSION['post_message'] = ['text' => '画像は最大4枚まで選択できます。超過分は無視されます。', 'type' => 'error'];
        }

        for ($i = 0; $i < $total_files && $i < $max_files; $i++) {
            $file_name = $uploaded_files['name'][$i];
            $file_tmp_name = $uploaded_files['tmp_name'][$i];
            $file_error = $uploaded_files['error'][$i];
            $file_size = $uploaded_files['size'][$i];
            $file_type = $uploaded_files['type'][$i];

            if ($file_error !== UPLOAD_ERR_OK) {
                if ($file_error === UPLOAD_ERR_NO_FILE) continue;
                // エラーメッセージを設定してリダイレクト
                $_SESSION['post_message'] = ['text' => "画像のアップロード中にエラーが発生しました (コード: {$file_error})。", 'type' => 'error'];
                header('Location: post.php');
                exit();
            }

            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_file_size_bytes = 5 * 1024 * 1024; // 5MB

            if (!in_array($file_type, $allowed_types) || $file_size > $max_file_size_bytes) {
                $_SESSION['post_message'] = ['text' => '画像形式またはサイズが不正です。', 'type' => 'error'];
                header('Location: post.php');
                exit();
            }
            
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $new_file_name = uniqid('post_img_') . '.' . $extension;
            $destination_path = $upload_dir . $new_file_name;

            if (move_uploaded_file($file_tmp_name, $destination_path)) {
                $web_path = 'img/' . $new_file_name; 
                $uploaded_image_paths[] = ['path' => $web_path, 'order' => $i + 1];
            } else {
                $_SESSION['post_message'] = ['text' => 'ファイルの保存に失敗しました。', 'type' => 'error'];
                header('Location: post.php');
                exit();
            }
        }
    }

    try {
        $posts_db = new cposts();
        $post_id = $posts_db->insert_post(DEBUG_MODE, $user_id, $post_title, $post_content);

        if ($post_id) {
            $all_images_saved = true;
            if (!empty($uploaded_image_paths)) {
                $post_images_db = new cpost_images();
                foreach ($uploaded_image_paths as $image_data) {
                    if (!$post_images_db->insert_image(DEBUG_MODE, $post_id, $image_data['path'], $image_data['order'])) {
                        $all_images_saved = false;
                        error_log("Failed to insert image path for post_id: {$post_id}, path: {$image_data['path']}");
                    }
                }
            }

            if ($all_images_saved) {
                $_SESSION['post_message'] = ['text' => '投稿が完了しました！', 'type' => 'success'];
            } else {
                $_SESSION['post_message'] = ['text' => '投稿は完了しましたが、一部の画像の保存に失敗しました。', 'type' => 'error'];
            }
            header('Location: posts.php');
            exit();

        } else {
            $_SESSION['post_message'] = ['text' => '投稿の保存に失敗しました。', 'type' => 'error'];
            header('Location: post.php');
            exit();
        }
    } catch (Exception $e) {
        error_log("Post creation error: " . $e->getMessage());
        $_SESSION['post_message'] = ['text' => 'システムエラーが発生しました。', 'type' => 'error'];
        header('Location: post.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規投稿作成 | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="css/top.css">
    <link rel="stylesheet" href="css/post.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* カスタムメッセージボックスのスタイル */
        .custom-message-box {
            position: fixed; top: 20px; left: 50%; transform: translateX(-50%);
            padding: 15px 25px; border-radius: 8px; font-size: 1.6rem; color: #fff;
            z-index: 10000; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            opacity: 0; animation: fadeInOut 3s forwards; min-width: 300px; text-align: center;
        }
        .custom-message-box.success { background-color: #28a745; }
        .custom-message-box.error { background-color: #dc3545; }
        @keyframes fadeInOut {
            0%, 100% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
            10%, 90% { opacity: 1; transform: translateX(-50%) translateY(0); }
        }
        /* 画像プレビューのスタイル */
        #image-preview {
            display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px;
            justify-content: center; padding: 10px; border: 1px dashed #ccc;
            border-radius: 8px; min-height: 80px; align-items: center;
        }
        .post-image-preview {
            max-width: 100px; max-height: 100px; object-fit: contain;
            border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .image-upload-label {
            display: inline-flex; align-items: center; padding: 10px 20px;
            background-color: #f0f0f0; border: 2px dashed #007bff; border-radius: 8px;
            cursor: pointer; font-size: 1.1rem; color: #007bff;
            transition: background-color 0.3s ease; margin-top: 10px;
        }
        .image-upload-label:hover { background-color: #e2e6ea; }
        .image-upload-icon { font-size: 2rem; margin-right: 10px; line-height: 1; }
    </style>
</head>

<body>

    <?php 
    // 共通ヘッダーを読み込む
    require_once 'header.php'; 
    ?>

    <main>
        <section class="post-creation-section">
            <div class="post-form-container">
                <h2 class="section-title">
                    <span class="en">NEW POST</span>
                    <span class="ja">( 新規投稿作成 )</span>
                </h2>
                <form action="post.php" method="POST" id="postForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="post-title">タイトル</label>
                        <input type="text" id="post-title" name="post_title" placeholder="投稿のタイトルを入力してください" required>
                    </div>
                    <div class="form-group">
                        <label for="post-content">内容</label>
                        <textarea id="post-content" name="post_content" placeholder="こちらに投稿内容を入力してください"
                            required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="post-images" class="image-upload-label">
                            <span class="image-upload-icon">＋</span>
                            画像を追加（最大4枚）
                        </label>
                        <input type="file" id="post-images" name="post_images[]" accept="image/*" multiple style="display:none;">
                        <div id="image-preview" class="image-preview-simple"></div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="cancel-btn">キャンセル</button>
                        <button type="submit" class="submit-btn">投稿する</button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <?php 
    // 共通フッターを読み込む
    require_once 'footer.php'; 
    ?>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        const postPhpMessage = <?php echo json_encode($post_message); ?>;
        const postPhpMessageType = <?php echo json_encode($message_type); ?>;

        document.addEventListener('DOMContentLoaded', function () {
            function displayMessage(message, type) {
                if (!message) return;
                const messageBox = document.createElement('div');
                messageBox.classList.add('custom-message-box', type);
                messageBox.textContent = message;
                document.body.appendChild(messageBox);
                setTimeout(() => messageBox.remove(), 3000);
            }
            displayMessage(postPhpMessage, postPhpMessageType);
        });
    </script>
    <script src="js/post.js"></script>
</body>

</html>
