<?php
// admin_header.php でセッションは開始されている想定
// パスは環境に合わせて適宜調整してください
require_once __DIR__ . '/../common/contents_db.php';

$faq_db = new cfaqs();
// falseはデバッグモードOFFを示す
$faqs = $faq_db->get_all_faqs_with_category(false);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAKE BIT | FAQ管理（一覧）</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../admincss/admin.css">
    <link rel="stylesheet" href="../admincss/admin_faq.css">
</head>
<body>

    <?php require_once 'admin_header.php'; ?>

    <main class="admin-main">
        <div class="admin-main__inner">
            <h2 class="admin-page-title">
                <span class="en">FAQ MANAGEMENT</span>
                <span class="ja">( FAQ管理 )</span>
            </h2>

            <section class="admin-section admin-faq-list">
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>タイトル</th>
                                <th>質問のカテゴリ</th>
                                <th>内容</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($faqs)): ?>
                                <?php foreach ($faqs as $faq): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($faq['faq_title']); ?></td>
                                        <td><?php echo htmlspecialchars($faq['faq_category_name']); ?></td>
                                        <!-- 内容が長いとレイアウトが崩れるため、50文字で省略表示 -->
                                        <td><?php echo htmlspecialchars(mb_strimwidth($faq['faq_content'], 0, 50, '...')); ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <!-- 編集・削除ボタンにIDを付与 -->
                                                <a href="admin_faq_edit.php?id=<?php echo htmlspecialchars($faq['faq_id']); ?>" class="btn btn-sm btn-edit">編集</a>
                                                <button class="btn btn-sm btn-delete" data-id="<?php echo htmlspecialchars($faq['faq_id']); ?>">削除</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">FAQが登録されていません。</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <div class="add-faq-button-area">
                <a href="admin_faq_add.php" class="btn btn-primary btn-add-new">
                    <span class="btn-icon">＋</span> 新しいFAQを登録する
                </a>
            </div>

        </div>
    </main>

    <footer class="admin-footer">
        <div class="admin-footer__inner">
            <p class="admin-footer__copyright">© SAKE BIT Admin All Rights Reserved.</p>
        </div>
    </footer>

    <script src="../adminjs/admin.js"></script>
    <!-- 今後の削除機能実装のために、削除処理用のJSを読み込む（ファイルは別途作成想定） -->
    <!-- <script src="../adminjs/admin_faq.js"></script> -->
</body>
</html>
