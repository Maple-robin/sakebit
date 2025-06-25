<?php
/*!
@file config.php
@brief DB接続変数と、エンコードの定義
@copyright Copyright (c) 2024 Yamanoi Yasushi.
*/
////////////////////////////////////
//実行ブロック
//データベースマネージメント
define('DB_RDBMS','mysql');
//MySQLの場合のキャラ設定にSET NAMESを使用するかどうか
define('DB_MYSQL_SET_NAMES','1');
//ホスト(ローカルの場合は'localhost'と記述)
define('DB_HOST','localhost');
//ユーザー
define('DB_USER','j2025gdb');
//パスワード
define('DB_PASS','B60urys50UFwSM0X!');
//DB名
define('DB_NAME','j2025gdb');
//DBのキャラセット
define('DB_CHARSET','utf8');
//PHPのキャラセット
define('PHP_CHARSET','UTF-8');
//暗号化のキー
define('MY_AES_KEY','phpbase_key');

// デバッグモードの定義 (この行を追加)
define('DEBUG', true); // 開発中は true, 本番環境では false に変更してください
