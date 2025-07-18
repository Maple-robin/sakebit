<?php
/*!
@file faq.php
@brief よくある質問ページ
@copyright Copyright (c) 2024 Your Name.
*/

// データベースからFAQデータを取得
require_once __DIR__ . '/common/contents_db.php';
$faq_db = new cfaqs();
// falseはデバッグモードOFFを示す
$faqs_by_category = $faq_db->get_all_faqs_grouped_by_category(false);

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>よくある質問 | SAKE BIT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/top.css">
    <link rel="stylesheet" href="css/faq.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <?php 
    // 共通ヘッダーを読み込む
    require_once 'header.php'; 
    ?>

    <main>
        <section class="faq-hero">
            <div class="faq-hero__content">
                <h2 class="section-title">
                    <span class="en">FAQ</span>
                    <span class="ja">（ よくある質問 ）</span>
                </h2>
            </div>
        </section>

        <section class="faq-section">
            <div class="faq-section__inner">
                
                <?php if (!empty($faqs_by_category)): ?>
                    <?php foreach ($faqs_by_category as $category): ?>
                        <div class="faq-category">
                            <h3 class="faq-category__title"><?php echo htmlspecialchars($category['category_name']); ?></h3>
                            
                            <?php foreach ($category['faqs'] as $faq): ?>
                                <div class="faq-item">
                                    <h4 class="faq-item__question">
                                        <span class="q-mark">Q.</span>
                                        <span class="question-text"><?php echo htmlspecialchars($faq['question']); ?></span>
                                        <span class="icon"></span>
                                    </h4>
                                    <div class="faq-item__answer">
                                        <!-- nl2br() で改行を <br> タグに変換 -->
                                        <p>A. <?php echo nl2br(htmlspecialchars($faq['answer'])); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center;">現在、よくある質問を準備中です。</p>
                <?php endif; ?>

                <div class="faq-contact-info">
                    <p>上記以外にご不明な点がございましたら、お気軽にお問い合わせください。</p>
                    <a href="contact.php" class="btn-contact">お問い合わせはこちら</a>
                </div>
            </div>
        </section>
    </main>

    <?php 
    // 共通フッターを読み込む
    require_once 'footer.php'; 
    ?>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/faq.js"></script>
</body>

</html>
