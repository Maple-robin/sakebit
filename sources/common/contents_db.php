<?php
/*!
@file contents_db.php
@brief データベース操作クラス群
@copyright Copyright (c) 2024 Your Name. (元の著作者名を尊重しつつ、プロジェクト名を入れることを推奨)
*/

// config.phpをインクルードしてDB接続定数を読み込む
require_once __DIR__ . '/config.php'; // config.phpのパスを適切に設定してください

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
            echo "DB接続エラー: " . $e->getMessage();
            exit();
        }
    }

    protected function select_query($debug, $query, $prep_arr) {
        if ($debug) {
            echo "<pre>Debug SQL: " . htmlspecialchars($query) . "</pre>";
            echo "<pre>Debug Params: " . htmlspecialchars(print_r($prep_arr, true)) . "</pre>";
        }
        $this->stmt = $this->pdo->prepare($query);
        $this->stmt->execute($prep_arr);
    }

    // INSERT, UPDATE, DELETEなどの書き込み操作を実行するメソッド
    protected function execute_query($debug, $query, $prep_arr = array()) {
        if ($debug) {
            echo "<pre>Debug SQL: " . htmlspecialchars($query) . "</pre>";
            echo "<pre>Debug Params: " . htmlspecialchars(print_r($prep_arr, true)) . "</pre>";
        }
        try {
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute($prep_arr);
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage() . " SQL: " . $query . " Params: " . json_encode($prep_arr));
            if ($debug) {
                echo "<pre>Error: " . htmlspecialchars($e->getMessage()) . "</pre>";
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
        $query = "SELECT COUNT(*) FROM user_info WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['count(*)'];
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

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) FROM product_info WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['count(*)'];
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
        $query = "SELECT COUNT(*) FROM categories WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['count(*)'];
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
/// タグクラス
//--------------------------------------------------------------------------------------
class ctags extends crecord {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) FROM tags WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['count(*)'];
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
/// 投稿クラス
//--------------------------------------------------------------------------------------
class cposts extends crecord {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) FROM posts WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['count(*)'];
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
        $query = "SELECT COUNT(*) FROM contacts WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['count(*)'];
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
        $query = "SELECT COUNT(*) FROM faqs WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['count(*)'];
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
        $query = "SELECT COUNT(*) FROM faq_categories WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['count(*)'];
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
        $query = "SELECT COUNT(*) FROM Mypage WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['count(*)'];
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
        $query = "SELECT COUNT(*) FROM report_info WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['count(*)'];
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

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) FROM good WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['count(*)'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit) {
        $arr = array();
        $query = "SELECT * FROM good WHERE 1 ORDER BY good_id ASC LIMIT :from, :limit";
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
        $query = "SELECT * FROM good WHERE good_id = :good_id";
        $prep_arr = array(':good_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
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

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) FROM heart WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['count(*)'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit) {
        $arr = array();
        $query = "SELECT * FROM heart WHERE 1 ORDER BY heart_id ASC LIMIT :from, :limit";
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
        $query = "SELECT * FROM heart WHERE heart_id = :heart_id";
        $prep_arr = array(':heart_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
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
        $query = "SELECT COUNT(*) FROM contacts_reply WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['count(*)'];
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

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) FROM otumami_categories WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['count(*)'];
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

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) FROM otumami_tags WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['count(*)'];
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

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) FROM otumami WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['count(*)'];
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
/// おつまみとタグの関連付けクラス (中間テーブル)
//--------------------------------------------------------------------------------------
class cotumami_otumami_tags extends crecord {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_count($debug) {
        $query = "SELECT COUNT(*) FROM otumami_otumami_tags WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['count(*)'];
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
        $query = "SELECT * FROM otumami_otumami_tags WHERE otumami_id = :otumami_id ORDER BY tag_id ASC";
        $prep_arr = array(':otumami_id' => (int)$otumami_id);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    // 例: 特定のタグに紐づくすべてのおつまみを取得
    public function get_by_tag_id($debug, $tag_id) {
        if (!cutil::is_number($tag_id) || $tag_id < 1) {
            return false;
        }
        $arr = array();
        $query = "SELECT * FROM otumami_otumami_tags WHERE tag_id = :tag_id ORDER BY otumami_id ASC";
        $prep_arr = array(':tag_id' => (int)$tag_id);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
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
        $query = "SELECT COUNT(*) FROM carts WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['count(*)'];
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
        $query = "SELECT COUNT(*) FROM cart_items WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['count(*)'];
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
        $query = "SELECT COUNT(*) FROM orders WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['count(*)'];
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
        $query = "SELECT COUNT(*) FROM order_items WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['count(*)'];
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
            ':admin_user_pass' => $admin_user_pass_hashed // ハッシュ化されたパスワード
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
        $prep_arr = array(':admin_user_name' => $admin_user_name);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct() {
        parent::__destruct();
    }
}

?>
