<?php
/*!
@file registration_success.php
@brief 新規登録成功メッセージ表示ページ
@copyright Copyright (c) 2024 Your Name.
*/
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録完了 | OUR BRAND</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Zen+Old+Mincho:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans JP', sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: #333;
        }
        .success-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        .success-container h1 {
            color: #28a745; /* Green for success */
            margin-bottom: 20px;
            font-size: 2em;
        }
        .success-container p {
            font-size: 1.1em;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .success-container a {
            display: inline-block;
            background-color: #007bff; /* Blue for call to action */
            color: white;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .success-container a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <h1>登録が完了しました！</h1>
        <p>ご登録ありがとうございます。これでサービスをご利用いただけます。</p>
        <a href="login.php">ログインページへ</a>
    </div>
</body>
</html>
