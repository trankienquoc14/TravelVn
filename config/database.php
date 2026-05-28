<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

class Database
{
    private $host = "sql100.infinityfree.com";
    private $db_name = "if0_41728842_travelvn";
    private $username = "if0_41728842";
    private $password = "ddEXbUD0grfbxWx";
    private $port = "3306";

    public function connect()
    {
        $conn = null;
        try {
            // DSN chuẩn cho InfinityFree
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            
            // Chỉ giữ lại các tùy chọn lỗi cơ bản, bỏ sạch SSL
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            );

            $conn = new PDO($dsn, $this->username, $this->password, $options);
            $conn->exec("SET time_zone = '+07:00'");

        } catch (PDOException $e) {
            // Bật dòng này để xem lỗi cụ thể nếu kết nối vẫn xịt
            die("Connection error: " . $e->getMessage());
        }
        return $conn;
    }
}
?>