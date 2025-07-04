<?php
/*!
@file faq.php
@brief よくある質問ページ
@copyright Copyright (c) 2024 Your Name.
*/

// ★注意: DB接続やセッション開始は header.php で行われるため、ここでの処理は不要です。
// session_start();
// require_once __DIR__ . '/common/contents_db.php';

// ここにFAQページ固有のPHPロジックがあれば記述します。

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>よくある質問 | OUR BRAND</title>
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
                <div class="faq-category">
                    <h3 class="faq-category__title">当サイトについて</h3>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">このサイトはどんな目的で利用できますか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A.
                                このサイトは、お酒をこれから楽しみたい初心者の方が、お酒の基本的な知識を学んだり、自分好みのお酒を見つけたり、他の方のおすすめ情報を参考にしたりするための情報サイトです。お酒の販売は行っていません。
                            </p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">お酒の知識が全くないのですが、どこから始めればいいですか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. まずは「初心者の手引き」をご覧ください。お酒の種類や選び方、基本的な飲み方など、初めての方に役立つ情報がまとめられています。</p>
                        </div>
                    </div>
                </div>

                <div class="faq-category">
                    <h3 class="faq-category__title">ログイン・会員登録について</h3>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">ログインすると何ができますか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. ログインすると、お気に入りのお酒を保存したり、過去に投稿した内容を編集・管理したり、他のユーザーをフォローして最新の投稿をチェックしたりできるようになります。</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">新規会員登録はどのように行えばいいですか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. トップページの「新規登録」ボタンから、メールアドレスとパスワードを設定してご登録いただけます。登録完了後、すぐにログインしてサービスをご利用いただけます。</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">登録した情報を変更したい場合はどうすればいいですか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. ログイン後、マイページの「会員情報編集」から、登録情報（メールアドレス、パスワードなど）を変更できます。</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">パスワードを忘れてしまいました。どうすればいいですか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. ログインページの「パスワードを忘れた方」リンクをクリックし、ご登録のメールアドレスを入力してください。パスワード再設定用のURLをお送りします。</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">退会したい場合はどうすればいいですか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. ログイン後、マイページの「退会手続き」からお手続きいただけます。退会すると、保存したお気に入りや投稿履歴などはすべて削除されますのでご注意ください。</p>
                        </div>
                    </div>
                </div>

                <div class="faq-category">
                    <h3 class="faq-category__title">お酒の情報について</h3>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">どんなお酒を探すことができますか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A.
                                検索機能を使って特定のお酒を探したり、おすすめ欄から人気のお酒やテーマに沿ったお酒を見つけたりできます。また、「みんなの投稿」ページでは、他の方がおすすめするお酒の情報も参考にできます。
                            </p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">探したお酒のページでは何が見られますか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. 各お酒のページでは、商品の詳しい説明、購入できるECサイトへのリンク、そしてそのお酒に合うおすすめのおつまみ情報をご覧いただけます。</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">このサイトで直接お酒を購入することはできますか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. いいえ、当サイトではお酒の販売は行っておりません。各お酒のページに記載されているECサイトのリンクから、外部のサイトでご購入いただけます。</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">掲載されているお酒の情報は常に最新ですか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. 掲載情報には細心の注意を払っておりますが、商品のリニューアルや価格変更などにより、情報が更新される場合があります。ECサイトへのリンク先で最終的な情報をご確認ください。
                            </p>
                        </div>
                    </div>
                </div>

                <div class="faq-category">
                    <h3 class="faq-category__title">投稿について</h3>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">お酒のおすすめや感想を投稿したいのですが、どうすればいいですか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. 「みんなの投稿」ページから、あなたのおすすめのお酒や、お酒と料理の素晴らしい組み合わせなどを自由に投稿できます。</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">投稿する際に注意することはありますか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. 投稿内容は、他の方が楽しく利用できるよう、公序良俗に反しないもの、誹謗中傷を含まないものをお願いします。具体的な注意点は「投稿ガイドライン」をご確認ください。</p>
                        </div>
                    </div>
                </div>

                <div class="faq-category">
                    <h3 class="faq-category__title">その他</h3>
                    <div class="faq-item">
                        <h4 class="faq-item__question">
                            <span class="q-mark">Q.</span>
                            <span class="question-text">お酒に関する質問や、サイトへの要望がある場合はどうすればいいですか？</span>
                            <span class="icon"></span>
                        </h4>
                        <div class="faq-item__answer">
                            <p>A. サイト下部にある「お問い合わせ」からご連絡ください。皆様のご意見やご要望を参考に、より良いサイト運営を目指してまいります。</p>
                        </div>
                    </div>
                </div>

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
