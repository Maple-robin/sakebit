<?php
/*!
@file contents_db.php
@brief データベース操作クラス群
@copyright Copyright (c) 2024 Your Name. (元の著作者名を尊重しつつ、プロジェクト名を入れることを推奨)
*/

// config.phpをインクルードしてDB接続定数を読み込む
// contents_db.phpとconfig.phpは同じディレクトリにあるはずなので、このパスで正しいです。
require_once __DIR__ . '/config.php'; 

// 仮の基底クラスとユーティリティクラスの定義（実際には別途定義されているものと想定）
// --------------------------------------------------------------------------------------
class crecord {
    protected $pdo; // PDOインスタンスを保持
    protected $stmt; // PDOStatementインスタンスを保持

    public function __construct() {
        // config.phpで定義された定数を使用してDB接続処理を行う
        try {
            $dsn = DB_RDBMS . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            $user = DB_USER;
            $password = DB_PASS;

            $this->pdo = new PDO($dsn, $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            // MySQLの場合のSET NAMES
            if (defined('DB_MYSQL_SET_NAMES') && DB_MYSQL_SET_NAMES === '1') {
                $this->pdo->exec("SET NAMES " . DB_CHARSET);
            }

        } catch (PDOException $e) {
            // DEBUGがtrueの場合のみブラウザにエラーメッセージを表示、それ以外はログのみ
            if (defined('DEBUG') && DEBUG) {
                echo "DB接続エラー: " . htmlspecialchars($e->getMessage());
            }
            error_log("DB Connection Error: " . $e->getMessage()); // 常にログには出力
            exit();
        }
    }

    protected function select_query($debug, $query, $prep_arr) {
        if ($debug) {
            error_log("Debug SQL (select_query): " . $query);
            error_log("Debug Params (select_query): " . print_r($prep_arr, true));
        }
        $this->stmt = $this->pdo->prepare($query);
        $this->stmt->execute($prep_arr);
    }

    // INSERT, UPDATE, DELETEなどの書き込み操作、および結果セットを返さないSELECT操作を実行するメソッド
    // SELECT文で結果セットを返す場合は、PDOStatementオブジェクトを返すように変更
    protected function execute_query($debug, $query, $prep_arr = array()) {
        if ($debug) {
            error_log("Debug SQL (execute_query): " . $query);
            error_log("Debug Params (execute_query): " . print_r($prep_arr, true));
        }
        try {
            $stmt = $this->pdo->prepare($query);
            $result = $stmt->execute($prep_arr);

            // SELECT文の場合、PDOStatementオブジェクトをそのまま返す
            if (preg_match('/^\s*SELECT/i', $query)) {
                return $stmt;
            }
            // それ以外のクエリ（INSERT/UPDATE/DELETEなど）の場合、実行結果（true/false）を返す
            return $result;
        } catch (PDOException $e) {
            // エラーログへの出力 (本番環境でもログには出力する)
            $error_message_log = "Database Error: " . $e->getMessage() .
                                 " SQLSTATE: " . ($e->errorInfo[0] ?? 'N/A') .
                                 " SQLSTATE Code: " . ($e->errorInfo[1] ?? 'N/A') .
                                 " Driver Message: " . ($e->errorInfo[2] ?? 'N/A') .
                                 " Query: " . $query .
                                 " Params: " . json_encode($prep_arr);
            error_log($error_message_log);

            // デバッグモードがtrueの場合、ブラウザにも詳細なエラーメッセージを出力し、そこで実行を停止
            // ただし、本番環境向けではこれをコメントアウトまたは削除し、ログのみにする
            // 現在のconfig.phpでDEBUG=falseなので、このechoは実行されず、exitも実行されません。
            if (defined('DEBUG') && DEBUG) {
                echo "<div style='background-color:#ffe6e6; border:1px solid #ffb3b3; padding:10px; margin-bottom:10px; color:#cc0000; font-family:monospace;'>";
                echo "<strong>データベースエラーが発生しました（DEBUGモード）:</strong><br>";
                echo "メッセージ: " . htmlspecialchars($e->getMessage()) . "<br>";
                echo "SQLSTATE: " . htmlspecialchars($e->errorInfo[0] ?? 'N/A') . "<br>";
                echo "ドライバエラーコード: " . htmlspecialchars($e->errorInfo[1] ?? 'N/A') . "<br>";
                echo "ドライバメッセージ: " . htmlspecialchars($e->errorInfo[2] ?? 'N/A') . "<br>";
                echo "クエリ: <pre>" . htmlspecialchars($query) . "</pre>";
                echo "パラメータ: <pre>" . htmlspecialchars(json_encode($prep_arr, JSON_UNESCAPED_UNICODE)) . "</pre>";
                echo "</div>";
                // exit(); // 本番環境ではexit()は使わない
            }
            return false;
        }
    }

    public function fetch_assoc() {
        if ($this->stmt) {
            return $this->stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    // 最後に挿入されたIDを取得するメソッド (AUTO_INCREMENTカラムがある場合)
    public function last_insert_id() {
        return $this->pdo->lastInsertId();
    }

    // PDOインスタンスを返すメソッド (トランザクション処理用)
    public function get_pdo() {
        return $this->pdo;
    }

    public function __destruct() {
        $this->stmt = null;
        $this->pdo = null;
    }
}

class cutil {
    public static function is_number($value) {
        return is_numeric($value);
    }
}
// --------------------------------------------------------------------------------------


////////////////////////////////////
// 以下、DBクラス群

//--------------------------------------------------------------------------------------
/// ユーザー情報クラス
//--------------------------------------------------------------------------------------
class cuser_info extends crecord {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) AS total_count FROM user_info WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit) {
        $arr = array();
        $query = "SELECT * FROM user_info WHERE 1 ORDER BY user_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id) {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM user_info WHERE user_id = :user_id";
        $prep_arr = array(':user_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    // 新しいユーザーをデータベースに挿入するメソッド
    // user_id が AUTO_INCREMENT なので、挿入後に lastInsertId() でIDを取得する必要がある
    public function insert_user($debug, $user_name, $user_email, $user_pass_hashed, $user_age) {
        $query = "INSERT INTO user_info (user_name, user_email, user_pass, user_age) VALUES (:user_name, :user_email, :user_pass, :user_age)";
        $prep_arr = array(
            ':user_name' => $user_name,
            ':user_email' => $user_email,
            ':user_pass' => $user_pass_hashed, // ハッシュ化されたパスワード
            ':user_age' => $user_age // DATE型に合う 'YYYY-MM-DD' 形式
        );
        $result = $this->execute_query($debug, $query, $prep_arr);
        if ($result) {
            return $this->last_insert_id(); // 挿入された user_id を返す
        }
        return false;
    }

    // メールアドレスでユーザーを検索するメソッド（登録済みかどうかのチェック用）
    public function get_user_by_email($debug, $email) {
        $query = "SELECT * FROM user_info WHERE user_email = :user_email";
        $prep_arr = array(':user_email' => $email);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    // メールアドレスでユーザー情報を取得するメソッド（ログイン用）
    public function get_user_by_email_for_login($debug, $email) {
        $query = "SELECT user_id, user_name, user_email, user_pass, user_age FROM user_info WHERE user_email = :user_email";
        $prep_arr = array(':user_email' => $email);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    // ユーザーを削除するメソッド (admin_users.php で使用予定)
    public function delete_user($debug, $user_id) {
        if (!cutil::is_number($user_id) || $user_id < 1) {
            return false;
        }
        $query = "DELETE FROM user_info WHERE user_id = :user_id";
        $prep_arr = array(':user_id' => (int)$user_id);
        return $this->execute_query($debug, $query, $prep_arr);
    }

    // ユーザー名を更新するメソッド
    public function update_user_name($debug, $user_id, $user_name) {
        if (!cutil::is_number($user_id) || $user_id < 1) {
            return false;
        }
        $query = "UPDATE user_info SET user_name = :user_name WHERE user_id = :user_id";
        $prep_arr = array(
            ':user_name' => $user_name,
            ':user_id' => (int)$user_id
        );
        return $this->execute_query($debug, $query, $prep_arr);
    }


    public function __destruct() {
        parent::__destruct();
    }
}

//--------------------------------------------------------------------------------------
/// 新規追加: client_user_info テーブルを操作するクラス
//--------------------------------------------------------------------------------------
class cclient_user_info extends crecord {
    public function __construct() {
        parent::__construct();
    }

    /**
     * 新しいクライアントユーザーをデータベースに挿入するメソッド
     * @param bool $debug デバッグモードのオン/オフ
     * @param string $company_name 会社名
     * @param string $representative_name 代表者名
     * @param string $email メールアドレス
     * @param string $phone 電話番号
     * @param string $address 住所
     * @param string $password_hash ハッシュ化されたパスワード
     * @return int|false 挿入されたclient_id、または失敗した場合はfalse
     */
    public function insert_client_user($debug, $company_name, $representative_name, $email, $phone, $address, $password_hash) {
        $query = "INSERT INTO client_user_info (company_name, representative_name, email, phone, address, password_hash) VALUES (:company_name, :representative_name, :email, :phone, :address, :password_hash)";
        $prep_arr = array(
            ':company_name' => $company_name,
            ':representative_name' => $representative_name,
            ':email' => $email,
            ':phone' => $phone,
            ':address' => $address,
            ':password_hash' => $password_hash
        );
        $result = $this->execute_query($debug, $query, $prep_arr);
        if ($result) {
            return $this->last_insert_id(); // 挿入された client_id を返す
        }
        return false;
    }

    /**
     * メールアドレスでクライアントユーザーを検索するメソッド（登録済みかどうかのチェック用）
     * @param bool $debug デバッグモードのオン/オフ
     * @param string $email 検索するメールアドレス
     * @return array|false ユーザー情報（連想配列）、または見つからない場合はfalse
     */
    public function get_client_user_by_email($debug, $email) {
        $query = "SELECT * FROM client_user_info WHERE email = :email";
        $prep_arr = array(':email' => $email);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    /**
     * メールアドレスでクライアントユーザー情報を取得するメソッド（ログイン認証用）
     * @param bool $debug デバッグモードのオン/オフ
     * @param string $email 検索するメールアドレス
     * @return array|false ユーザー情報（連想配列、password_hashを含む）、または見つからない場合はfalse
     */
    public function get_client_user_by_email_for_login($debug, $email) {
        $query = "SELECT client_id, company_name, representative_name, email, phone, address, password_hash FROM client_user_info WHERE email = :email";
        $prep_arr = array(':email' => $email);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct() {
        parent::__destruct();
    }
}


//--------------------------------------------------------------------------------------
/// ユーザープロフィールクラス
//--------------------------------------------------------------------------------------
class cuser_profiles extends crecord {
    public function __construct() {
        parent::__construct();
    }

    // プロフィール情報を挿入
    public function insert_profile($debug, $user_id, $profile_icon_url, $profile_text) {
        $query = "INSERT INTO user_profiles (user_id, profile_icon_url, profile_text) VALUES (:user_id, :profile_icon_url, :profile_text)";
        $prep_arr = array(
            ':user_id' => (int)$user_id,
            ':profile_icon_url' => $profile_icon_url,
            ':profile_text' => $profile_text
        );
        return $this->execute_query($debug, $query, $prep_arr);
    }

    // プロフィール情報をユーザーIDで取得
    public function get_profile_by_user_id($debug, $user_id) {
        if (!cutil::is_number($user_id) || $user_id < 1) {
            return false;
        }
        $query = "SELECT * FROM user_profiles WHERE user_id = :user_id";
        $prep_arr = array(':user_id' => (int)$user_id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    // プロフィール情報を更新 (アイコンURLと自己紹介文)
    public function update_profile($debug, $user_id, $profile_icon_url, $profile_text) {
        if (!cutil::is_number($user_id) || $user_id < 1) {
            return false;
        }
        $query = "UPDATE user_profiles SET profile_icon_url = :profile_icon_url, profile_text = :profile_text WHERE user_id = :user_id";
        $prep_arr = array(
            ':profile_icon_url' => $profile_icon_url,
            ':profile_text' => $profile_text,
            ':user_id' => (int)$user_id
        );
        return $this->execute_query($debug, $query, $prep_arr);
    }

    public function __destruct() {
        parent::__destruct();
    }
}


//--------------------------------------------------------------------------------------
/// 商品情報クラス
//--------------------------------------------------------------------------------------
class cproduct_info extends crecord {
    public function __construct() {
        parent::__construct();
    }

    /**
     * 新しい商品情報をデータベースに挿入するメソッド
     * @param bool $debug デバッグモードのオン/オフ
     * @param string $product_name 商品名
     * @param float $product_price 価格
     * @param string $product_category カテゴリーIDを指す文字列（例: '1'、外部結合で名前を取得）
     * @param string $product_description 商品説明1
     * @param string $product_discription 商品説明2 (スペルミスは元のDB定義に合わせる)
     * @param string $product_How 使い方
     * @param string $product_Contents 内容物
     * @param int $product_stock 在庫数
     * @param float $product_degree アルコール度数
     * @return int|false 挿入されたproduct_id、または失敗した場合はfalse
     */
    public function insert_product($debug, $product_name, $product_price, $product_category, $product_description, $product_discription, $product_How, $product_Contents, $product_stock, $product_degree) {
        $query = "INSERT INTO product_info (product_name, product_price, product_category, product_description, product_discription, product_How, product_Contents, product_stock, product_degree) VALUES (:product_name, :product_price, :product_category, :product_description, :product_discription, :product_How, :product_Contents, :product_stock, :product_degree)";
        $prep_arr = array(
            ':product_name' => $product_name,
            ':product_price' => (float)$product_price,
            ':product_category' => $product_category, // カテゴリーIDを直接渡す
            ':product_description' => $product_description,
            ':product_discription' => $product_discription,
            ':product_How' => $product_How,
            ':product_Contents' => $product_Contents,
            ':product_stock' => (int)$product_stock,
            ':product_degree' => (float)$product_degree
        );
        $result = $this->execute_query($debug, $query, $prep_arr);
        if ($result) {
            return $this->last_insert_id(); // 挿入された product_id を返す
        }
        return false;
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) AS total_count FROM product_info WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit) {
        $arr = array();
        $query = "SELECT * FROM product_info WHERE 1 ORDER BY product_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id) {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM product_info WHERE product_id = :product_id";
        $prep_arr = array(':product_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct() {
        parent::__destruct();
    }
}

//--------------------------------------------------------------------------------------
/// カテゴリークラス
//--------------------------------------------------------------------------------------
class ccategories extends crecord {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) AS total_count FROM categories WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit) {
        $arr = array();
        $query = "SELECT * FROM categories WHERE 1 ORDER BY category_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id) {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM categories WHERE category_id = :category_id";
        $prep_arr = array(':category_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct() {
        parent::__destruct();
    }
}

//--------------------------------------------------------------------------------------
/// タグクラス (product_info のタグ)
//--------------------------------------------------------------------------------------
// ctags クラスは既存の otumami_tags とは別に、product_info 用として再定義します。
// 今回の要件に合わせて、tag_category_id を持つタグの情報を扱います。
class ctags_for_products extends crecord {
    public function __construct() {
        parent::__construct();
    }

    /**
     * 全てのタグ情報を取得するメソッド (タグカテゴリーIDを含む)
     * @param bool $debug デバッグモードのオン/オフ
     * @return array|false タグ情報の配列、または失敗した場合はfalse
     */
    public function get_all_tags_with_category($debug) {
        $query = "SELECT t.tag_id, t.tag_category_id, t.tag_name, tc.tag_category_name
                  FROM tags t
                  JOIN tag_categories tc ON t.tag_category_id = tc.tag_category_id
                  ORDER BY tc.tag_category_id ASC, t.tag_id ASC";
        $stmt = $this->execute_query($debug, $query);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) AS total_count FROM tags WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit) {
        $arr = array();
        $query = "SELECT * FROM tags WHERE 1 ORDER BY tag_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id) {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM tags WHERE tag_id = :tag_id";
        $prep_arr = array(':tag_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct() {
        parent::__destruct();
    }
}

//--------------------------------------------------------------------------------------
/// タグカテゴリークラス (product_info のタグカテゴリー)
//--------------------------------------------------------------------------------------
class ctag_categories_for_products extends crecord {
    public function __construct() {
        parent::__construct();
    }

    /**
     * 全てのタグカテゴリー情報を取得するメソッド
     * @param bool $debug デバッグモードのオン/オフ
     * @return array|false タグカテゴリー情報の配列、または失敗した場合はfalse
     */
    public function get_all_tag_categories($debug) {
        $query = "SELECT * FROM tag_categories ORDER BY tag_category_id ASC";
        $stmt = $this->execute_query($debug, $query);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) AS total_count FROM tag_categories WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit) {
        $arr = array();
        $query = "SELECT * FROM tag_categories WHERE 1 ORDER BY tag_category_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id) {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM tag_categories WHERE tag_category_id = :tag_category_id";
        $prep_arr = array(':tag_category_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }


    public function __destruct() {
        parent::__destruct();
    }
}

//--------------------------------------------------------------------------------------
/// 商品とタグの関連付けクラス (中間テーブル)
//--------------------------------------------------------------------------------------
class cproduct_tags_relation extends crecord {
    public function __construct() {
        parent::__construct();
    }

    /**
     * 商品とタグの関連情報をデータベースに挿入するメソッド
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $product_id 商品ID
     * @param int $tag_id タグID
     * @return bool 成功した場合はtrue、失敗した場合はfalse
     */
    public function insert_product_tag_relation($debug, $product_id, $tag_id) {
        $query = "INSERT INTO product_tags_relation (product_id, tag_id) VALUES (:product_id, :tag_id)";
        $prep_arr = array(
            ':product_id' => (int)$product_id,
            ':tag_id' => (int)$tag_id
        );
        return $this->execute_query($debug, $query, $prep_arr);
    }

    /**
     * 特定の商品IDに紐づく全てのタグを取得するメソッド
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $product_id 商品のID
     * @return array タグ情報の配列、または見つからない場合は空の配列
     */
    public function get_tags_by_product_id($debug, $product_id) {
        if (!cutil::is_number($product_id) || $product_id < 1) {
            return [];
        }
        $arr = array();
        $query = "SELECT ptr.tag_id, t.tag_name, tc.tag_category_name
                  FROM product_tags_relation ptr
                  JOIN tags t ON ptr.tag_id = t.tag_id
                  JOIN tag_categories tc ON t.tag_category_id = tc.tag_category_id
                  ORDER BY tc.tag_category_id ASC, t.tag_id ASC
                  WHERE ptr.product_id = :product_id"; // WHERE句を移動
        $stmt = $this->execute_query($debug, $query, array(':product_id' => (int)$product_id));
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    /**
     * 特定の商品IDに紐づく全てのタグ関連付けを削除するメソッド
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $product_id 商品ID
     * @return bool 成功した場合はtrue、失敗した場合はfalse
     */
    public function delete_product_tags_by_product_id($debug, $product_id) {
        if (!cutil::is_number($product_id) || $product_id < 1) {
            return false;
        }
        $query = "DELETE FROM product_tags_relation WHERE product_id = :product_id";
        $prep_arr = array(':product_id' => (int)$product_id);
        return $this->execute_query($debug, $query, $prep_arr);
    }

    public function __destruct() {
        parent::__destruct();
    }
}


//--------------------------------------------------------------------------------------
/// 投稿クラス
//--------------------------------------------------------------------------------------
class cposts extends crecord {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) AS total_count FROM posts WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit) {
        $arr = array();
        $query = "SELECT * FROM posts WHERE 1 ORDER BY post_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id) {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM posts WHERE post_id = :post_id";
        $prep_arr = array(':post_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    /**
     * 新しい投稿をデータベースに挿入するメソッド
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $user_id 投稿ユーザーのID
     * @param string $post_title 投稿タイトル
     * @param string $post_content 投稿内容
     * @return int|false 挿入されたpost_id、または失敗した場合はfalse
     */
    public function insert_post($debug, $user_id, $post_title, $post_content) {
        $query = "INSERT INTO posts (user_id, post_title, post_content) VALUES (:user_id, :post_title, :post_content)";
        $prep_arr = array(
            ':user_id' => (int)$user_id,
            ':post_title' => $post_title,
            ':post_content' => $post_content
        );
        $result = $this->execute_query($debug, $query, $prep_arr);
        if ($result) {
            return $this->last_insert_id(); // 挿入された post_id を返す
        }
        return false;
    }

    /**
     * 特定のユーザーの投稿を全て取得するメソッド (マイページ用)
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $user_id ユーザーID
     * @return array 投稿データの配列
     */
    public function get_posts_by_user_id($debug, $user_id) {
        if (!cutil::is_number($user_id) || $user_id < 1) {
            return [];
        }
        $arr = [];
        $query = "SELECT * FROM posts WHERE user_id = :user_id ORDER BY post_id DESC";
        $this->select_query($debug, $query, array(':user_id' => (int)$user_id));
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    /**
     * 複数の投稿IDに基づいて投稿データを取得するメソッド
     * @param bool $debug デバッグモードのオン/オフ
     * @param array $post_ids 取得したい投稿IDの配列
     * @return array 投稿データの配列
     */
    public function get_posts_by_ids($debug, $post_ids) {
        if (empty($post_ids)) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($post_ids), '?'));
        $query = "SELECT * FROM posts WHERE post_id IN ($placeholders) ORDER BY post_id DESC";

        $prep_arr = array_map('intval', $post_ids);

        $this->select_query($debug, $query, $prep_arr);

        $arr = [];
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    /**
     * 特定の投稿を削除するメソッド
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $post_id 投稿ID
     * @param int $user_id ユーザーID (所有者であるか確認用)
     * @return bool 削除が成功した場合はtrue、失敗した場合はfalse
     */
    public function delete_post($debug, $post_id, $user_id) {
        if (!cutil::is_number($post_id) || $post_id < 1 || !cutil::is_number($user_id) || $user_id < 1) {
            return false;
        }
        $query = "DELETE FROM posts WHERE post_id = :post_id AND user_id = :user_id";
        $prep_arr = array(
            ':post_id' => (int)$post_id,
            ':user_id' => (int)$user_id
        );
        return $this->execute_query($debug, $query, $prep_arr);
    }

    public function __destruct() {
        parent::__destruct();
    }
}

//--------------------------------------------------------------------------------------
/// 投稿画像クラス (MyPage.php で参照されるクラス)
//--------------------------------------------------------------------------------------
class cpost_images extends crecord {
    public function __construct() {
        parent::__construct();
    }

    /**
     * 新しい投稿画像をデータベースに挿入するメソッド
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $post_id 関連する投稿のID
     * @param string $image_path 画像ファイルのパス
     * @param int $display_order 画像の表示順序
     * @return int|false 挿入されたimage_id、または失敗した場合はfalse
     */
    public function insert_image($debug, $post_id, $image_path, $display_order) {
        $query = "INSERT INTO post_images (post_id, image_path, display_order) VALUES (:post_id, :image_path, :display_order)";
        $prep_arr = array(
            ':post_id' => (int)$post_id,
            ':image_path' => $image_path,
            ':display_order' => (int)$display_order
        );
        $result = $this->execute_query($debug, $query, $prep_arr);
        if ($result) {
            return $this->last_insert_id(); // 挿入された image_id を返す
        }
        return false;
    }

    /**
     * 特定の投稿IDに紐づく全ての画像を取得するメソッド
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $post_id 投稿のID
     * @return array 投稿画像情報の配列、または見つからない場合は空の配列
     */
    public function get_images_by_post_id($debug, $post_id) {
        if (!cutil::is_number($post_id) || $post_id < 1) {
            return [];
        }
        $arr = [];
        $query = "SELECT * FROM post_images WHERE post_id = :post_id ORDER BY display_order ASC, image_id ASC";
        $stmt = $this->execute_query($debug, $query, array(':post_id' => (int)$post_id));
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    /**
     * 特定の投稿に紐づく画像を全て削除するメソッド
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $post_id 投稿ID
     * @return bool 成功した場合はtrue、失敗した場合はfalse
     */
    public function delete_images_by_post_id($debug, $post_id) {
        if (!cutil::is_number($post_id) || $post_id < 1) {
            return false;
        }
        $query = "DELETE FROM post_images WHERE post_id = :post_id";
        $prep_arr = array(':post_id' => (int)$post_id);
        return $this->execute_query($debug, $query, $prep_arr);
    }

    public function __destruct() {
        parent::__destruct();
    }
}


//--------------------------------------------------------------------------------------
/// 問い合わせクラス
//--------------------------------------------------------------------------------------
class ccontacts extends crecord {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) AS total_count FROM contacts WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit) {
        $arr = array();
        $query = "SELECT * FROM contacts WHERE 1 ORDER BY contact_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id) {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM contacts WHERE contact_id = :contact_id";
        $prep_arr = array(':contact_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct() {
        parent::__destruct();
    }
}

//--------------------------------------------------------------------------------------
/// FAQクラス
//--------------------------------------------------------------------------------------
class cfaqs extends crecord {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) AS total_count FROM faqs WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit) {
        $arr = array();
        $query = "SELECT * FROM faqs WHERE 1 ORDER BY faq_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id) {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM faqs WHERE faq_id = :faq_id";
        $prep_arr = array(':faq_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct() {
        parent::__destruct();
    }
}

//--------------------------------------------------------------------------------------
/// FAQカテゴリークラス
//--------------------------------------------------------------------------------------
class cfaq_categories extends crecord {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) AS total_count FROM faq_categories WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit) {
        $arr = array();
        $query = "SELECT * FROM faq_categories WHERE 1 ORDER BY faq_category_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id) {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM faq_categories WHERE faq_category_id = :faq_category_id";
        $prep_arr = array(':faq_category_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }


    public function __destruct() {
        parent::__destruct();
    }
}

//--------------------------------------------------------------------------------------
/// マイページクラス
//--------------------------------------------------------------------------------------
class cMypage extends crecord {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) AS total_count FROM Mypage WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit) {
        $arr = array();
        $query = "SELECT * FROM Mypage WHERE 1 ORDER BY profile_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id) {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM Mypage WHERE profile_id = :profile_id";
        $prep_arr = array(':profile_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct() {
        parent::__destruct();
    }
}

//--------------------------------------------------------------------------------------
/// 通報情報クラス
//--------------------------------------------------------------------------------------
class creport_info extends crecord {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) AS total_count FROM report_info WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit) {
        $arr = array();
        $query = "SELECT * FROM report_info WHERE 1 ORDER BY report_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id) {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM report_info WHERE report_id = :report_id";
        $prep_arr = array(':report_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct() {
        parent::__destruct();
    }
}

//--------------------------------------------------------------------------------------
/// いいね！クラス
//--------------------------------------------------------------------------------------
class cgood extends crecord {
    public function __construct() {
        parent::__construct();
    }

    /**
     * ユーザーが良いねした投稿を登録する
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $user_id いいねしたユーザーID
     * @param int $post_id いいねされた投稿ID
     * @return bool 成功した場合はtrue、失敗した場合はfalse
     */
    public function insert_good($debug, $user_id, $post_id) {
        $query = "INSERT INTO good (user_id, post_id) VALUES (:user_id, :post_id)";
        $prep_arr = array(
            ':user_id' => (int)$user_id,
            ':post_id' => (int)$post_id
        );
        return $this->execute_query($debug, $query, $prep_arr);
    }

    /**
     * ユーザーが良いねした投稿を削除する
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $user_id いいねを取り消すユーザーID
     * @param int $post_id いいねを取り消される投稿ID
     * @return bool 成功した場合はtrue、失敗した場合はfalse
     */
    public function delete_good($debug, $user_id, $post_id) {
        $query = "DELETE FROM good WHERE user_id = :user_id AND post_id = :post_id";
        $prep_arr = array(
            ':user_id' => (int)$user_id,
            ':post_id' => (int)$post_id
        );
        return $this->execute_query($debug, $query, $prep_arr);
    }

    /**
     * 特定の投稿の良いね数を取得する
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $post_id 投稿ID
     * @return int いいね数
     */
    public function count_good_by_post_id($debug, $post_id) {
        if (!cutil::is_number($post_id) || $post_id < 1) {
            return 0;
        }
        $query = "SELECT COUNT(*) AS good_count FROM good WHERE post_id = :post_id";
        $this->select_query($debug, $query, array(':post_id' => (int)$post_id));
        $row = $this->fetch_assoc();
        return $row ? (int)$row['good_count'] : 0;
    }

    /**
     * 特定のユーザーが特定の投稿に良いねしているかチェックする
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $user_id ユーザーID
     * @param int $post_id 投稿ID
     * @return bool いいねしている場合はtrue、していない場合はfalse
     */
    public function is_good_by_user($debug, $user_id, $post_id) {
        if (!cutil::is_number($user_id) || $user_id < 1 || !cutil::is_number($post_id) || $post_id < 1) {
            return false;
        }
        $query = "SELECT COUNT(*) AS count_result FROM good WHERE user_id = :user_id AND post_id = :post_id";
        $this->select_query($debug, $query, array(
            ':user_id' => (int)$user_id,
            ':post_id' => (int)$post_id
        ));
        $row = $this->fetch_assoc();
        return $row && $row['count_result'] > 0;
    }

    /**
     * 特定のユーザーが良いねした投稿のIDリストを取得する
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $user_id ユーザーID
     * @return array いいねした投稿のIDの配列
     */
    public function get_liked_post_ids_by_user_id($debug, $user_id) {
        if (!cutil::is_number($user_id) || $user_id < 1) {
            return [];
        }
        $arr = [];
        $query = "SELECT post_id FROM good WHERE user_id = :user_id ORDER BY good_id DESC";
        $this->select_query($debug, $query, array(':user_id' => (int)$user_id));
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row['post_id'];
        }
        return $arr;
    }

    /**
     * 特定の投稿IDに関連する全てのいいねを削除するメソッド (delete_post.php用)
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $post_id 投稿ID
     * @return bool 成功した場合はtrue、失敗した場合はfalse
     */
    public function delete_all_goods_by_post_id($debug, $post_id) {
        if (!cutil::is_number($post_id) || $post_id < 1) {
            return false;
        }
        $query = "DELETE FROM good WHERE post_id = :post_id";
        $prep_arr = array(':post_id' => (int)$post_id);
        return $this->execute_query($debug, $query, $prep_arr);
    }

    public function __destruct() {
        parent::__destruct();
    }
}

//--------------------------------------------------------------------------------------
/// ハートクラス
//--------------------------------------------------------------------------------------
class cheart extends crecord {
    public function __construct() {
        parent::__construct();
    }

    /**
     * ユーザーがハート（ブックマーク）した投稿を登録する
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $user_id ハートしたユーザーID
     * @param int $post_id ハートされた投稿ID
     * @return bool 成功した場合はtrue、失敗した場合はfalse
     */
    public function insert_heart($debug, $user_id, $post_id) {
        $query = "INSERT INTO heart (user_id, post_id) VALUES (:user_id, :post_id)";
        $prep_arr = array(
            ':user_id' => (int)$user_id,
            ':post_id' => (int)$post_id
        );
        return $this->execute_query($debug, $query, $prep_arr);
    }

    /**
     * ユーザーがハート（ブックマーク）した投稿を削除する
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $user_id ハートを取り消すユーザーID
     * @param int $post_id ハートを取り消される投稿ID
     * @return bool 成功した場合はtrue、失敗した場合はfalse
     */
    public function delete_heart($debug, $user_id, $post_id) {
        $query = "DELETE FROM heart WHERE user_id = :user_id AND post_id = :post_id";
        $prep_arr = array(
            ':user_id' => (int)$user_id,
            ':post_id' => (int)$post_id
        );
        return $this->execute_query($debug, $query, $prep_arr);
    }

    /**
     * 特定の投稿のハート数を取得する
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $post_id 投稿ID
     * @return int ハート数
     */
    public function count_heart_by_post_id($debug, $post_id) {
        if (!cutil::is_number($post_id) || $post_id < 1) {
            return 0;
        }
        $query = "SELECT COUNT(*) AS heart_count FROM heart WHERE post_id = :post_id";
        $this->select_query($debug, $query, array(':post_id' => (int)$post_id));
        $row = $this->fetch_assoc();
        return $row ? (int)$row['heart_count'] : 0;
    }

    /**
     * 特定のユーザーが特定の投稿にハートしているかチェックする
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $user_id ユーザーID
     * @param int $post_id 投稿ID
     * @return bool ハートしている場合はtrue、していない場合はfalse
     */
    public function is_heart_by_user($debug, $user_id, $post_id) {
        if (!cutil::is_number($user_id) || $user_id < 1 || !cutil::is_number($post_id) || $post_id < 1) {
            return false;
        }
        $query = "SELECT COUNT(*) AS count_result FROM heart WHERE user_id = :user_id AND post_id = :post_id";
        $this->select_query($debug, $query, array(
            ':user_id' => (int)$user_id,
            ':post_id' => (int)$post_id
        ));
        $row = $this->fetch_assoc();
        return $row && $row['count_result'] > 0;
    }

    /**
     * 特定のユーザーがハートした投稿のIDリストを取得する
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $user_id ユーザーID
     * @return array ハートした投稿のIDの配列
     */
    public function get_hearted_post_ids_by_user_id($debug, $user_id) {
        if (!cutil::is_number($user_id) || $user_id < 1) {
            return [];
        }
        $arr = [];
        $query = "SELECT post_id FROM heart WHERE user_id = :user_id ORDER BY heart_id DESC";
        $this->select_query($debug, $query, array(':user_id' => (int)$user_id));
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row['post_id'];
        }
        return $arr;
    }

    /**
     * 特定の投稿IDに関連する全てのハートを削除するメソッド (delete_post.php用)
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $post_id 投稿ID
     * @return bool 成功した場合はtrue、失敗した場合はfalse
     */
    public function delete_all_hearts_by_post_id($debug, $post_id) {
        if (!cutil::is_number($post_id) || $post_id < 1) {
            return false;
        }
        $query = "DELETE FROM heart WHERE post_id = :post_id";
        $prep_arr = array(':post_id' => (int)$post_id);
        return $this->execute_query($debug, $query, $prep_arr);
    }

    public function __destruct() {
        parent::__destruct();
    }
}

//--------------------------------------------------------------------------------------
/// 問い合わせ返信クラス
//--------------------------------------------------------------------------------------
class ccontacts_reply extends crecord {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) AS total_count FROM contacts_reply WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit) {
        $arr = array();
        $query = "SELECT * FROM contacts_reply WHERE 1 ORDER BY contact_reply_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id) {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM contacts_reply WHERE contact_reply_id = :contact_reply_id";
        $prep_arr = array(':contact_reply_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct() {
        parent::__destruct();
    }
}

//--------------------------------------------------------------------------------------
/// おつまみカテゴリークラス
//--------------------------------------------------------------------------------------
class cotumami_categories extends crecord {
    public function __construct() {
        parent::__construct();
    }

    /**
     * 全てのカテゴリー情報を取得するメソッド
     * @param bool $debug デバッグモードのオン/オフ
     * @return array|false カテゴリー情報の配列、または失敗した場合はfalse
     */
    public function get_all_categories($debug) {
        $query = "SELECT * FROM otumami_categories ORDER BY category_id ASC";
        $stmt = $this->execute_query($debug, $query);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) AS total_count FROM otumami_categories WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit) {
        $arr = array();
        $query = "SELECT * FROM otumami_categories WHERE 1 ORDER BY category_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id) {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM otumami_categories WHERE category_id = :category_id";
        $prep_arr = array(':category_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct() {
        parent::__destruct();
    }
}

//--------------------------------------------------------------------------------------
/// おつまみタグクラス
//--------------------------------------------------------------------------------------
class cotumami_tags extends crecord {
    public function __construct() {
        parent::__construct();
    }

    /**
     * 全てのタグ情報を取得するメソッド
     * @param bool $debug デバッグモードのオン/オフ
     * @return array|false タグ情報の配列、または失敗した場合はfalse
     */
    public function get_all_tags($debug) {
        $query = "SELECT * FROM otumami_tags ORDER BY tag_id ASC";
        $stmt = $this->execute_query($debug, $query);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) AS total_count FROM otumami_tags WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit) {
        $arr = array();
        $query = "SELECT * FROM otumami_tags WHERE 1 ORDER BY tag_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id) {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM otumami_tags WHERE tag_id = :tag_id";
        $prep_arr = array(':tag_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct() {
        parent::__destruct();
    }
}

//--------------------------------------------------------------------------------------
/// おつまみクラス
//--------------------------------------------------------------------------------------
class cotumami extends crecord {
    public function __construct() {
        parent::__construct();
    }

    /**
     * 新しいおつまみ情報をデータベースに挿入するメソッド
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $combi_category_id 合うお酒のカテゴリーID
     * @param string $otumami_name おつまみ名
     * @param float $otumami_price 値段
     * @param string $otumami_description おつまみの説明1
     * @param string $otumami_discription おつまみの説明2 (スペルミスは元のDB定義に合わせる)
     * @param int $otumami_stock 在庫数
     * @return int|false 挿入されたotumami_id、または失敗した場合はfalse
     */
    public function insert_otumami($debug, $combi_category_id, $otumami_name, $otumami_price, $otumami_description, $otumami_discription, $otumami_stock) {
        $query = "INSERT INTO otumami (combi_category_id, otumami_name, otumami_price, otumami_description, otumami_discription, otumami_stock) VALUES (:combi_category_id, :otumami_name, :otumami_price, :otumami_description, :otumami_discription, :otumami_stock)";
        $prep_arr = array(
            ':combi_category_id' => (int)$combi_category_id,
            ':otumami_name' => $otumami_name,
            ':otumami_price' => (float)$otumami_price,
            ':otumami_description' => $otumami_description,
            ':otumami_discription' => $otumami_discription, // スキルのミスを保持
            ':otumami_stock' => (int)$otumami_stock
        );
        $result = $this->execute_query($debug, $query, $prep_arr);
        if ($result) {
            return $this->last_insert_id(); // 挿入された otumami_id を返す
        }
        return false;
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) AS total_count FROM otumami WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit) {
        $arr = array();
        $query = "SELECT * FROM otumami WHERE 1 ORDER BY otumami_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id) {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM otumami WHERE otumami_id = :otumami_id";
        $prep_arr = array(':otumami_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct() {
        parent::__destruct();
    }
}

//--------------------------------------------------------------------------------------
/// おつまみ画像クラス (新規追加)
//--------------------------------------------------------------------------------------
class cotumami_images extends crecord {
    public function __construct() {
        parent::__construct();
    }

    /**
     * 新しいおつまみ画像をデータベースに挿入するメソッド
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $otumami_id 関連するおつまみのID
     * @param string $image_path 画像ファイルのパス
     * @param string $image_type 画像の種類 ('main' または 'sub')
     * @param int $display_order 画像の表示順序
     * @return int|false 挿入されたimage_id、または失敗した場合はfalse
     */
    public function insert_image($debug, $otumami_id, $image_path, $image_type, $display_order) {
        $query = "INSERT INTO otumami_images (otumami_id, image_path, image_type, display_order) VALUES (:otumami_id, :image_path, :image_type, :display_order)";
        $prep_arr = array(
            ':otumami_id' => (int)$otumami_id,
            ':image_path' => $image_path,
            ':image_type' => $image_type,
            ':display_order' => (int)$display_order
        );
        $result = $this->execute_query($debug, $query, $prep_arr);
        if ($result) {
            return $this->last_insert_id(); // 挿入された image_id を返す
        }
        return false;
    }

    /**
     * 特定のおつまみIDに紐づく全ての画像を取得するメソッド
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $otumami_id おつまみのID
     * @return array 投稿画像情報の配列、または見つからない場合は空の配列
     */
    public function get_images_by_otumami_id($debug, $otumami_id) {
        if (!cutil::is_number($otumami_id) || $otumami_id < 1) {
            return [];
        }
        $arr = [];
        $query = "SELECT * FROM otumami_images WHERE otumami_id = :otumami_id ORDER BY display_order ASC, image_id ASC";
        $stmt = $this->execute_query($debug, $query, array(':otumami_id' => (int)$otumami_id));
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function __destruct() {
        parent::__destruct();
    }
}


//--------------------------------------------------------------------------------------
/// おつまみとタグの関連付けクラス (中間テーブル)
//--------------------------------------------------------------------------------------
class cotumami_otumami_tags extends crecord {
    public function __construct() {
        parent::__construct();
    }

    /**
     * おつまみとタグの関連情報をデータベースに挿入するメソッド
     * @param bool $debug デバッグモードのオン/オフ
     * @param int $otumami_id おつまみID
     * @param int $tag_id タグID
     * @return bool 成功した場合はtrue、失敗した場合はfalse
     */
    public function insert_otumami_tag_relation($debug, $otumami_id, $tag_id) {
        $query = "INSERT INTO otumami_otumami_tags (otumami_id, tag_id) VALUES (:otumami_id, :tag_id)";
        $prep_arr = array(
            ':otumami_id' => (int)$otumami_id,
            ':tag_id' => (int)$tag_id
        );
        return $this->execute_query($debug, $query, $prep_arr);
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) AS total_count FROM otumami_otumami_tags WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit) {
        $arr = array();
        $query = "SELECT * FROM otumami_otumami_tags WHERE 1 ORDER BY otumami_id ASC, tag_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    // 複合主キーのため、get_tgt は通常は使用せず、特定の用途のメソッドを定義します。
    // 例: 特定のおつまみに紐づくすべてのタグを取得
    public function get_by_otumami_id($debug, $otumami_id) {
        if (!cutil::is_number($otumami_id) || $otumami_id < 1) {
            return false;
        }
        $arr = array();
        $query = "SELECT tag_id FROM otumami_otumami_tags WHERE otumami_id = :otumami_id ORDER BY tag_id ASC"; // タグIDのみを取得
        $stmt = $this->execute_query($debug, $query, array(':otumami_id' => (int)$otumami_id));
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    // 例: 特定のタグに紐づくすべてのおつまみを取得
    public function get_by_tag_id($debug, $tag_id) {
        if (!cutil::is_number($tag_id) || $tag_id < 1) {
            return false;
        }
        $arr = array();
        $query = "SELECT otumami_id FROM otumami_otumami_tags WHERE tag_id = :tag_id ORDER BY otumami_id ASC"; // otumami_idのみを取得
        $stmt = $this->execute_query($debug, $query, array(':tag_id' => (int)$tag_id));
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function __destruct() {
        parent::__destruct();
    }
}

//--------------------------------------------------------------------------------------
/// カートクラス
//--------------------------------------------------------------------------------------
class ccarts extends crecord {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) AS total_count FROM carts WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit) {
        $arr = array();
        $query = "SELECT * FROM carts WHERE 1 ORDER BY cart_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id) {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM carts WHERE cart_id = :cart_id";
        $prep_arr = array(':cart_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct() {
        parent::__destruct();
    }
}

//--------------------------------------------------------------------------------------
/// カートアイテムクラス
//--------------------------------------------------------------------------------------
class ccart_items extends crecord {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) AS total_count FROM cart_items WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit) {
        $arr = array();
        $query = "SELECT * FROM cart_items WHERE 1 ORDER BY cart_item_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id) {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM cart_items WHERE cart_item_id = :cart_item_id";
        $prep_arr = array(':cart_item_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct() {
        parent::__destruct();
    }
}

//--------------------------------------------------------------------------------------
/// 注文情報クラス
//--------------------------------------------------------------------------------------
class corders extends crecord {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) AS total_count FROM orders WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit) {
        $arr = array();
        $query = "SELECT * FROM orders WHERE 1 ORDER BY order_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id) {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM orders WHERE order_id = :order_id";
        $prep_arr = array(':order_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct() {
        parent::__destruct();
    }
}

//--------------------------------------------------------------------------------------
/// 注文アイテムクラス
//--------------------------------------------------------------------------------------
class corder_items extends crecord {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) AS total_count FROM order_items WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit) {
        $arr = array();
        $query = "SELECT * FROM order_items WHERE 1 ORDER BY order_item_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id) {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM order_items WHERE order_item_id = :order_item_id";
        $prep_arr = array(':order_item_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct() {
        parent::__destruct();
    }
}

//--------------------------------------------------------------------------------------
/// 管理者ユーザー情報クラス
//--------------------------------------------------------------------------------------
class cadmin_user_info extends crecord {
    public function __construct() {
        parent::__construct();
    }

    /**
     * 新しい管理者ユーザーをデータベースに挿入するメソッド
     * admin_user_id は AUTO_INCREMENT なので、挿入後に lastInsertId() でIDを取得する必要がある
     * @param bool $debug デバッグモードのオン/オフ
     * @param string $admin_user_name 管理者ユーザー名
     * @param string $admin_user_pass_hashed ハッシュ化された管理者パスワード
     * @return int|false 挿入されたadmin_user_id、または失敗した場合はfalse
     */
    public function insert_admin_user($debug, $admin_user_name, $admin_user_pass_hashed) {
        $query = "INSERT INTO admin_user_info (admin_user_name, admin_user_pass) VALUES (:admin_user_name, :admin_user_pass)";
        $prep_arr = array(
            ':admin_user_name' => $admin_user_name,
            ':admin_user_pass' => $admin_user_pass_hashed
        );
        $result = $this->execute_query($debug, $query, $prep_arr);
        if ($result) {
            return $this->last_insert_id(); // 挿入された admin_user_id を返す
        }
        return false;
    }

    /**
     * 管理者ユーザー名で管理者ユーザー情報を取得するメソッド（ログイン認証用）
     * @param bool $debug デバッグモードのオン/オフ
     * @param string $admin_user_name 検索する管理者ユーザー名
     * @return array|false ユーザー情報（連想配列）、または見つからない場合はfalse
     */
    public function get_admin_user_by_name($debug, $admin_user_name) {
        $query = "SELECT admin_user_id, admin_user_name, admin_user_pass FROM admin_user_info WHERE admin_user_name = :admin_user_name";
        $prep_arr = array(':admin_user_name' => (string)$admin_user_name);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct() {
        parent::__destruct();
    }
}
?>
