<?php
/*!
@file post.php
@brief 新規投稿作成ページと処理
@copyright Copyright (c) 2024 Your Name.
*/

// セッションを開始 (HTML出力の前に置く)
session_start();

// エラー表示設定 (開発中のみ)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// contents_db.php など、必要なファイルをインクルード
require_once __DIR__ . '/common/contents_db.php';
require_once __DIR__ . '/common/config.php'; // config.phpもインクルード

// DEBUG_MODE が config.php で定義されていない場合はここで定義
if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', false); // 本番環境ではfalseに設定
}

// ログイン状態をチェック
// ユーザーIDがセッションに存在しない場合はログインページへリダイレクト
if (!isset($_SESSION['user_id'])) {
    // ログインページへリダイレクトし、メッセージを表示する
    $_SESSION['login_message'] = ['text' => '投稿するにはログインが必要です。', 'type' => 'error'];
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id']; // ログインしているユーザーのID

$post_message = '';
$message_type = '';

// セッションにメッセージがあれば取得し、一度だけ表示
if (isset($_SESSION['post_message'])) {
    $post_message = $_SESSION['post_message']['text'];
    $message_type = $_SESSION['post_message']['type'];
    unset($_SESSION['post_message']); // 表示後削除
}

// POSTリクエストがある場合のみ処理を実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_title = $_POST['post_title'] ?? '';
    $post_content = $_POST['post_content'] ?? '';
    $uploaded_files = $_FILES['post_images'] ?? null;

    // 入力値のバリデーション
    if (empty($post_title) || empty($post_content)) {
        $_SESSION['post_message'] = ['text' => 'タイトルと内容の両方を入力してください。', 'type' => 'error'];
        header('Location: post.php');
        exit();
    }

    $uploaded_image_paths = []; // アップロードされた画像のパスを格納する配列
    // 画像保存ディレクトリを変更: /home/j2025g/public_html/img/
    $upload_dir = __DIR__ . '/img/'; 
    
    // アップロードディレクトリが存在しない場合は作成
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true); // 0755は一般的なパーミッション
    }

    // ファイルアップロードの処理
    // ファイルが選択されていない場合でも投稿自体は可能とするため、このブロック全体をスキップする条件を追加
    if ($uploaded_files && isset($uploaded_files['name'][0]) && $uploaded_files['error'][0] !== UPLOAD_ERR_NO_FILE) {
        $max_files = 4;
        $total_files = count($uploaded_files['name']);

        if ($total_files > $max_files) {
            $_SESSION['post_message'] = ['text' => '画像は最大4枚まで選択できます。超過分は無視されます。', 'type' => 'error'];
            // 処理を継続するために、最初のmax_files分のみを扱う
            // header('Location: post.php'); // ここではリダイレクトせず、処理を続行
            // exit();
        }

        // 実際に処理するファイルの数を制限
        for ($i = 0; $i < $total_files && $i < $max_files; $i++) { // ここで $i < $max_files を追加
            $file_name = $uploaded_files['name'][$i];
            $file_tmp_name = $uploaded_files['tmp_name'][$i];
            $file_error = $uploaded_files['error'][$i];
            $file_size = $uploaded_files['size'][$i];
            $file_type = $uploaded_files['type'][$i];

            // アップロードエラーチェック
            if ($file_error !== UPLOAD_ERR_OK) {
                // UPLOAD_ERR_NO_FILE はここで処理されないようにする (ファイルの有無チェックはPHPスクリプト上部で行う)
                if ($file_error === UPLOAD_ERR_NO_FILE) {
                    continue; // ファイルが選択されていない場合はスキップ
                }
                $error_msg = "画像のアップロード中にエラーが発生しました: ";
                switch ($file_error) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $error_msg .= "ファイルサイズが大きすぎます。";
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $error_msg .= "ファイルの一部しかアップロードされませんでした。";
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $error_msg .= "一時フォルダがありません。";
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $error_msg .= "ディスクへの書き込みに失敗しました。";
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $error_msg .= "PHP拡張機能によりアップロードが停止しました。";
                        break;
                    default:
                        $error_msg .= "不明なエラー (コード: {$file_error})。";
                        break;
                }
                $_SESSION['post_message'] = ['text' => $error_msg, 'type' => 'error'];
                header('Location: post.php');
                exit();
            }

            // 画像ファイルタイプとサイズのバリデーション (必要に応じて強化)
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_file_size_bytes = 5 * 1024 * 1024; // 5MB

            if (!in_array($file_type, $allowed_types)) {
                $_SESSION['post_message'] = ['text' => 'サポートされていない画像形式です。JPEG, PNG, GIFのみ許可されています。', 'type' => 'error'];
                header('Location: post.php');
                exit();
            }
            if ($file_size > $max_file_size_bytes) {
                $_SESSION['post_message'] = ['text' => '画像のサイズが大きすぎます。5MB以下にしてください。', 'type' => 'error'];
                header('Location: post.php');
                exit();
            }
            
            // ユニークなファイル名を生成
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $new_file_name = uniqid('post_img_') . '.' . $extension;
            $destination_path = $upload_dir . $new_file_name;

            // ファイルを移動
            if (move_uploaded_file($file_tmp_name, $destination_path)) {
                // データベースに保存するパスは、Webからアクセス可能な相対パス
                $web_path = 'img/' . $new_file_name; 
                $uploaded_image_paths[] = ['path' => $web_path, 'order' => $i + 1]; // 1から始まる順序
            } else {
                $_SESSION['post_message'] = ['text' => 'ファイルの保存に失敗しました。ディレクトリのパーミッションを確認してください。', 'type' => 'error'];
                header('Location: post.php');
                exit();
            }
        }
    }

    // データベース操作
    try {
        $posts_db = new cposts();
        $post_id = $posts_db->insert_post(DEBUG_MODE, $user_id, $post_title, $post_content);

        if ($post_id) {
            $post_images_db = new cpost_images();
            $all_images_saved = true;

            // 画像がアップロードされている場合のみ、post_imagesテーブルに挿入
            if (!empty($uploaded_image_paths)) {
                foreach ($uploaded_image_paths as $image_data) {
                    if (!$post_images_db->insert_image(DEBUG_MODE, $post_id, $image_data['path'], $image_data['order'])) {
                        $all_images_saved = false;
                        error_log("Failed to insert image path for post_id: {$post_id}, path: {$image_data['path']}");
                        // この時点で投稿はできているが、画像の一部または全てが保存できなかった場合
                        // トランザクション処理を導入するか、エラーリカバリを検討する必要がある
                    }
                }
            }

            if ($all_images_saved) {
                $_SESSION['post_message'] = ['text' => '投稿が完了しました！', 'type' => 'success'];
                header('Location: index.php'); // 投稿成功後はトップページなどへリダイレクト
                exit();
            } else {
                // 画像が一つも選択されていないが、投稿自体は成功した場合のメッセージ
                // または、一部の画像の保存に失敗した場合のメッセージ
                if (empty($uploaded_image_paths)) {
                    $_SESSION['post_message'] = ['text' => '投稿が完了しました！', 'type' => 'success']; // 画像なしでの投稿成功
                } else {
                    $_SESSION['post_message'] = ['text' => '投稿は完了しましたが、一部の画像の保存に失敗しました。', 'type' => 'error'];
                }
                header('Location: post.php'); // 失敗時は投稿ページに戻る
                exit();
            }
        } else {
            $_SESSION['post_message'] = ['text' => '投稿の保存に失敗しました。', 'type' => 'error'];
            header('Location: post.php');
            exit();
        }
    } catch (Exception $e) {
        error_log("Post creation error: " . $e->getMessage());
        $_SESSION['post_message'] = ['text' => 'システムエラーが発生しました。しばらくしてから再度お試しください。', 'type' => 'error'];
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
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px 25px;
            border-radius: 8px;
            font-size: 1.6rem;
            color: #fff;
            z-index: 10000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            opacity: 0;
            animation: fadeInOut 3s forwards;
            min-width: 300px;
            text-align: center;
        }
        .custom-message-box.success {
            background-color: #28a745; /* 緑色 */
        }
        .custom-message-box.error {
            background-color: #dc3545; /* 赤色 */
        }
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
            10% { opacity: 1; transform: translateX(-50%) translateY(0); }
            90% { opacity: 1; transform: translateX(-50%) translateY(0); }
            100% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
        }
        /* 画像プレビューのスタイルを追加 */
        #image-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
            justify-content: center; /* 中央寄せ */
            padding: 10px;
            border: 1px dashed #ccc; /* 枠線 */
            border-radius: 8px;
            min-height: 80px; /* ある程度の高さを確保 */
            align-items: center; /* 縦方向の中央寄せ */
        }
        .post-image-preview {
            max-width: 100px; /* プレビュー画像の最大幅 */
            max-height: 100px; /* プレビュー画像の最大高さ */
            object-fit: contain; /* アスペクト比を保ちつつ収まるように */
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .image-upload-label {
            display: inline-flex; /* flexboxでアイコンとテキストを横並び */
            align-items: center; /* 垂直方向の中央揃え */
            padding: 10px 20px;
            background-color: #f0f0f0;
            border: 2px dashed #007bff;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            color: #007bff;
            transition: background-color 0.3s ease;
            margin-top: 10px; /* 上の要素との間隔 */
        }
        .image-upload-label:hover {
            background-color: #e2e6ea;
        }
        .image-upload-icon {
            font-size: 2rem; /* アイコンのサイズ */
            margin-right: 10px; /* アイコンとテキストの間隔 */
            line-height: 1; /* アイコンの行の高さを調整 */
        }
    </style>
</head>

<body>

    <!-- 共通ヘッダー：index.phpからコピー -->
    <header class="header">
        <div class="header__inner">
            <!-- ハンバーガーメニューを左端に配置 -->
            <button class="hamburger-menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <!-- ロゴを中央に配置 -->
            <h1 class="header__logo">
                <a href="index.php">OUR BRAND</a>
            </h1>
            <!-- ナビゲーションとアイコンを右端に配置 -->
            <nav class="header__nav">
                <ul class="nav__list pc-only">
                    <li><a href="products_list.php">商品一覧</a></li>
                    <li><a href="contact.php">お問い合わせ</a></li>
                </ul>
                <div class="header__icons">
                    <a href="wishlist.php" class="header__icon-link">
                        <i class="fas fa-heart"></i>
                    </a>
                    <a href="cart.php" class="header__icon-link">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <!-- スマホ用メニューも必要なら同様に -->
    <nav class="sp-menu">
        <div class="sp-menu__header">
            <?php if (isset($_SESSION['user_id'])): // ログイン状態をチェック ?>
                <a href="logout.php" class="sp-menu__login" style="cursor:pointer;">
                    <i class="fas fa-user-circle"></i> ログアウト
                </a>
            <?php else: ?>
                <a href="login.php" class="sp-menu__login js-login-btn" style="cursor:pointer;">
                    <i class="fas fa-user-circle"></i> ログイン
                </a>
            <?php endif; ?>
        </div>
        <div class="sp-menu__search">
            <input type="text" placeholder="検索...">
            <button type="submit"><i class="fas fa-search"></i></button>
        </div>
        <ul class="sp-menu__list">
            <li class="sp-menu__category-toggle">
                商品カテゴリ <i class="fas fa-chevron-down category-icon"></i>
                <ul class="sp-menu__sub-list">
                    <li><a href="products_list.php?category=日本酒">日本酒</a></li>
                    <li><a href="products_list.php?category=中国酒">中国酒</a></li>
                    <li><a href="products_list.php?category=梅酒">梅酒</a></li>
                    <li><a href="products_list.php?category=缶チューハイ">缶チューハイ</a></li>
                    <li><a href="products_list.php?category=焼酎">焼酎</a></li>
                    <li><a href="products_list.php?category=ウィスキー">ウィスキー</a></li>
                    <li><a href="products_list.php?category=スピリッツ">スピリッツ</a></li>
                    <li><a href="products_list.php?category=リキュール">リキュール</a></li>
                    <li><a href="products_list.php?category=ワイン">ワイン</a></li>
                    <li><a href="products_list.php?category=ビール">ビール</a></li>
                </ul>
            </li>
            <li class="sp-menu__category-toggle">
                商品タグ <i class="fas fa-chevron-down category-icon"></i>
                <ul class="sp-menu__sub-list">
                    <li><a href="products_list.php?tag=初心者向け">初心者向け</a></li>
                    <li><a href="products_list.php?tag=甘口">甘口</a></li>
                    <li><a href="products_list.php?tag=辛口">辛口</a></li>
                    <li><a href="products_list.php?tag=度数低め">度数低め</a></li>
                    <li><a href="products_list.php?tag=度数高め">度数高め</a></li>
                </ul>
            </li>
            <li class="sp-menu__item"><a href="posts.php">投稿ページ</a></li>
            <li class="sp-menu__item"><a href="MyPage.php">マイページ</a></li>
        </ul>
        <div class="sp-menu__divider"></div>
        <ul class="sp-menu__list sp-menu__list--bottom">
            <li class="sp-menu__item"><a href="faq.php">よくある質問</a></li>
            <li class="sp-menu__item"><a href="contact.php">お問い合わせ</a></li>
        </ul>
    </nav>

    <main>
        <section class="post-creation-section">
            <div class="post-form-container">
                <h2 class="section-title">
                    <span class="en">NEW POST</span>
                    <span class="ja">( 新規投稿作成 )</span>
                </h2>
                <!-- actionをpost.php自身に設定し、enctype="multipart/form-data" を追加 -->
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
                    <!-- 投稿フォーム内に追加 -->
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

    <footer class="footer">
        <div class="footer__inner">
            <ul class="footer__nav">
                <li>
                    <span class="footer__nav-title">商品一覧</span>
                    <ul class="footer__subnav">
                        <li><a href="products_list.php?category=日本酒">日本酒</a></li>
                        <li><a href="products_list.php?category=中国酒">中国酒</a></li>
                        <li><a href="products_list.php?category=梅酒">梅酒</a></li>
                        <li><a href="products_list.php?category=缶チューハイ">缶チューハイ</a></li>
                        <li><a href="products_list.php?category=焼酎">焼酎</a></li>
                        <li><a href="products_list.php?category=ウィスキー">ウィスキー</a></li>
                        <li><a href="products_list.php?category=スピリッツ">スピリッツ</a></li>
                        <li><a href="products_list.php?category=リキュール">リキュール</a></li>
                        <li><a href="products_list.php?category=ワイン">ワイン</a></li>
                        <li><a href="products_list.php?category=ビール">ビール</a></li>
                    </ul>
                </li>
                <li><a href="faq.php">よくあるご質問／お問合せ</a></li>
                <li><a href="MyPage.php">会員登録・ログイン</a></li>
                <li><a href="history.php">購入履歴</a></li>
                <li><a href="cart.php">買い物かごを見る</a></li>
                <li><a href="privacy.php">プライバシーポリシー</a></li>
                <li><a href="terms.php">利用規約</a></li>
            </ul>
            <div class="footer__logo" style="margin: 24px 0 12px;">
                <a href="index.php">
                    <img src="img/logo.png" alt="OUR BRAND" style="height:32px;">
                </a>
            </div>
            <p class="footer__copyright">© OUR BRAND All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        // PHPからJavaScriptにメッセージを渡すための変数
        const postPhpMessage = <?php echo json_encode($post_message); ?>;
        const postPhpMessageType = <?php echo json_encode($message_type); ?>;

        document.addEventListener('DOMContentLoaded', function () {
            // カスタムメッセージボックスを表示する関数
            function displayMessage(message, type) {
                if (!message) return;

                const messageBox = document.createElement('div');
                messageBox.classList.add('custom-message-box');
                if (type === 'success') {
                    messageBox.classList.add('success');
                } else if (type === 'error') {
                    messageBox.classList.add('error');
                }
                messageBox.textContent = message;

                const existingMessageBox = document.querySelector('.custom-message-box');
                if (existingMessageBox) {
                    existingMessageBox.remove();
                }

                document.body.appendChild(messageBox);

                setTimeout(() => {
                    messageBox.remove();
                }, 3000); // 3秒後に消える
            }

            // ページロード時にPHPからのメッセージを表示
            displayMessage(postPhpMessage, postPhpMessageType);
        });
    </script>
    <!-- post.js を読み込む -->
    <script src="js/post.js"></script>
</body>

</html>
