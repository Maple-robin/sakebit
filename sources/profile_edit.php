<?php
/*!
@file index.php
@brief トップページ
@copyright Copyright (c) 2024 Your Name.
*/

// セッションを開始 (HTML出力の前に置く)
session_start();

// contents_db.php など、必要なファイルをインクルード（必要に応じて）
// require_once __DIR__ . '/common/contents_db.php';

// ここにトップページ固有のPHPロジックがあれば記述
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プロフィール編集 | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/MyPage.css">
    <link rel="stylesheet" href="css/profile_edit.css">
    <link rel="stylesheet" href="css/top.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            <!-- ↓ここから追加 -->
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
            <!-- ↑ここまで追加 -->
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
        <div class="mypage-container">
            <h1 class="page-title">
                <span class="en">EDIT PROFILE</span>
                <span class="ja">( プロフィール編集 )</span>
            </h1>

            <div class="profile-edit-inner">
                <div class="profile-edit-item profile-edit-icon">
                    <label for="user-icon">
                        <img src="https://placehold.co/150x150/FFD700/000000?text=USER" alt="ユーザーアイコン"
                            class="profile-icon-preview">
                        <input type="file" id="user-icon" accept="image/*" style="display: none;">
                        <button type="button" class="btn-change-icon">アイコンを変更</button>
                    </label>
                    <p class="icon-guidance">推奨サイズ: 150x150px / JPEG, PNG</p>
                </div>

                <div class="profile-edit-item">
                    <label for="birthday" class="edit-label">誕生日</label>
                    <input type="date" id="birthday" class="edit-input" value="1990-01-01">
                </div>

                <div class="profile-edit-item">
                    <label for="bio" class="edit-label">自己紹介</label>
                    <textarea id="bio" class="edit-textarea" rows="5"
                        placeholder="自己紹介を入力してください。">お酒と美味しい料理をこよなく愛するサンプル太郎です。特に日本酒の奥深さに魅了されており、週末は新しい銘柄を探しに出かけるのが趣味です。皆さんとお酒に関する情報交換ができたら嬉しいです！</textarea>
                    <p class="char-count"><span id="bio-current-char"></span> / 200文字</p>
                </div>

                <div class="profile-edit-actions">
                    <button type="submit" class="btn-save-profile">変更を保存</button>
                    <button type="button" class="btn-cancel-profile" onclick="history.back()">キャンセル</button>
                </div>
            </div>
        </div>
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

    <script src="js/script.js"></script>
    <script src="js/MyPage.js"></script>
    <script src="js/profile_edit.js"></script>
</body>

</html>