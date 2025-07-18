<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAKE BIT | お酒管理（編集）</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../admincss/admin.css">
    <link rel="stylesheet" href="../admincss/admin_otsumami_add.css">
</head>
<body>

    <?php require_once 'admin_header.php'; ?>

    <main class="admin-main">
        <div class="admin-main__inner">
            <h2 class="admin-page-title">
                <span class="en">EDIT SNACK</span>
                <span class="ja">( おつまみデータ編集 )</span>
            </h2>

            <section class="admin-section admin-otsumami-edit-form">
                <form action="#" method="post" enctype="multipart/form-data" class="admin-form">
                    <div class="form-group">
                        <label for="otsumami_name">おつまみ名 <span class="required-tag">必須</span></label>
                        <input type="text" id="otsumami_name" name="otsumami_name" required maxlength="128">
                    </div>

                    <div class="form-group">
                        <label for="main_image">メイン画像 <span class="required-tag">必須</span></label>
                        <input type="file" id="main_image" name="main_image" accept="image/*" required>
                    </div>

                    <div class="form-group">
                        <label for="sub_image1">サブ画像1</label>
                        <input type="file" id="sub_image1" name="sub_image1" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="sub_image2">サブ画像2</label>
                        <input type="file" id="sub_image2" name="sub_image2" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="sub_image3">サブ画像3</label>
                        <input type="file" id="sub_image3" name="sub_image3" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="combi_type">合うお酒の種類 <span class="required-tag">必須</span></label>
                        <select id="combi_type" name="combi_type" required>
                            <option value="">選択してください</option>
                            <option value="ビール">ビール</option>
                            <option value="日本酒">日本酒</option>
                            <option value="ワイン">ワイン</option>
                            <option value="ウイスキー">ウイスキー</option>
                            <option value="焼酎">焼酎</option>
                            <option value="その他">その他</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="combi_category">合うお酒のカテゴリ <span class="required-tag">必須</span></label>
                        <select id="combi_category" name="combi_category" required>
                            <option value="">選択してください</option>
                            <option value="甘口">甘口</option>
                            <option value="辛口">辛口</option>
                            <option value="さっぱり">さっぱり</option>
                            <option value="濃厚">濃厚</option>
                            <option value="スパイシー">スパイシー</option>
                            <option value="ヘルシー">ヘルシー</option>
                            <option value="定番">定番</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="otsumami_text">おつまみレシピ／説明 <span class="required-tag">必須</span></label>
                        <textarea id="otsumami_text" name="otsumami_text" rows="6" required maxlength="200"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="liquor_name">お酒名 <span class="required-tag">必須</span></label>
                        <input type="text" id="liquor_name" name="liquor_name" required maxlength="128" placeholder="関連するお酒の名称を入力してください（任意）">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">変更を保存</button>
                    </div>
                </form>
            </section>

            <div class="back-to-list-button-area">
                <a href="admin_otsumami.php" class="btn btn-secondary btn-back-to-list">
                    おつまみ管理一覧に戻る
                </a>
            </div>

        </div>
    </main>

    <footer class="admin-footer">
        <div class="admin-footer__inner">
            <p class="admin-footer__copyright">© SAKE BIT Admin All Rights Reserved.</p>
        </div>
    </footer>

    <script src="../adminjs/admin_otumami_edit.js"></script>

    <script src="../adminjs/admin.js"></script>
</body>
</html>
