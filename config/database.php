<?php
// 1. SET MÚI GIỜ CHO TOÀN BỘ CODE PHP (Các hàm như date() sẽ trả về giờ VN)
date_default_timezone_set('Asia/Ho_Chi_Minh');

class Database
{
    private $host = "sql100.infinityfree.com";
    private $db_name = "if0_41728842_travelvn";
    private $username = "if0_41728842";
    private $password = "ddEXbUD0grfbxWx"; // Điền lại password của bạn vào đây nhé
    private $port = "3306";

    public function connect()
    {
        $conn = null;

        try {
            // Lấy đường dẫn tuyệt đối đến file ca.pem nằm cùng thư mục config
            $ca_cert = __DIR__ . '/ca.pem';

            // Khai báo DSN có thêm thuộc tính port và BỔ SUNG charset=utf8mb4 (để lưu tiếng Việt không bị lỗi)
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";charset=utf8mb4";

            // Thêm mảng options để cấu hình SSL cho PDO
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_SSL_CA => $ca_cert,
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true
            );

            // Khởi tạo PDO với mảng options
            $conn = new PDO($dsn, $this->username, $this->password, $options);

            // 2. SET MÚI GIỜ CHO MYSQL (Để hàm NOW(), CURRENT_TIMESTAMP lưu chuẩn giờ VN)
            $conn->exec("SET time_zone = '+07:00'");

        } catch (PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }

        return $conn;
    }
}
?>