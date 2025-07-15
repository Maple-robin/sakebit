<?php
/*!
@file contents_db.php
@brief データベース操作クラス群
@copyright Copyright (c) 2024 Your Name.
*/

require_once __DIR__ . '/config.php';

class crecord
{
    protected $pdo;
    protected $stmt;

    public function __construct()
    {
        try {
            $dsn = DB_RDBMS . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            $user = DB_USER;
            $password = DB_PASS;

            $this->pdo = new PDO($dsn, $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            if (defined('DB_MYSQL_SET_NAMES') && DB_MYSQL_SET_NAMES === '1') {
                $this->pdo->exec("SET NAMES " . DB_CHARSET);
            }
        } catch (PDOException $e) {
            if (defined('DEBUG') && DEBUG) {
                echo "DB接続エラー: " . htmlspecialchars($e->getMessage());
            }
            error_log("DB Connection Error: " . $e->getMessage());
            exit();
        }
    }

    protected function select_query($debug, $query, $prep_arr)
    {
        if ($debug) {
            error_log("Debug SQL (select_query): " . $query);
            error_log("Debug Params (select_query): " . print_r($prep_arr, true));
        }
        $this->stmt = $this->pdo->prepare($query);
        $this->stmt->execute($prep_arr);
    }

    protected function execute_query($debug, $query, $prep_arr = array())
    {
        if ($debug) {
            error_log("Debug SQL (execute_query): " . $query);
            error_log("Debug Params (execute_query): " . print_r($prep_arr, true));
        }
        try {
            $stmt = $this->pdo->prepare($query);
            $result = $stmt->execute($prep_arr);

            if (preg_match('/^\s*SELECT/i', $query)) {
                return $stmt;
            }
            return $result;
        } catch (PDOException $e) {
            // エラーをログに記録
            $error_message_log = "Database Error: " . $e->getMessage() .
                " SQLSTATE: " . ($e->errorInfo[0] ?? 'N/A') .
                " SQLSTATE Code: " . ($e->errorInfo[1] ?? 'N/A') .
                " Driver Message: " . ($e->errorInfo[2] ?? 'N/A') .
                " Query: " . $query .
                " Params: . " . json_encode($prep_arr);
            error_log($error_message_log);

            // トランザクション処理を正しく行うため、例外を再度スローして呼び出し元にエラーを伝播させる
            throw $e;
        }
    }

    public function fetch_assoc()
    {
        if ($this->stmt) {
            return $this->stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function last_insert_id()
    {
        return $this->pdo->lastInsertId();
    }

    public function get_pdo()
    {
        return $this->pdo;
    }

    public function __destruct()
    {
        $this->stmt = null;
        $this->pdo = null;
    }
}

class cutil
{
    public static function is_number($value)
    {
        return is_numeric($value);
    }
}

class cuser_info extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_count($debug)
    {
        $query = "SELECT COUNT(*) AS total_count FROM user_info WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit)
    {
        $arr = array();
        $query = "SELECT * FROM user_info WHERE 1 ORDER BY user_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id)
    {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM user_info WHERE user_id = :user_id";
        $prep_arr = array(':user_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function insert_user($debug, $user_name, $user_email, $user_pass_hashed, $user_age)
    {
        $query = "INSERT INTO user_info (user_name, user_email, user_pass, user_age) VALUES (:user_name, :user_email, :user_pass, :user_age)";
        $prep_arr = array(
            ':user_name' => $user_name,
            ':user_email' => $user_email,
            ':user_pass' => $user_pass_hashed,
            ':user_age' => $user_age
        );
        $result = $this->execute_query($debug, $query, $prep_arr);
        if ($result) {
            return $this->last_insert_id();
        }
        return false;
    }

    public function get_user_by_email($debug, $email)
    {
        $query = "SELECT * FROM user_info WHERE user_email = :user_email";
        $prep_arr = array(':user_email' => $email);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function get_user_by_email_for_login($debug, $email)
    {
        $query = "SELECT user_id, user_name, user_email, user_pass, user_age FROM user_info WHERE user_email = :user_email";
        $prep_arr = array(':user_email' => $email);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function delete_user($debug, $user_id)
    {
        if (!cutil::is_number($user_id) || $user_id < 1) {
            return false;
        }
        $query = "DELETE FROM user_info WHERE user_id = :user_id";
        $prep_arr = array(':user_id' => (int)$user_id);
        return $this->execute_query($debug, $query, $prep_arr);
    }

    public function update_user_name($debug, $user_id, $user_name)
    {
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

    public function __destruct()
    {
        parent::__destruct();
    }
}

class cclient_user_info extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert_client_user($debug, $company_name, $representative_name, $email, $phone, $address, $password_hash)
    {
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
            return $this->last_insert_id();
        }
        return false;
    }

    public function get_client_user_by_email($debug, $email)
    {
        $query = "SELECT * FROM client_user_info WHERE email = :email";
        $prep_arr = array(':email' => $email);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function get_client_user_by_email_for_login($debug, $email)
    {
        $query = "SELECT client_id, company_name, representative_name, email, phone, address, password_hash FROM client_user_info WHERE email = :email";
        $prep_arr = array(':email' => $email);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}

class cuser_profiles extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert_profile($debug, $user_id, $profile_icon_url, $profile_text)
    {
        $query = "INSERT INTO user_profiles (user_id, profile_icon_url, profile_text) VALUES (:user_id, :profile_icon_url, :profile_text)";
        $prep_arr = array(
            ':user_id' => (int)$user_id,
            ':profile_icon_url' => $profile_icon_url,
            ':profile_text' => $profile_text
        );
        return $this->execute_query($debug, $query, $prep_arr);
    }

    public function get_profile_by_user_id($debug, $user_id)
    {
        if (!cutil::is_number($user_id) || $user_id < 1) {
            return false;
        }
        $query = "SELECT * FROM user_profiles WHERE user_id = :user_id";
        $prep_arr = array(':user_id' => (int)$user_id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function update_profile($debug, $user_id, $profile_icon_url, $profile_text)
    {
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

    public function __destruct()
    {
        parent::__destruct();
    }
}

class cproduct_info extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 【修正】新しい商品情報をデータベースに挿入するメソッド
     * client_id を引数に追加し、SQLに含めるように修正
     */
    public function insert_product($debug, $client_id, $product_name, $product_price, $product_category, $product_description, $product_discription, $product_How, $product_Contents, $product_stock, $product_degree)
    {
        $query = "INSERT INTO product_info (client_id, product_name, product_price, product_category, product_description, product_discription, product_How, product_Contents, product_stock, product_degree) VALUES (:client_id, :product_name, :product_price, :product_category, :product_description, :product_discription, :product_How, :product_Contents, :product_stock, :product_degree)";
        $prep_arr = array(
            ':client_id' => (int)$client_id,
            ':product_name' => $product_name,
            ':product_price' => (float)$product_price,
            ':product_category' => $product_category,
            ':product_description' => $product_description,
            ':product_discription' => $product_discription,
            ':product_How' => $product_How,
            ':product_Contents' => $product_Contents,
            ':product_stock' => (int)$product_stock,
            ':product_degree' => (float)$product_degree
        );
        $result = $this->execute_query($debug, $query, $prep_arr);
        if ($result) {
            return $this->last_insert_id();
        }
        return false;
    }

    public function get_all_count($debug)
    {
        $query = "SELECT COUNT(*) AS total_count FROM product_info WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit)
    {
        $arr = array();
        $query = "SELECT * FROM product_info WHERE 1 ORDER BY product_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id)
    {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM product_info WHERE product_id = :product_id";
        $prep_arr = array(':product_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    /**
     * 【修正】管理者向けの商品リストを取得するメソッド
     * client_user_info, categories, tags テーブルをLEFT JOINして
     * 企業名、カテゴリ名、タグ名をまとめて取得するように変更
     * created_at も追加で取得
     * また、client_id が指定されない場合は全件取得するように修正
     */
    public function get_product_list_for_admin($debug, $client_id = null, $from = 0, $limit = 100)
    {
        $query = "
            SELECT
                p.product_id,
                p.product_name,
                p.product_price,
                p.product_description,
                p.product_discription,
                p.product_How,
                p.product_Contents,
                p.product_stock,
                p.product_degree,
                p.created_at, -- ★追加: created_at を取得
                cu.company_name,
                c.category_name,
                GROUP_CONCAT(DISTINCT t.tag_name ORDER BY t.tag_id SEPARATOR ',') AS tags_concat,
                (
                    SELECT GROUP_CONCAT(pi.image_path ORDER BY pi.image_type = 'main' DESC, pi.display_order ASC, pi.image_id ASC SEPARATOR ';')
                    FROM product_images pi
                    WHERE pi.product_id = p.product_id
                    LIMIT 4
                ) AS image_paths
            FROM
                product_info p
            LEFT JOIN
                client_user_info cu ON p.client_id = cu.client_id
            LEFT JOIN
                categories c ON p.product_category = c.category_id
            LEFT JOIN
                product_tags_relation ptr ON p.product_id = ptr.product_id
            LEFT JOIN
                tags t ON ptr.tag_id = t.tag_id
        ";

        $prep_arr = [];
        $where_clauses = [];

        // client_id が指定され、かつ有効な数値の場合のみWHERE句を追加
        if ($client_id !== null && cutil::is_number($client_id) && $client_id > 0) {
            $where_clauses[] = "p.client_id = :client_id";
            $prep_arr[':client_id'] = (int)$client_id;
        }

        if (!empty($where_clauses)) {
            $query .= " WHERE " . implode(" AND ", $where_clauses);
        }

        $query .= "
            GROUP BY
                p.product_id
            ORDER BY
                p.product_id DESC
            LIMIT :from, :limit
        ";

        $prep_arr[':from']  = (int)$from;
        $prep_arr[':limit'] = (int)$limit;

        $arr = [];
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function decrease_stock($debug, $product_id, $quantity)
    {
        if (!cutil::is_number($product_id) || $product_id < 1 || !cutil::is_number($quantity) || $quantity < 1) {
            return false;
        }

        // このメソッドは、api/process_order.php のトランザクション内で呼び出されることを前提としています。
        try {
            // 1. 行をロックして現在の在庫数を取得
            $query_select = "SELECT product_stock FROM product_info WHERE product_id = :product_id FOR UPDATE";
            $stmt_select = $this->pdo->prepare($query_select);
            $stmt_select->execute([':product_id' => (int)$product_id]);
            $result = $stmt_select->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                // 商品が見つからない場合
                error_log("decrease_stock error: Product with ID {$product_id} not found.");
                return false;
            }

            $current_stock = (int)$result['product_stock'];

            // 2. PHP側で在庫数を確認
            if ($current_stock < $quantity) {
                // 在庫が不足している場合
                error_log("decrease_stock error: Insufficient stock for product ID {$product_id}. Required: {$quantity}, Available: {$current_stock}");
                return false;
            }

            // 3. 在庫数を更新
            $new_stock = $current_stock - (int)$quantity;
            $query_update = "UPDATE product_info SET product_stock = :new_stock WHERE product_id = :product_id";
            $stmt_update = $this->pdo->prepare($query_update);
            $update_success = $stmt_update->execute([
                ':new_stock' => $new_stock,
                ':product_id' => (int)$product_id
            ]);

            return $update_success;
        } catch (PDOException $e) {
            // エラーが発生した場合、呼び出し元のcatchブロックでトランザクションがロールバックされるように例外をスローする
            $error_message_log = "Database Error in decrease_stock: " . $e->getMessage();
            error_log($error_message_log);
            throw $e;
        }
    }
    public function get_top_selling_products($debug, $limit = 3)
    {
        $query = "
            SELECT
                p.product_id,
                p.product_name,
                SUM(oi.quantity) AS total_sold,
                (
                    SELECT pi.image_path 
                    FROM product_images pi 
                    WHERE pi.product_id = p.product_id 
                    AND pi.image_type = 'main' 
                    ORDER BY pi.display_order ASC, pi.image_id ASC 
                    LIMIT 1
                ) AS main_image_path
            FROM
                order_items oi
            JOIN
                product_info p ON oi.product_id = p.product_id
            WHERE
                oi.product_id IS NOT NULL
            GROUP BY
                p.product_id, p.product_name
            ORDER BY
                total_sold DESC
            LIMIT :limit
        ";

        $prep_arr = [
            ':limit' => (int)$limit
        ];

        $arr = [];
        $stmt = $this->execute_query($debug, $query, $prep_arr);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }
    public function get_ranked_products($debug, $limit = 5, $offset = 0)
    {
        $query = "
            SELECT
                p.product_id,
                p.product_name,
                p.product_price,
                SUM(oi.quantity) AS total_sold,
                (
                    SELECT pi.image_path 
                    FROM product_images pi 
                    WHERE pi.product_id = p.product_id 
                    ORDER BY pi.image_type = 'main' DESC, pi.display_order ASC, pi.image_id ASC 
                    LIMIT 1
                ) AS main_image_path,
                GROUP_CONCAT(DISTINCT t.tag_name ORDER BY t.tag_id SEPARATOR ', ') AS tags
            FROM
                order_items oi
            JOIN
                product_info p ON oi.product_id = p.product_id
            LEFT JOIN
                product_tags_relation ptr ON p.product_id = ptr.product_id
            LEFT JOIN
                tags t ON ptr.tag_id = t.tag_id
            WHERE
                oi.product_id IS NOT NULL
            GROUP BY
                p.product_id
            ORDER BY
                total_sold DESC
            LIMIT :limit OFFSET :offset
        ";

        $prep_arr = [
            ':limit' => (int)$limit,
            ':offset' => (int)$offset
        ];

        $stmt = $this->execute_query($debug, $query, $prep_arr);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }
    public function get_top_selling_products_by_tag($debug, $tag_name, $limit = 5)
    {
        $query = "
            SELECT
                p.product_id,
                p.product_name,
                p.product_price,
                SUM(oi.quantity) AS total_sold,
                (
                    SELECT pi.image_path 
                    FROM product_images pi 
                    WHERE pi.product_id = p.product_id 
                    AND pi.image_type = 'main' 
                    ORDER BY pi.display_order ASC, pi.image_id ASC 
                    LIMIT 1
                ) AS main_image_path,
                -- 商品に紐づく全てのタグを取得
                (
                    SELECT GROUP_CONCAT(t_all.tag_name ORDER BY t_all.tag_id SEPARATOR ', ')
                    FROM product_tags_relation ptr_all
                    JOIN tags t_all ON ptr_all.tag_id = t_all.tag_id
                    WHERE ptr_all.product_id = p.product_id
                ) AS tags
            FROM
                order_items oi
            JOIN
                product_info p ON oi.product_id = p.product_id
            -- 特定のタグを持つ商品を絞り込むためのJOIN
            JOIN
                product_tags_relation ptr_filter ON p.product_id = ptr_filter.product_id
            JOIN
                tags t_filter ON ptr_filter.tag_id = t_filter.tag_id
            WHERE
                oi.product_id IS NOT NULL
                AND t_filter.tag_name = :tag_name
            GROUP BY
                p.product_id
            ORDER BY
                total_sold DESC
            LIMIT :limit
        ";

        $prep_arr = [
            ':tag_name' => $tag_name,
            ':limit' => (int)$limit
        ];

        $stmt = $this->execute_query($debug, $query, $prep_arr);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }
    public function get_all_products_for_list($debug)
    {
        $query = "
            SELECT
                p.product_id,
                p.product_name,
                p.product_price,
                p.product_Contents,
                p.created_at,
                c.category_name,
                -- 販売数を計算（売れていない商品は0になる）
                COALESCE(SUM(oi.quantity), 0) AS total_sold,
                -- メイン画像を取得
                (
                    SELECT pi.image_path 
                    FROM product_images pi 
                    WHERE pi.product_id = p.product_id 
                    ORDER BY pi.image_type = 'main' DESC, pi.display_order ASC, pi.image_id ASC 
                    LIMIT 1
                ) AS main_image_path,
                -- 関連タグをカンマ区切りで取得
                GROUP_CONCAT(DISTINCT t.tag_name ORDER BY t.tag_id SEPARATOR ', ') AS tags
            FROM
                product_info p
            LEFT JOIN
                categories c ON p.product_category = c.category_id
            LEFT JOIN
                product_tags_relation ptr ON p.product_id = ptr.product_id
            LEFT JOIN
                tags t ON ptr.tag_id = t.tag_id
            LEFT JOIN
                order_items oi ON p.product_id = oi.product_id
            GROUP BY
                p.product_id
            ORDER BY
                p.product_id DESC
        ";

        $stmt = $this->execute_query($debug, $query, []);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }
    /**
     * 【新規追加】指定されたクライアントIDに紐づく商品のリストを取得する (プレビューのプルダウン用)
     * @param bool $debug デバッグモード
     * @param int $client_id クライアントID
     * @return array 商品の配列 (product_id, product_name)
     */
    public function get_products_by_client_id($debug, $client_id)
    {
        $query = "SELECT product_id, product_name FROM product_info WHERE client_id = :client_id ORDER BY product_id DESC";
        $prep_arr = [':client_id' => (int)$client_id];
        $stmt = $this->execute_query($debug, $query, $prep_arr);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    /**
     * 【新規追加】単一商品の全ての詳細情報を取得する (プレビュー表示用)
     * @param bool $debug デバッグモード
     * @param int $product_id 商品ID
     * @return array|false 商品データが見つかればその配列、なければfalse
     */
    public function get_full_product_details($debug, $product_id)
    {
        $query = "
            SELECT
                p.*,
                c.category_name,
                cu.company_name,
                GROUP_CONCAT(DISTINCT t.tag_name ORDER BY t.tag_id SEPARATOR ', ') AS tags
            FROM
                product_info p
            LEFT JOIN
                categories c ON p.product_category = c.category_id
            LEFT JOIN
                client_user_info cu ON p.client_id = cu.client_id
            LEFT JOIN
                product_tags_relation ptr ON p.product_id = ptr.product_id
            LEFT JOIN
                tags t ON ptr.tag_id = t.tag_id
            WHERE
                p.product_id = :product_id
            GROUP BY
                p.product_id
        ";
        $prep_arr = [':product_id' => (int)$product_id];
        $this->select_query($debug, $query, $prep_arr);
        $product_details = $this->fetch_assoc();

        if ($product_details) {
            // 画像情報を別途取得して追加
            $images_db = new cproduct_images();
            $product_details['images'] = $images_db->get_images_by_product_id($debug, $product_id);
        }

        return $product_details;
    }
    public function get_top_selling_products_by_category($debug, $category_id, $limit = 5)
    {
        $query = "
            SELECT
                p.product_id,
                p.product_name,
                p.product_price,
                COALESCE(SUM(oi.quantity), 0) AS total_sold,
                (
                    SELECT pi.image_path 
                    FROM product_images pi 
                    WHERE pi.product_id = p.product_id 
                    ORDER BY pi.image_type = 'main' DESC, pi.display_order ASC, pi.image_id ASC 
                    LIMIT 1
                ) AS main_image_path
            FROM
                product_info p
            LEFT JOIN
                order_items oi ON p.product_id = oi.product_id
            WHERE
                p.product_category = :category_id
            GROUP BY
                p.product_id
            ORDER BY
                total_sold DESC, p.product_id DESC
            LIMIT :limit
        ";

        $prep_arr = [
            ':category_id' => (int)$category_id,
            ':limit' => (int)$limit
        ];

        $stmt = $this->execute_query($debug, $query, $prep_arr);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }
    public function update_product($debug, $product_id, $product_name, $product_price, $product_category, $product_description, $product_discription, $product_How, $product_Contents, $product_stock, $product_degree)
    {
        $query = "
            UPDATE product_info SET
                product_name = :product_name,
                product_price = :product_price,
                product_category = :product_category,
                product_description = :product_description,
                product_discription = :product_discription,
                product_How = :product_How,
                product_Contents = :product_Contents,
                product_stock = :product_stock,
                product_degree = :product_degree
            WHERE product_id = :product_id
        ";
        $prep_arr = [
            ':product_name' => $product_name,
            ':product_price' => (float)$product_price,
            ':product_category' => $product_category,
            ':product_description' => $product_description,
            ':product_discription' => $product_discription,
            ':product_How' => $product_How,
            ':product_Contents' => $product_Contents,
            ':product_stock' => (int)$product_stock,
            ':product_degree' => (float)$product_degree,
            ':product_id' => (int)$product_id
        ];
        return $this->execute_query($debug, $query, $prep_arr);
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}

class cproduct_images extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert_image($debug, $product_id, $image_path, $image_type, $display_order)
    {
        $query = "INSERT INTO product_images (product_id, image_path, image_type, display_order) VALUES (:product_id, :image_path, :image_type, :display_order)";
        $prep_arr = array(
            ':product_id' => (int)$product_id,
            ':image_path' => $image_path,
            ':image_type' => $image_type,
            ':display_order' => (int)$display_order
        );
        $result = $this->execute_query($debug, $query, $prep_arr);
        if ($result) {
            return $this->last_insert_id();
        }
        return false;
    }

    public function get_images_by_product_id($debug, $product_id)
    {
        if (!cutil::is_number($product_id) || $product_id < 1) {
            return [];
        }
        $arr = [];
        $query = "SELECT * FROM product_images WHERE product_id = :product_id ORDER BY display_order ASC, image_id ASC LIMIT 4";
        $stmt = $this->execute_query($debug, $query, array(':product_id' => (int)$product_id));
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function delete_images_by_product_id($debug, $product_id)
    {
        if (!cutil::is_number($product_id) || $product_id < 1) {
            return false;
        }
        $query = "DELETE FROM product_images WHERE product_id = :product_id";
        $prep_arr = array(':product_id' => (int)$product_id);
        return $this->execute_query($debug, $query, $prep_arr);
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}

class ccategories extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_count($debug)
    {
        $query = "SELECT COUNT(*) AS total_count FROM categories WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit)
    {
        $arr = array();
        $query = "SELECT * FROM categories WHERE 1 ORDER BY category_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id)
    {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM categories WHERE category_id = :category_id";
        $prep_arr = array(':category_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}

class ctags_for_products extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_tags_with_category($debug)
    {
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

    /**
     * 【新規追加】すべてのタグをタグカテゴリごとにグループ化して取得するメソッド
     * @param bool $debug デバッグモード
     * @return array タグカテゴリごとにグループ化されたタグの配列
     */
    public function get_all_tags_grouped_by_category($debug)
    {
        $query = "
            SELECT
                tc.tag_category_id,
                tc.tag_category_name,
                t.tag_id,
                t.tag_name
            FROM
                tag_categories tc
            LEFT JOIN
                tags t ON tc.tag_category_id = t.tag_category_id
            ORDER BY
                tc.tag_category_id ASC, t.tag_id ASC
        ";
        $stmt = $this->execute_query($debug, $query);
        $grouped_tags = [];
        if ($stmt) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $category_id = $row['tag_category_id'];
                $category_name = $row['tag_category_name'];

                if (!isset($grouped_tags[$category_id])) {
                    $grouped_tags[$category_id] = [
                        'tag_category_id' => $category_id,
                        'tag_category_name' => $category_name,
                        'tags' => []
                    ];
                }
                // tag_idがnullの場合（カテゴリにタグがない場合）はスキップ
                if ($row['tag_id'] !== null) {
                    $grouped_tags[$category_id]['tags'][] = [
                        'tag_id' => $row['tag_id'],
                        'tag_name' => $row['tag_name']
                    ];
                }
            }
        }
        // 連想配列のキーを捨てて、0からの連番の配列にする
        return array_values($grouped_tags);
    }


    public function get_all_count($debug)
    {
        $query = "SELECT COUNT(*) AS total_count FROM tags WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit)
    {
        $arr = array();
        $query = "SELECT * FROM tags WHERE 1 ORDER BY tag_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id)
    {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM tags WHERE tag_id = :tag_id";
        $prep_arr = array(':tag_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}

class ctag_categories_for_products extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_tag_categories($debug)
    {
        $query = "SELECT * FROM tag_categories ORDER BY tag_category_id ASC";
        $stmt = $this->execute_query($debug, $query);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function get_all_count($debug)
    {
        $query = "SELECT COUNT(*) AS total_count FROM faq_categories WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit)
    {
        $arr = array();
        $query = "SELECT * FROM faq_categories WHERE 1 ORDER BY faq_category_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id)
    {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM faq_categories WHERE tag_category_id = :tag_category_id";
        $prep_arr = array(':tag_category_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }


    public function __destruct()
    {
        parent::__destruct();
    }
}

class cproduct_tags_relation extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert_product_tag_relation($debug, $product_id, $tag_id)
    {
        $query = "INSERT INTO product_tags_relation (product_id, tag_id) VALUES (:product_id, :tag_id)";
        $prep_arr = array(
            ':product_id' => (int)$product_id,
            ':tag_id' => (int)$tag_id
        );
        return $this->execute_query($debug, $query, $prep_arr);
    }

    public function get_tags_by_product_id($debug, $product_id)
    {
        if (!cutil::is_number($product_id) || $product_id < 1) {
            return [];
        }
        $arr = array();
        $query = "SELECT ptr.tag_id, t.tag_name, tc.tag_category_name
                  FROM product_tags_relation ptr
                  JOIN tags t ON ptr.tag_id = t.tag_id
                  JOIN tag_categories tc ON t.tag_category_id = tc.tag_category_id
                  WHERE ptr.product_id = :product_id
                  ORDER BY tc.tag_category_id ASC, t.tag_id ASC";
        $stmt = $this->execute_query($debug, $query, array(':product_id' => (int)$product_id));
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function delete_product_tags_by_product_id($debug, $product_id)
    {
        if (!cutil::is_number($product_id) || $product_id < 1) {
            return false;
        }
        $query = "DELETE FROM product_tags_relation WHERE product_id = :product_id";
        $prep_arr = array(':product_id' => (int)$product_id);
        return $this->execute_query($debug, $query, $prep_arr);
    }
    public function delete_tags_by_product_id($debug, $product_id)
    {
        if (!cutil::is_number($product_id) || $product_id < 1) {
            return false;
        }
        $query = "DELETE FROM product_tags_relation WHERE product_id = :product_id";
        return $this->execute_query($debug, $query, [':product_id' => (int)$product_id]);
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}

class cposts extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_count($debug)
    {
        $query = "SELECT COUNT(*) AS total_count FROM posts WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit)
    {
        $arr = array();
        $query = "SELECT * FROM posts WHERE 1 ORDER BY post_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id)
    {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM posts WHERE post_id = :post_id";
        $prep_arr = array(':post_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function insert_post($debug, $user_id, $post_title, $post_content)
    {
        $query = "INSERT INTO posts (user_id, post_title, post_content) VALUES (:user_id, :post_title, :post_content)";
        $prep_arr = array(
            ':user_id' => (int)$user_id,
            ':post_title' => $post_title,
            ':post_content' => $post_content
        );
        $result = $this->execute_query($debug, $query, $prep_arr);
        if ($result) {
            return $this->last_insert_id();
        }
        return false;
    }

    public function get_posts_by_user_id($debug, $user_id)
    {
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

    public function get_posts_by_ids($debug, $post_ids)
    {
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

    public function delete_post($debug, $post_id, $user_id)
    {
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

    public function __destruct()
    {
        parent::__destruct();
    }
}

class cpost_images extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert_image($debug, $post_id, $image_path, $display_order)
    {
        $query = "INSERT INTO post_images (post_id, image_path, display_order) VALUES (:post_id, :image_path, :display_order)";
        $prep_arr = array(
            ':post_id' => (int)$post_id,
            ':image_path' => $image_path,
            ':display_order' => (int)$display_order
        );
        $result = $this->execute_query($debug, $query, $prep_arr);
        if ($result) {
            return $this->last_insert_id();
        }
        return false;
    }

    public function get_images_by_post_id($debug, $post_id)
    {
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

    public function delete_images_by_post_id($debug, $post_id)
    {
        if (!cutil::is_number($post_id) || $post_id < 1) {
            return false;
        }
        $query = "DELETE FROM post_images WHERE post_id = :post_id";
        $prep_arr = array(':post_id' => (int)$post_id);
        return $this->execute_query($debug, $query, $prep_arr);
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}

class ccontacts extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_count($debug)
    {
        $query = "SELECT COUNT(*) AS total_count FROM contacts WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit)
    {
        $arr = array();
        $query = "SELECT * FROM contacts WHERE 1 ORDER BY contact_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id)
    {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM contacts WHERE contact_id = :contact_id";
        $prep_arr = array(':contact_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}

class cfaqs extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_count($debug)
    {
        $query = "SELECT COUNT(*) AS total_count FROM faqs WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit)
    {
        $arr = array();
        $query = "SELECT * FROM faqs WHERE 1 ORDER BY faq_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id)
    {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM faqs WHERE faq_id = :faq_id";
        $prep_arr = array(':faq_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}

class cfaq_categories extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_count($debug)
    {
        $query = "SELECT COUNT(*) AS total_count FROM faq_categories WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit)
    {
        $arr = array();
        $query = "SELECT * FROM faq_categories WHERE 1 ORDER BY faq_category_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id)
    {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM faq_categories WHERE tag_category_id = :tag_category_id";
        $prep_arr = array(':tag_category_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }


    public function __destruct()
    {
        parent::__destruct();
    }
}

class cMypage extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_count($debug)
    {
        $query = "SELECT COUNT(*) AS total_count FROM Mypage WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit)
    {
        $arr = array();
        $query = "SELECT * FROM Mypage WHERE 1 ORDER BY profile_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id)
    {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM Mypage WHERE profile_id = :profile_id";
        $prep_arr = array(':profile_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}

class creport_info extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_count($debug)
    {
        $query = "SELECT COUNT(*) AS total_count FROM report_info WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit)
    {
        $arr = array();
        $query = "SELECT * FROM report_info WHERE 1 ORDER BY report_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id)
    {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM report_info WHERE report_id = :report_id";
        $prep_arr = array(':report_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}

class cgood extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert_good($debug, $user_id, $post_id)
    {
        $query = "INSERT INTO good (user_id, post_id) VALUES (:user_id, :post_id)";
        $prep_arr = array(
            ':user_id' => (int)$user_id,
            ':post_id' => (int)$post_id
        );
        return $this->execute_query($debug, $query, $prep_arr);
    }

    public function delete_good($debug, $user_id, $post_id)
    {
        $query = "DELETE FROM good WHERE user_id = :user_id AND post_id = :post_id";
        $prep_arr = array(
            ':user_id' => (int)$user_id,
            ':post_id' => (int)$post_id
        );
        return $this->execute_query($debug, $query, $prep_arr);
    }

    public function count_good_by_post_id($debug, $post_id)
    {
        if (!cutil::is_number($post_id) || $post_id < 1) {
            return 0;
        }
        $query = "SELECT COUNT(*) AS good_count FROM good WHERE post_id = :post_id";
        $this->select_query($debug, $query, array(':post_id' => (int)$post_id));
        $row = $this->fetch_assoc();
        return $row ? (int)$row['good_count'] : 0;
    }

    public function is_good_by_user($debug, $user_id, $post_id)
    {
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

    public function get_liked_post_ids_by_user_id($debug, $user_id)
    {
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

    public function delete_all_goods_by_post_id($debug, $post_id)
    {
        if (!cutil::is_number($post_id) || $post_id < 1) {
            return false;
        }
        $query = "DELETE FROM good WHERE post_id = :post_id";
        $prep_arr = array(':post_id' => (int)$post_id);
        return $this->execute_query($debug, $query, $prep_arr);
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}

class cheart extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert_heart($debug, $user_id, $post_id)
    {
        $query = "INSERT INTO heart (user_id, post_id) VALUES (:user_id, :post_id)";
        $prep_arr = array(
            ':user_id' => (int)$user_id,
            ':post_id' => (int)$post_id
        );
        return $this->execute_query($debug, $query, $prep_arr);
    }

    public function delete_heart($debug, $user_id, $post_id)
    {
        $query = "DELETE FROM heart WHERE user_id = :user_id AND post_id = :post_id";
        $prep_arr = array(
            ':user_id' => (int)$user_id,
            ':post_id' => (int)$post_id
        );
        return $this->execute_query($debug, $query, $prep_arr);
    }

    public function count_heart_by_post_id($debug, $post_id)
    {
        if (!cutil::is_number($post_id) || $post_id < 1) {
            return 0;
        }
        $query = "SELECT COUNT(*) AS heart_count FROM heart WHERE post_id = :post_id";
        $this->select_query($debug, $query, array(':post_id' => (int)$post_id));
        $row = $this->fetch_assoc();
        return $row ? (int)$row['heart_count'] : 0;
    }

    public function is_heart_by_user($debug, $user_id, $post_id)
    {
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

    public function get_hearted_post_ids_by_user_id($debug, $user_id)
    {
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

    public function delete_all_hearts_by_post_id($debug, $post_id)
    {
        if (!cutil::is_number($post_id) || $post_id < 1) {
            return false;
        }
        $query = "DELETE FROM heart WHERE post_id = :post_id";
        $prep_arr = array(':post_id' => (int)$post_id);
        return $this->execute_query($debug, $query, $prep_arr);
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}

class ccontacts_reply extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_count($debug)
    {
        $query = "SELECT COUNT(*) AS total_count FROM contacts_reply WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit)
    {
        $arr = array();
        $query = "SELECT * FROM contacts_reply WHERE 1 ORDER BY contact_reply_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id)
    {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM contacts_reply WHERE contact_reply_id = :contact_reply_id";
        $prep_arr = array(':contact_reply_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}

class cotumami_categories extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_categories($debug)
    {
        $query = "SELECT * FROM otumami_categories ORDER BY category_id ASC";
        $stmt = $this->execute_query($debug, $query);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function get_all_count($debug)
    {
        $query = "SELECT COUNT(*) AS total_count FROM otumami_categories WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit)
    {
        $arr = array();
        $query = "SELECT * FROM otumami_categories WHERE 1 ORDER BY category_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id)
    {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM otumami_categories WHERE category_id = :category_id";
        $prep_arr = array(':category_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}

class cotumami_tags extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_tags($debug)
    {
        $query = "SELECT * FROM otumami_tags ORDER BY tag_id ASC";
        $stmt = $this->execute_query($debug, $query);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function get_all_count($debug)
    {
        $query = "SELECT COUNT(*) AS total_count FROM otumami_tags WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit)
    {
        $arr = array();
        $query = "SELECT * FROM otumami_tags WHERE 1 ORDER BY tag_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id)
    {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM otumami_tags WHERE tag_id = :tag_id";
        $prep_arr = array(':tag_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}

class cotumami extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert_otumami($debug, $combi_category_id, $otumami_name, $otumami_price, $otumami_description, $otumami_discription, $otumami_stock)
    {
        $query = "INSERT INTO otumami (combi_category_id, otumami_name, otumami_price, otumami_description, otumami_discription, otumami_stock) VALUES (:combi_category_id, :otumami_name, :otumami_price, :otumami_description, :otumami_discription, :otumami_stock)";
        $prep_arr = array(
            ':combi_category_id' => (int)$combi_category_id,
            ':otumami_name' => $otumami_name,
            ':otumami_price' => (float)$otumami_price,
            ':otumami_description' => $otumami_description,
            ':otumami_discription' => $otumami_discription,
            ':otumami_stock' => (int)$otumami_stock
        );
        $result = $this->execute_query($debug, $query, $prep_arr);
        if ($result) {
            return $this->last_insert_id();
        }
        return false;
    }

    public function get_all_count($debug)
    {
        $query = "SELECT COUNT(*) AS total_count FROM otumami WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit)
    {
        $arr = array();
        $query = "SELECT * FROM otumami WHERE 1 ORDER BY otumami_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id)
    {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM otumami WHERE otumami_id = :otumami_id";
        $prep_arr = array(':otumami_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }
    public function get_otumami_list_by_ids($debug, $ids)
    {
        if (empty($ids) || !is_array($ids)) {
            return [];
        }

        // プレースホルダーを作成 (例: ?,?,?)
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $query = "
            SELECT
                o.otumami_id,
                o.otumami_name,
                o.otumami_price,
                o.created_at,
                (
                    SELECT oi.image_path 
                    FROM otumami_images oi 
                    WHERE oi.otumami_id = o.otumami_id 
                    ORDER BY oi.image_type = 'main' DESC, oi.display_order ASC, oi.image_id ASC 
                    LIMIT 1
                ) AS main_image_path,
                GROUP_CONCAT(DISTINCT ot.tag_name ORDER BY ot.tag_id SEPARATOR ', ') AS tags
            FROM
                otumami o
            LEFT JOIN
                otumami_otumami_tags otr ON o.otumami_id = otr.otumami_id
            LEFT JOIN
                otumami_tags ot ON otr.tag_id = ot.tag_id
            WHERE
                o.otumami_id IN ({$placeholders})
            GROUP BY
                o.otumami_id
        ";

        // array_valuesでキーをリセットし、PDOが正しくバインドできるようにする
        $stmt = $this->execute_query($debug, $query, array_values($ids));
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }
    public function decrease_stock($debug, $otumami_id, $quantity)
    {
        if (!cutil::is_number($otumami_id) || $otumami_id < 1 || !cutil::is_number($quantity) || $quantity < 1) {
            return false;
        }

        // このメソッドは、api/process_order.php のトランザクション内で呼び出されることを前提としています。
        try {
            // 1. 行をロックして現在の在庫数を取得
            $query_select = "SELECT otumami_stock FROM otumami WHERE otumami_id = :otumami_id FOR UPDATE";
            $stmt_select = $this->pdo->prepare($query_select);
            $stmt_select->execute([':otumami_id' => (int)$otumami_id]);
            $result = $stmt_select->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                error_log("decrease_stock error: Otumami with ID {$otumami_id} not found.");
                return false;
            }

            $current_stock = (int)$result['otumami_stock'];

            // 2. PHP側で在庫数を確認
            if ($current_stock < $quantity) {
                error_log("decrease_stock error: Insufficient stock for otumami ID {$otumami_id}. Required: {$quantity}, Available: {$current_stock}");
                return false;
            }

            // 3. 在庫数を更新
            $new_stock = $current_stock - (int)$quantity;
            $query_update = "UPDATE otumami SET otumami_stock = :new_stock WHERE otumami_id = :otumami_id";
            $stmt_update = $this->pdo->prepare($query_update);
            $update_success = $stmt_update->execute([
                ':new_stock' => $new_stock,
                ':otumami_id' => (int)$otumami_id
            ]);

            return $update_success;
        } catch (PDOException $e) {
            $error_message_log = "Database Error in otumami->decrease_stock: " . $e->getMessage();
            error_log($error_message_log);
            throw $e;
        }
    }
    public function get_top_selling_otumami_by_sake_category($debug, $sake_category_id, $limit = 5)
    {
        $query = "
            SELECT
                o.otumami_id,
                o.otumami_name,
                o.otumami_price,
                (
                    SELECT oi.image_path 
                    FROM otumami_images oi 
                    WHERE oi.otumami_id = o.otumami_id 
                    ORDER BY oi.image_type = 'main' DESC, oi.display_order ASC, oi.image_id ASC 
                    LIMIT 1
                ) AS main_image_path,
                COALESCE(SUM(order_items.quantity), 0) AS total_sold
            FROM
                otumami o

            LEFT JOIN
                order_items ON o.otumami_id = order_items.otumami_id
            WHERE
                o.combi_category_id = :category_id
            GROUP BY
                o.otumami_id
            ORDER BY
                total_sold DESC, o.otumami_id DESC
            LIMIT :limit
        ";

        $prep_arr = [
            ':category_id' => (int)$sake_category_id,
            ':limit' => (int)$limit
        ];

        $stmt = $this->execute_query($debug, $query, $prep_arr);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }
    public function get_top_selling_otumami($debug, $limit = 5)
    {
        $query = "
            SELECT
                o.otumami_id,
                o.otumami_name,
                o.otumami_price,
                (
                    SELECT oi.image_path 
                    FROM otumami_images oi 
                    WHERE oi.otumami_id = o.otumami_id 
                    ORDER BY oi.image_type = 'main' DESC, oi.display_order ASC, oi.image_id ASC 
                    LIMIT 1
                ) AS main_image_path,
                COALESCE(SUM(order_items.quantity), 0) AS total_sold
            FROM
                otumami o
            LEFT JOIN
                order_items ON o.otumami_id = order_items.otumami_id
            GROUP BY
                o.otumami_id
            ORDER BY
                total_sold DESC, o.otumami_id DESC
            LIMIT :limit
        ";

        $prep_arr = [':limit' => (int)$limit];

        $stmt = $this->execute_query($debug, $query, $prep_arr);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}

class cotumami_images extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert_image($debug, $otumami_id, $image_path, $image_type, $display_order)
    {
        $query = "INSERT INTO otumami_images (otumami_id, image_path, image_type, display_order) VALUES (:otumami_id, :image_path, :image_type, :display_order)";
        $prep_arr = array(
            ':otumami_id' => (int)$otumami_id,
            ':image_path' => $image_path,
            ':image_type' => $image_type,
            ':display_order' => (int)$display_order
        );
        $result = $this->execute_query($debug, $query, $prep_arr);
        if ($result) {
            return $this->last_insert_id();
        }
        return false;
    }

    public function get_images_by_otumami_id($debug, $otumami_id)
    {
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

    public function __destruct()
    {
        parent::__destruct();
    }
}

class cotumami_otumami_tags extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert_otumami_tag_relation($debug, $otumami_id, $tag_id)
    {
        $query = "INSERT INTO otumami_otumami_tags (otumami_id, tag_id) VALUES (:otumami_id, :tag_id)";
        $prep_arr = array(
            ':otumami_id' => (int)$otumami_id,
            ':tag_id' => (int)$tag_id
        );
        return $this->execute_query($debug, $query, $prep_arr);
    }

    public function get_all_count($debug)
    {
        $query = "SELECT COUNT(*) AS total_count FROM otumami_otumami_tags WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit)
    {
        $arr = array();
        $query = "SELECT * FROM otumami_otumami_tags WHERE 1 ORDER BY otumami_id ASC, tag_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_by_otumami_id($debug, $otumami_id)
    {
        if (!cutil::is_number($otumami_id) || $otumami_id < 1) {
            return false;
        }
        $arr = array();
        $query = "SELECT tag_id FROM otumami_otumami_tags WHERE otumami_id = :otumami_id ORDER BY tag_id ASC";
        $stmt = $this->execute_query($debug, $query, array(':otumami_id' => (int)$otumami_id));
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function get_by_tag_id($debug, $tag_id)
    {
        if (!cutil::is_number($tag_id) || $tag_id < 1) {
            return false;
        }
        $arr = array();
        $query = "SELECT otumami_id FROM otumami_otumami_tags WHERE tag_id = :tag_id ORDER BY otumami_id ASC";
        $stmt = $this->execute_query($debug, $query, array(':tag_id' => (int)$tag_id));
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}
class ccarts extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_or_create_cart_by_user_id($debug, $user_id)
    {
        if (!cutil::is_number($user_id) || $user_id < 1) {
            return false;
        }
        $query = "SELECT cart_id FROM carts WHERE user_id = :user_id";
        $this->select_query($debug, $query, [':user_id' => (int)$user_id]);
        $cart = $this->fetch_assoc();

        if ($cart) {
            return $cart['cart_id'];
        } else {
            $query = "INSERT INTO carts (user_id) VALUES (:user_id)";
            $result = $this->execute_query($debug, $query, [':user_id' => (int)$user_id]);
            if ($result) {
                return $this->last_insert_id();
            }
        }
        return false;
    }
    public function __destruct()
    {
        parent::__destruct();
    }
}

class ccart_items extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }
    public function add_or_update_item($debug, $cart_id, $item_id, $item_type, $quantity, $price)
    {
        if (!cutil::is_number($cart_id) || !cutil::is_number($item_id) || !cutil::is_number($quantity)) {
            return false;
        }

        // どのIDカラムを検索・更新するかを決定
        $id_column = ($item_type === 'otumami') ? 'otumami_id' : 'product_id';

        // 同じ商品が既にカートに存在するか確認
        $query = "SELECT cart_item_id, cart_quantity FROM cart_items WHERE cart_id = :cart_id AND {$id_column} = :item_id";
        $this->select_query($debug, $query, [':cart_id' => (int)$cart_id, ':item_id' => (int)$item_id]);
        $existing_item = $this->fetch_assoc();

        if ($existing_item) {
            // 存在する場合：数量を更新
            $new_quantity = $existing_item['cart_quantity'] + $quantity;
            $query = "UPDATE cart_items SET cart_quantity = :quantity WHERE cart_item_id = :cart_item_id";
            return $this->execute_query($debug, $query, [
                ':quantity' => $new_quantity,
                ':cart_item_id' => $existing_item['cart_item_id']
            ]);
        } else {
            // 存在しない場合：新規に挿入
            $product_id_val = ($item_type === 'product') ? (int)$item_id : null;
            $otumami_id_val = ($item_type === 'otumami') ? (int)$item_id : null;

            $query = "INSERT INTO cart_items (cart_id, product_id, otumami_id, cart_quantity, cart_price_at_add) 
                      VALUES (:cart_id, :product_id, :otumami_id, :quantity, :price)";
            return $this->execute_query($debug, $query, [
                ':cart_id' => (int)$cart_id,
                ':product_id' => $product_id_val,
                ':otumami_id' => $otumami_id_val,
                ':quantity' => (int)$quantity,
                ':price' => (float)$price
            ]);
        }
    }

    /**
     * ★★★【修正】★★★
     * カート内の商品（お酒・おつまみ両方）の情報を取得する
     * @param bool $debug デバッグモード
     * @param int $cart_id カートID
     * @return array カート内の商品情報の配列
     */
    public function get_items_by_cart_id($debug, $cart_id)
    {
        if (!cutil::is_number($cart_id) || $cart_id < 1) {
            return [];
        }
        $arr = [];
        // お酒とおつまみの情報をCOALESCEで結合して取得
        $query = "
            SELECT
                ci.cart_item_id,
                ci.cart_id,
                ci.product_id,
                ci.otumami_id,
                ci.cart_quantity,
                ci.cart_price_at_add,
                COALESCE(p.product_name, o.otumami_name) AS product_name, -- 統一された名前
                COALESCE(p.product_Contents, '') AS product_Contents, -- お酒にしかない情報は空文字を返す
                COALESCE(
                    (SELECT image_path FROM product_images WHERE product_id = ci.product_id ORDER BY display_order ASC, image_id ASC LIMIT 1),
                    (SELECT image_path FROM otumami_images WHERE otumami_id = ci.otumami_id ORDER BY display_order ASC, image_id ASC LIMIT 1)
                ) AS image_path
            FROM
                cart_items ci
            LEFT JOIN
                product_info p ON ci.product_id = p.product_id
            LEFT JOIN
                otumami o ON ci.otumami_id = o.otumami_id
            WHERE
                ci.cart_id = :cart_id
            ORDER BY
                ci.cart_added_at DESC
        ";
        $this->select_query($debug, $query, [':cart_id' => (int)$cart_id]);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    /**
     * ★★★【新規追加】★★★
     * カート内の特定の商品の数量を更新する
     */
    public function update_item_quantity($debug, $cart_item_id, $quantity)
    {
        if (!cutil::is_number($cart_item_id) || $cart_item_id < 1 || !cutil::is_number($quantity) || $quantity < 1) {
            return false;
        }
        $query = "UPDATE cart_items SET cart_quantity = :quantity WHERE cart_item_id = :cart_item_id";
        $prep_arr = [
            ':quantity' => (int)$quantity,
            ':cart_item_id' => (int)$cart_item_id
        ];
        return $this->execute_query($debug, $query, $prep_arr);
    }

    /**
     * ★★★【新規追加】★★★
     * カート内の特定の商品を削除する
     */
    public function remove_item($debug, $cart_item_id)
    {
        if (!cutil::is_number($cart_item_id) || $cart_item_id < 1) {
            return false;
        }
        $query = "DELETE FROM cart_items WHERE cart_item_id = :cart_item_id";
        $prep_arr = [':cart_item_id' => (int)$cart_item_id];
        return $this->execute_query($debug, $query, $prep_arr);
    }
    public function clear_items_by_cart_id($debug, $cart_id)
    {
        if (!cutil::is_number($cart_id) || $cart_id < 1) {
            return false;
        }
        $query = "DELETE FROM cart_items WHERE cart_id = :cart_id";
        $prep_arr = [':cart_id' => (int)$cart_id];
        return $this->execute_query($debug, $query, $prep_arr);
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}

class corders extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_count($debug)
    {
        $query = "SELECT COUNT(*) AS total_count FROM orders WHERE 1";
        $prep_arr = array();
        $this->select_query($debug, $query, $prep_arr);
        if ($row = $this->fetch_assoc()) {
            return $row['total_count'];
        }
        return 0;
    }

    public function get_all($debug, $from, $limit)
    {
        $arr = array();
        $query = "SELECT * FROM orders WHERE 1 ORDER BY order_id ASC LIMIT :from, :limit";
        $prep_arr = array(':from' => (int)$from, ':limit' => (int)$limit);
        $this->select_query($debug, $query, $prep_arr);
        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_tgt($debug, $id)
    {
        if (!cutil::is_number($id) || $id < 1) {
            return false;
        }
        $query = "SELECT * FROM orders WHERE order_id = :order_id";
        $prep_arr = array(':order_id' => (int)$id);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function get_orders_by_user_id($debug, $user_id)
    {
        if (!cutil::is_number($user_id) || $user_id < 1) {
            return [];
        }

        $arr = [];
        // 注文を新しい順に取得
        $query = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY order_date DESC";
        $prep_arr = [':user_id' => (int)$user_id];

        $this->select_query($debug, $query, $prep_arr);

        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    /**
     * ★★★【新規追加】★★★
     * 新しい注文をデータベースに作成する
     * @param bool $debug デバッグモード
     * @param int $user_id ユーザーID
     * @param float $total_amount 合計金額
     * @param string $shipping_address 配送先住所
     * @return int|false 作成された注文のID、失敗した場合はfalse
     */
    public function create_order($debug, $user_id, $total_amount, $shipping_address, $delivery_date, $delivery_time)
    {
        $query = "
            INSERT INTO orders (user_id, total_amount, shipping_address, order_status, delivery_date, delivery_time) 
            VALUES (:user_id, :total_amount, :shipping_address, 'pending', :delivery_date, :delivery_time)
        ";
        
        // delivery_timeが'none'の場合はnullとしてDBに保存
        $time_to_save = ($delivery_time === 'none' || empty($delivery_time)) ? null : $delivery_time;

        $prep_arr = [
            ':user_id' => (int)$user_id,
            ':total_amount' => (float)$total_amount,
            ':shipping_address' => $shipping_address,
            ':delivery_date' => !empty($delivery_date) ? $delivery_date : null,
            ':delivery_time' => $time_to_save
        ];

        $result = $this->execute_query($debug, $query, $prep_arr);
        if ($result) {
            return $this->last_insert_id();
        }
        return false;
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}

class corder_items extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    // ... (get_all_count, get_all, get_tgt, get_items_by_order_id は変更なし) ...

    /**
     * ★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★
     * ★★★ ここを修正しました (商品を1件ずつ登録する方式に変更) ★★★
     * ★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★
     * 注文に紐づく商品をまとめて登録する
     * @param bool $debug デバッグモード
     * @param int $order_id 注文ID
     * @param array $items 登録する商品の配列 (カートアイテム)
     * @return bool 成功した場合はtrue, 失敗した場合はfalse
     */
    public function add_items_to_order($debug, $order_id, $items)
    {
        if (empty($items)) {
            return false;
        }

        // 1件ずつ商品を登録する（デバッグのため）
        foreach ($items as $item) {
            $query = "
                INSERT INTO order_items (order_id, product_id, otumami_id, quantity, price_at_purchase) 
                VALUES (:order_id, :product_id, :otumami_id, :quantity, :price_at_purchase)
            ";

            $product_id = isset($item['product_id']) ? (int)$item['product_id'] : null;
            $otumami_id = isset($item['otumami_id']) ? (int)$item['otumami_id'] : null; // おつまみ商品にも対応できるよう準備

            // product_id と otumami_id のどちらかが必須
            if ($product_id === null && $otumami_id === null) {
                error_log("add_items_to_order: product_idとotumami_idが両方nullです。 Item: " . print_r($item, true));
                return false;
            }

            $prep_arr = [
                ':order_id'           => (int)$order_id,
                ':product_id'         => $product_id,
                ':otumami_id'         => $otumami_id,
                ':quantity'           => (int)$item['cart_quantity'],
                ':price_at_purchase'  => (float)$item['cart_price_at_add']
            ];

            // 1件でも失敗したら、全体を失敗としてトランザクションがロールバックされる
            $result = $this->execute_query($debug, $query, $prep_arr);
            if (!$result) {
                // execute_query内で既に詳細なエラーログは出力されているはず
                error_log("Failed to insert order_item. Order ID: {$order_id}, Details: " . print_r($item, true));
                return false;
            }
        }

        // すべてのループが成功した場合
        return true;
    }

    public function get_items_by_order_id($debug, $order_id)
    {
        if (!cutil::is_number($order_id) || $order_id < 1) {
            return [];
        }

        $arr = [];
        $query = "
            SELECT
                oi.order_item_id,
                oi.order_id,
                oi.product_id,
                oi.otumami_id,
                oi.quantity,
                oi.price_at_purchase,
                -- 商品かおつまみかによって名前を切り替える
                COALESCE(p.product_name, o.otumami_name) AS item_name,
                -- 商品かおつまみかを示すタイプを追加
                CASE
                    WHEN oi.product_id IS NOT NULL THEN 'product'
                    WHEN oi.otumami_id IS NOT NULL THEN 'otumami'
                    ELSE 'unknown'
                END AS item_type,
                -- 商品かおつまみかによって画像パスを切り替える
                COALESCE(
                    (SELECT image_path FROM product_images WHERE product_id = oi.product_id ORDER BY display_order ASC, image_id ASC LIMIT 1),
                    (SELECT image_path FROM otumami_images WHERE otumami_id = oi.otumami_id ORDER BY display_order ASC, image_id ASC LIMIT 1)
                ) AS image_path
            FROM
                order_items oi
            LEFT JOIN
                product_info p ON oi.product_id = p.product_id
            LEFT JOIN
                otumami o ON oi.otumami_id = o.otumami_id
            WHERE
                oi.order_id = :order_id
            ORDER BY
                oi.order_item_id ASC
        ";

        $prep_arr = [':order_id' => (int)$order_id];

        $this->select_query($debug, $query, $prep_arr);

        while ($row = $this->fetch_assoc()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_total_sold_count_by_product_id($debug, $product_id)
    {
        if (!cutil::is_number($product_id) || $product_id < 1) {
            return 0;
        }
        $query = "SELECT COALESCE(SUM(quantity), 0) AS total_sold_count FROM order_items WHERE product_id = :product_id";
        $prep_arr = array(':product_id' => (int)$product_id);
        $this->select_query($debug, $query, $prep_arr);
        $row = $this->fetch_assoc();
        return $row ? (int)$row['total_sold_count'] : 0;
    }

    public function get_frequently_bought_with_products($debug, $otumami_id, $limit = 5)
    {
        $query = "
            SELECT
                p.product_id,
                p.product_name,
                p.product_price,
                (
                    SELECT pi.image_path 
                    FROM product_images pi 
                    WHERE pi.product_id = p.product_id 
                    ORDER BY pi.image_type = 'main' DESC, pi.display_order ASC, pi.image_id ASC 
                    LIMIT 1
                ) AS main_image_path,
                COUNT(p.product_id) AS purchase_count
            FROM
                order_items oi1
            JOIN
                order_items oi2 ON oi1.order_id = oi2.order_id AND oi1.order_item_id != oi2.order_item_id
            JOIN
                product_info p ON oi2.product_id = p.product_id
            WHERE
                oi1.otumami_id = :otumami_id
            GROUP BY
                p.product_id
            ORDER BY
                purchase_count DESC, p.product_id DESC
            LIMIT :limit
        ";

        $prep_arr = [
            ':otumami_id' => (int)$otumami_id,
            ':limit' => (int)$limit
        ];

        $stmt = $this->execute_query($debug, $query, $prep_arr);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }
    public function get_frequently_bought_with_otumami($debug, $product_id, $limit = 5)
    {
        $query = "
            SELECT
                o.otumami_id,
                o.otumami_name,
                o.otumami_price,
                (
                    SELECT oi.image_path 
                    FROM otumami_images oi 
                    WHERE oi.otumami_id = o.otumami_id 
                    ORDER BY oi.image_type = 'main' DESC, oi.display_order ASC, oi.image_id ASC 
                    LIMIT 1
                ) AS main_image_path,
                COUNT(o.otumami_id) AS purchase_count
            FROM
                order_items oi1
            JOIN
                order_items oi2 ON oi1.order_id = oi2.order_id AND oi1.order_item_id != oi2.order_item_id
            JOIN
                otumami o ON oi2.otumami_id = o.otumami_id
            WHERE
                oi1.product_id = :product_id
            GROUP BY
                o.otumami_id
            ORDER BY
                purchase_count DESC, o.otumami_id DESC
            LIMIT :limit
        ";

        $prep_arr = [
            ':product_id' => (int)$product_id,
            ':limit' => (int)$limit
        ];

        $stmt = $this->execute_query($debug, $query, $prep_arr);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}

class cadmin_user_info extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert_admin_user($debug, $admin_user_name, $admin_user_pass_hashed)
    {
        $query = "INSERT INTO admin_user_info (admin_user_name, admin_user_pass) VALUES (:admin_user_name, :admin_user_pass)";
        $prep_arr = array(
            ':admin_user_name' => $admin_user_name,
            ':admin_user_pass' => $admin_user_pass_hashed
        );
        $result = $this->execute_query($debug, $query, $prep_arr);
        if ($result) {
            return $this->last_insert_id();
        }
        return false;
    }

    public function get_admin_user_by_name($debug, $admin_user_name)
    {
        $query = "SELECT admin_user_id, admin_user_name, admin_user_pass FROM admin_user_info WHERE admin_user_name = :admin_user_name";
        $prep_arr = array(':admin_user_name' => (string)$admin_user_name);
        $this->select_query($debug, $query, $prep_arr);
        return $this->fetch_assoc();
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}

/**
 * 【新規追加】商品詳細ページの訪問数を管理するクラス
 */
class cproduct_views extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 商品詳細ページへの訪問を記録する
     * @param bool $debug デバッグモード
     * @param int $product_id 訪問された商品ID
     * @return int|false 挿入された行のID、または失敗した場合はfalse
     */
    public function insert_product_view($debug, $product_id)
    {
        if (!cutil::is_number($product_id) || $product_id < 1) {
            error_log("Invalid product_id for insert_product_view: " . $product_id);
            return false;
        }
        $query = "INSERT INTO product_views (product_id) VALUES (:product_id)";
        $prep_arr = array(':product_id' => (int)$product_id);
        $result = $this->execute_query($debug, $query, $prep_arr);
        if ($result) {
            return $this->last_insert_id();
        }
        return false;
    }

    /**
     * 特定商品の総訪問数を取得する
     * @param bool $debug デバッグモード
     * @param int $product_id 商品ID
     * @return int 総訪問数
     */
    public function get_product_view_count($debug, $product_id)
    {
        if (!cutil::is_number($product_id) || $product_id < 1) {
            return 0;
        }
        $query = "SELECT COUNT(*) AS total_view_count FROM product_views WHERE product_id = :product_id";
        $prep_arr = array(':product_id' => (int)$product_id);
        $this->select_query($debug, $query, $prep_arr);
        $row = $this->fetch_assoc();
        return $row ? (int)$row['total_view_count'] : 0;
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}
class cproduct_favorites extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * ユーザーが商品をすでにお気に入り登録しているか確認する
     * @param bool $debug デバッグモード
     * @param int $user_id ユーザーID
     * @param int $product_id 商品ID
     * @return bool お気に入り済みの場合はtrue, そうでなければfalse
     */
    public function is_favorited($debug, $user_id, $product_id)
    {
        if (!cutil::is_number($user_id) || $user_id < 1 || !cutil::is_number($product_id) || $product_id < 1) {
            return false;
        }
        $query = "SELECT COUNT(*) AS count_result FROM product_favorites WHERE user_id = :user_id AND product_id = :product_id";
        $this->select_query($debug, $query, [':user_id' => (int)$user_id, ':product_id' => (int)$product_id]);
        $row = $this->fetch_assoc();
        return $row && $row['count_result'] > 0;
    }

    /**
     * お気に入りに追加する
     * @param bool $debug デバッグモード
     * @param int $user_id ユーザーID
     * @param int $product_id 商品ID
     * @return bool 成功した場合はtrue, 失敗した場合はfalse
     */
    public function add_favorite($debug, $user_id, $product_id)
    {
        $query = "INSERT INTO product_favorites (user_id, product_id) VALUES (:user_id, :product_id)";
        return $this->execute_query($debug, $query, [':user_id' => (int)$user_id, ':product_id' => (int)$product_id]);
    }

    /**
     * お気に入りから削除する
     * @param bool $debug デバッグモード
     * @param int $user_id ユーザーID
     * @param int $product_id 商品ID
     * @return bool 成功した場合はtrue, 失敗した場合はfalse
     */
    public function remove_favorite($debug, $user_id, $product_id)
    {
        $query = "DELETE FROM product_favorites WHERE user_id = :user_id AND product_id = :product_id";
        return $this->execute_query($debug, $query, [':user_id' => (int)$user_id, ':product_id' => (int)$product_id]);
    }

    /**
     * ユーザーのお気に入り商品IDリストを取得する
     * @param bool $debug デバッグモード
     * @param int $user_id ユーザーID
     * @return array お気に入り商品IDの配列
     */
    public function get_favorite_products_by_user_id($debug, $user_id)
    {
        if (!cutil::is_number($user_id) || $user_id < 1) {
            return [];
        }
        $query = "
            SELECT
                p.product_id,
                p.product_name,
                p.product_price,
                pf.created_at AS favorited_at,
                (
                    SELECT pi.image_path 
                    FROM product_images pi 
                    WHERE pi.product_id = p.product_id 
                    ORDER BY pi.image_type = 'main' DESC, pi.display_order ASC, pi.image_id ASC 
                    LIMIT 1
                ) AS main_image_path,
                GROUP_CONCAT(DISTINCT t.tag_name ORDER BY t.tag_id SEPARATOR ', ') AS tags
            FROM
                product_favorites AS pf
            JOIN
                product_info AS p ON pf.product_id = p.product_id
            LEFT JOIN
                product_tags_relation AS ptr ON p.product_id = ptr.product_id
            LEFT JOIN
                tags AS t ON ptr.tag_id = t.tag_id
            WHERE
                pf.user_id = :user_id
            GROUP BY
                p.product_id, pf.created_at
            ORDER BY
                pf.created_at DESC
        ";
        $stmt = $this->execute_query($debug, $query, [':user_id' => (int)$user_id]);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}

class cotumami_favorites extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    public function is_favorited($debug, $user_id, $otumami_id)
    {
        if (!cutil::is_number($user_id) || !cutil::is_number($otumami_id)) return false;
        $query = "SELECT COUNT(*) AS count_result FROM otumami_favorites WHERE user_id = :user_id AND otumami_id = :otumami_id";
        $this->select_query($debug, $query, [':user_id' => (int)$user_id, ':otumami_id' => (int)$otumami_id]);
        $row = $this->fetch_assoc();
        return $row && $row['count_result'] > 0;
    }

    public function add_favorite($debug, $user_id, $otumami_id)
    {
        $query = "INSERT INTO otumami_favorites (user_id, otumami_id) VALUES (:user_id, :otumami_id)";
        return $this->execute_query($debug, $query, [':user_id' => (int)$user_id, ':otumami_id' => (int)$otumami_id]);
    }

    public function remove_favorite($debug, $user_id, $otumami_id)
    {
        $query = "DELETE FROM otumami_favorites WHERE user_id = :user_id AND otumami_id = :otumami_id";
        return $this->execute_query($debug, $query, [':user_id' => (int)$user_id, ':otumami_id' => (int)$otumami_id]);
    }
    public function get_favorite_otumami_by_user_id($debug, $user_id)
    {
        if (!cutil::is_number($user_id) || $user_id < 1) {
            return [];
        }
        $query = "
            SELECT
                o.otumami_id,
                o.otumami_name,
                o.otumami_price,
                ofav.created_at AS favorited_at,
                (
                    SELECT oi.image_path 
                    FROM otumami_images oi 
                    WHERE oi.otumami_id = o.otumami_id 
                    ORDER BY oi.image_type = 'main' DESC, oi.display_order ASC, oi.image_id ASC 
                    LIMIT 1
                ) AS main_image_path,
                GROUP_CONCAT(DISTINCT ot.tag_name ORDER BY ot.tag_id SEPARATOR ', ') AS tags
            FROM
                otumami_favorites AS ofav
            JOIN
                otumami AS o ON ofav.otumami_id = o.otumami_id
            LEFT JOIN
                otumami_otumami_tags AS otr ON o.otumami_id = otr.otumami_id
            LEFT JOIN
                otumami_tags AS ot ON otr.tag_id = ot.tag_id
            WHERE
                ofav.user_id = :user_id
            GROUP BY
                o.otumami_id, ofav.created_at
            ORDER BY
                ofav.created_at DESC
        ";
        $stmt = $this->execute_query($debug, $query, [':user_id' => (int)$user_id]);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}
class canalytics extends crecord
{
    /**
     * サイト全体の統計情報を取得する
     * @param object $db データベース接続オブジェクト
     * @param string $date_from 集計開始日時
     * @param string $date_to 集計終了日時
     * @return array 統計情報
     */
    public function get_site_stats($db, $date_from, $date_to)
    {
        $stats = ['total_views' => 0, 'unique_users' => 0];
        try {
            // 総アクセス数
            $sql = "SELECT COUNT(log_id) FROM access_logs WHERE access_datetime BETWEEN ? AND ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$date_from, $date_to]);
            $stats['total_views'] = $stmt->fetchColumn();

            // ユニークユーザー数 (セッションIDでカウント)
            $sql = "SELECT COUNT(DISTINCT session_id) FROM access_logs WHERE access_datetime BETWEEN ? AND ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$date_from, $date_to]);
            $stats['unique_users'] = $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Analytics Error (get_site_stats): " . $e->getMessage());
            return $stats;
        }
        return $stats;
    }

    /**
     * 売上ランキングを取得する
     * @param object $db データベース接続オブジェクト
     * @param string $date_from 集計開始日時
     * @param string $date_to 集計終了日時
     * @param int $limit 取得件数
     * @return array ランキングデータ
     */
    public function get_sales_ranking($db, $date_from, $date_to, $limit)
    {
        $ranking = [];
        try {
            // ★【修正】並び順を売上金額(total_sales)から販売個数(total_quantity)に変更しました。
            $sql = "
                SELECT
                    p.product_name,
                    SUM(oi.quantity) AS total_quantity,
                    SUM(oi.quantity * oi.price_at_purchase) AS total_sales
                FROM order_items oi
                JOIN orders o ON oi.order_id = o.order_id
                JOIN product_info p ON oi.product_id = p.product_id
                WHERE o.order_date BETWEEN ? AND ? AND oi.product_id IS NOT NULL
                GROUP BY p.product_id, p.product_name
                ORDER BY total_quantity DESC
                LIMIT ?
            ";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(1, $date_from, PDO::PARAM_STR);
            $stmt->bindValue(2, $date_to, PDO::PARAM_STR);
            $stmt->bindValue(3, (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            $ranking = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Analytics Error (get_sales_ranking): " . $e->getMessage());
            return $ranking;
        }
        return $ranking;
    }

    /**
     * 人気カテゴリランキングを取得する
     * @param object $db データベース接続オブジェクト
     * @param string $date_from 集計開始日時
     * @param string $date_to 集計終了日時
     * @param int $limit 取得件数
     * @return array ランキングデータ
     */
    public function get_category_view_ranking($db, $date_from, $date_to, $limit)
    {
        $ranking = [];
        try {
            $sql = "
                SELECT
                    c.category_name,
                    COUNT(pv.view_id) AS view_count
                FROM product_views pv
                JOIN product_info p ON pv.product_id = p.product_id
                JOIN categories c ON p.product_category = c.category_id
                WHERE pv.view_timestamp BETWEEN ? AND ?
                GROUP BY c.category_id, c.category_name
                ORDER BY view_count DESC
                LIMIT ?
            ";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(1, $date_from, PDO::PARAM_STR);
            $stmt->bindValue(2, $date_to, PDO::PARAM_STR);
            $stmt->bindValue(3, (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            $ranking = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Analytics Error (get_category_view_ranking): " . $e->getMessage());
            return $ranking;
        }
        return $ranking;
    }

    /**
     * 人気タグランキングを取得する
     * @param object $db データベース接続オブジェクト
     * @param string $date_from 集計開始日時
     * @param string $date_to 集計終了日時
     * @param int $limit 取得件数
     * @return array ランキングデータ
     */
    public function get_tag_view_ranking($db, $date_from, $date_to, $limit)
    {
        $ranking = [];
        try {
            $sql = "
                SELECT
                    t.tag_name,
                    COUNT(pv.view_id) AS view_count
                FROM product_views pv
                JOIN product_tags_relation ptr ON pv.product_id = ptr.product_id
                JOIN tags t ON ptr.tag_id = t.tag_id
                WHERE pv.view_timestamp BETWEEN ? AND ?
                GROUP BY t.tag_id, t.tag_name
                ORDER BY view_count DESC
                LIMIT ?
            ";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(1, $date_from, PDO::PARAM_STR);
            $stmt->bindValue(2, $date_to, PDO::PARAM_STR);
            $stmt->bindValue(3, (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            $ranking = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Analytics Error (get_tag_view_ranking): " . $e->getMessage());
            return $ranking;
        }
        return $ranking;
    }
}
class caccess_logs extends crecord
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * access_logs テーブルに新しい記録を追加する
     * @param bool $debug デバッグモード
     * @param string $session_id セッションID
     * @param string $ip_address IPアドレス
     * @param string $page_url アクセスされたURL
     * @return bool 成功した場合はtrue, 失敗した場合はfalse
     */
    public function insert_log($debug, $session_id, $ip_address, $page_url)
    {
        $query = "INSERT INTO access_logs (session_id, ip_address, page_url) VALUES (:session_id, :ip_address, :page_url)";
        $prep_arr = [
            ':session_id' => $session_id,
            ':ip_address' => $ip_address,
            ':page_url' => $page_url
        ];
        return $this->execute_query($debug, $query, $prep_arr);
    }
}
