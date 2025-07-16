<?php
// PHPスクリプトの冒頭でセッションを開始
session_start();

// ログイン状態のチェック (必要であればコメントアウトを解除して使用)
if (!isset($_SESSION['admin_user_id']) || empty($_SESSION['admin_user_id'])) {
    header('Location: admin_login.php'); // ログインページのパスに修正
    exit();
}

// contents_db.phpを読み込む
require_once '../common/contents_db.php';

// デバッグモードのオン/オフ
$debug = false; // 本番環境ではfalseにすることを推奨

// セッションからメッセージとエラーを取得し、表示後にセッションから削除
$messages = $_SESSION['message'] ?? '';
$errors = $_SESSION['errors'] ?? [];

unset($_SESSION['message']);
unset($_SESSION['errors']);

// データベースクラスのインスタンスを生成
$db_otumami = new cotumami();
$db_categories = new cotumami_categories();
$db_otumami_images = new cotumami_images();
$db_otumami_tags_relation = new cotumami_otumami_tags(); // otumamiとタグのリレーションクラス
$db_tags = new cotumami_tags(); // タグマスタークラス

// 全てのおつまみ情報を取得
// ここではページネーションを考慮せず全て取得しますが、データ量が多い場合はページネーション実装を検討してください
$all_otsumami = $db_otumami->get_all($debug, 0, 9999); // 仮で大量取得
if ($all_otsumami === false) {
    $all_otsumami = []; // 取得失敗時は空の配列を設定
    error_log("Failed to fetch all otumami data.");
}

// カテゴリー情報をキャッシュ
$categories_cache = [];
$all_categories = $db_categories->get_all_categories($debug);
if ($all_categories) {
    foreach ($all_categories as $cat) {
        $categories_cache[$cat['category_id']] = $cat['category_name'];
    }
}

// タグ情報をキャッシュ
$tags_cache = [];
$all_tags = $db_tags->get_all_tags($debug);
if ($all_tags) {
    foreach ($all_tags as $tag) {
        $tags_cache[$tag['tag_id']] = $tag['tag_name'];
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAKE BIT | おつまみ管理（一覧）</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../admincss/admin.css">
    <link rel="stylesheet" href="../admincss/admin_otsumami.css">
    <!-- スタイルは外部CSSファイルで管理するため、ここではHTML埋め込みは推奨しません。 -->
</head>
<body>

    <?php require_once 'admin_header.php'; ?>

    <main class="admin-main">
        <div class="admin-main__inner">
            <!-- メッセージ表示エリア -->
            <?php if (!empty($messages)): ?>
                <div style="color: green; background-color: #e6ffe6; padding: 15px; border: 1px solid #00cc00; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: bold;">
                    <?php echo htmlspecialchars($messages); ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($errors)): ?>
                <div style="color: red; background-color: #ffe6e6; padding: 15px; border: 1px solid #cc0000; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: bold;">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <h2 class="admin-page-title">
                <span class="en">SNACK MANAGEMENT</span>
                <span class="ja">( おつまみ管理 )</span>
            </h2>

            <section class="admin-section admin-otsumami-list">
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>おつまみ名</th>
                                <th>画像</th>
                                <th>カテゴリー</th><!-- ★変更: おつまみカテゴリー -> カテゴリー -->
                                <th>タグ</th><!-- ★変更: おつまみタグ -> タグ -->
                                <th>おつまみ説明1</th>
                                <th>おつまみ説明2</th>
                                <th>価格</th>
                                <th>在庫数</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($all_otsumami)): ?>
                                <tr>
                                    <td colspan="9" style="text-align: center;">登録されているおつまみはありません。</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($all_otsumami as $otumami):
                                    // おつまみID
                                    $otumami_id = $otumami['otumami_id'];

                                    // カテゴリー名を取得
                                    $category_name = $categories_cache[$otumami['combi_category_id']] ?? '不明';

                                    // 画像を取得
                                    // contents_db.phpのget_images_by_otumami_idメソッドはdisplay_orderでソートされるため、メイン画像が最初に来ます。
                                    $otumami_images = $db_otumami_images->get_images_by_otumami_id($debug, $otumami_id);
                                    if ($otumami_images === false) {
                                        $otumami_images = []; // 取得失敗時は空の配列
                                        error_log("Failed to fetch images for otumami_id: " . $otumami_id);
                                    }

                                    // タグを取得
                                    $related_tags = $db_otumami_tags_relation->get_by_otumami_id($debug, $otumami_id);
                                    $tag_names = [];
                                    if ($related_tags) {
                                        foreach ($related_tags as $rel_tag) {
                                            $tag_id = $rel_tag['tag_id'];
                                            if (isset($tags_cache[$tag_id])) {
                                                $tag_names[] = $tags_cache[$tag_id];
                                            }
                                        }
                                    }
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($otumami['otumami_name']); ?></td>
                                    <td>
                                        <div class="image-thumbs">
                                            <?php if (!empty($otumami_images)): ?>
                                                <?php foreach ($otumami_images as $image): ?>
                                                    <!-- デバッグ用: display_orderを表示 (マウスオーバーでツールチップ) -->
                                                    <img src="../<?php echo htmlspecialchars($image['image_path']); ?>" 
                                                         alt="<?php echo htmlspecialchars($otumami['otumami_name'] . ' ' . $image['image_type']); ?>"
                                                         title="Order: <?php echo htmlspecialchars($image['display_order']); ?> Type: <?php echo htmlspecialchars($image['image_type']); ?>">
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span>画像なし</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($category_name); ?></td>
                                    <td>
                                        <div class="product-tags">
                                            <?php if (!empty($tag_names)): ?>
                                                <?php foreach ($tag_names as $tag_name): ?>
                                                    <span><?php echo htmlspecialchars($tag_name); ?></span>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span>タグなし</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <!-- 説明文をスクロール可能なdivで囲む -->
                                    <td>
                                        <div class="scrollable-content">
                                            <?php echo nl2br(htmlspecialchars($otumami['otumami_description'])); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="scrollable-content">
                                            <?php echo nl2br(htmlspecialchars($otumami['otumami_discription'])); ?>
                                        </div>
                                    </td>
                                    <td><?php echo number_format($otumami['otumami_price']); ?>円</td>
                                    <td><?php echo htmlspecialchars($otumami['otumami_stock']); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="admin_otumami_edit.php?id=<?php echo htmlspecialchars($otumami_id); ?>" class="btn btn-sm btn-edit">編集</a>
                                            <button class="btn btn-sm btn-delete" data-id="<?php echo htmlspecialchars($otumami_id); ?>">削除</button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <div class="add-otsumami-button-area">
                <a href="admin_otsumami_add.php" class="btn btn-primary btn-add-new">
                    <span class="btn-icon">＋</span> 新しいおつまみを登録する
                </a>
            </div>

        </div>
    </main>

    <footer class="admin-footer">
        <div class="admin-footer__inner">
            <p class="admin-footer__copyright">© SAKE BIT Admin All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        // ここに削除確認などのJavaScriptを追加できます（オプション）
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const otumamiId = this.dataset.id;
                if (confirm(`おつまみID: ${otumamiId} を本当に削除しますか？`)) {
                    // 削除処理へのリクエスト（例: delete_otsumami.php などにPOST送信）
                    // ここでは簡単な例としてアラートのみ
                    alert('削除機能はまだ実装されていません。');
                    // 実際にはformを作成してPOST送信するか、fetch APIを使う
                }
            });
        });
    </script>
    <script src="../adminjs/admin.js"></script>
</body>
</html>
