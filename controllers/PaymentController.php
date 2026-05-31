<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php'; // Đảm bảo gọi file chứa decode_id()

class PaymentController
{
    private $db;
    // --- CẤU HÌNH SEPAY ---
    private $sepayToken = "CUBA1ZD6SPBMX9YBRLK8JJD40GJCS6RUF3WQKFYR2MON9VA10CZPSMQWNHKIZX7O";
    private $accountNumber = "050134910132";

    public function __construct()
    {
        $this->db = (new Database())->connect();
    }

    // Hàm tạo mã QR 
    public function createQR($amount, $info)
    {
        $bank = "Sacombank"; 
        $account = $this->accountNumber;
        $name = "CONG TY DU LICH TRAVELVN";

        $qr_url = "https://img.vietqr.io/image/"
            . $bank . "-" . $account . "-compact2.png"
            . "?amount=" . $amount
            . "&addInfo=" . urlencode($info)
            . "&accountName=" . urlencode($name)
            . "&t=" . time();

        return $qr_url;
    }

    // --- HÀM XỬ LÝ HỦY ĐƠN & HOÀN TRẢ GHẾ ---
    public function cancelBooking()
    {
        header('Content-Type: application/json');

        // Nhận ID từ JS (có thể là mã băm hoặc số)
        $hash_id = $_POST['payment_id'] ?? '';
        $payment_id = is_numeric($hash_id) ? (int)$hash_id : decode_id($hash_id);

        if ($payment_id <= 0) {
            echo json_encode(["status" => "error", "message" => "Mã thanh toán không hợp lệ!"]);
            exit;
        }

        try {
            $stmt = $this->db->prepare("
                SELECT p.payment_status, b.booking_id, b.departure_id, b.number_of_people 
                FROM payments p
                JOIN bookings b ON p.booking_id = b.booking_id
                WHERE p.payment_id = ?
            ");
            $stmt->execute([$payment_id]);
            $info = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$info) {
                echo json_encode(["status" => "error", "message" => "Không tìm thấy giao dịch"]);
                exit;
            }

            // Đơn pending chưa trừ ghế nên khi hủy KHÔNG ĐƯỢC cộng ghế
            if ($info['payment_status'] === 'pending') {
                $this->db->beginTransaction();

                $this->db->prepare("UPDATE payments SET payment_status = 'cancelled' WHERE payment_id = ?")->execute([$payment_id]);
                $this->db->prepare("UPDATE bookings SET status = 'cancelled' WHERE booking_id = ?")->execute([$info['booking_id']]);

                // Lưu thông báo Hủy đơn vào Database
                $message = "⚠️ Khách hàng đã không hoàn tất thanh toán (Hết hạn 15 phút). Đơn #" . $info['booking_id'] . " đã bị hủy.";
                $link = "manager.php?action=bookingDetail&id=" . $info['booking_id'];
                $this->db->prepare("INSERT INTO notifications (user_id, booking_id, type, link, message) VALUES (NULL, ?, 'Hủy Đơn', ?, ?)")
                         ->execute([$info['booking_id'], $link, $message]);

                $this->db->commit();
                echo json_encode(["status" => "success", "message" => "Đã hủy đơn thành công"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Đơn hàng đã được xử lý trước đó, không thể hủy."]);
            }
        } catch (Exception $e) {
            $this->db->rollBack();
            echo json_encode(["status" => "error", "message" => "Lỗi hệ thống: " . $e->getMessage()]);
        }
        exit;
    }

    public function payment()
    {
        $hash_id = $_GET['payment_id'] ?? '';
        $payment_id = is_numeric($hash_id) ? (int)$hash_id : decode_id($hash_id);

        $booking_hash = $_GET['booking_id'] ?? '';
        $booking_id = !empty($booking_hash) ? (is_numeric($booking_hash) ? (int)$booking_hash : decode_id($booking_hash)) : 0;

        if ($payment_id === 0 && $booking_id > 0) {
            $stmtFind = $this->db->prepare("SELECT payment_id FROM payments WHERE booking_id = ? LIMIT 1");
            $stmtFind->execute([$booking_id]);
            $row = $stmtFind->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $payment_id = $row['payment_id'];
            }
        }

        if ($payment_id === 0) {
            die("<div class='text-center mt-5'><h3>Thiếu thông tin thanh toán hoặc đường dẫn không hợp lệ!</h3></div>");
        }

        $stmt = $this->db->prepare("
            SELECT p.*, b.customer_name, t.tour_name
            FROM payments p
            JOIN bookings b ON p.booking_id = b.booking_id
            JOIN departures d ON b.departure_id = d.departure_id
            JOIN tours t ON d.tour_id = t.tour_id
            WHERE p.payment_id = ?
        ");
        $stmt->execute([$payment_id]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$payment) {
            die("<div class='text-center mt-5'><h3>Không tìm thấy giao dịch</h3></div>");
        }

        $amount = (int) $payment['amount'];
        $info = "THANHTOAN " . $payment_id;

        require __DIR__ . '/../views/payment.php';
    }

    // --- ĐÂY LÀ HÀM QUAN TRỌNG NHẤT: THAY THẾ WEBHOOK ---
    public function checkPaymentStatus()
    {
        header('Content-Type: application/json');

        $hash_id = $_GET['payment_id'] ?? '';
        $payment_id = is_numeric($hash_id) ? (int)$hash_id : decode_id($hash_id);

        if ($payment_id <= 0) {
            echo json_encode(["status" => "error", "message" => "Invalid Payment ID"]);
            exit;
        }

        // 1. Gọi API SePay
        $url = "https://my.sepay.vn/userapi/transactions/list?account_number=" . $this->accountNumber;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $this->sepayToken,
            "Content-Type: application/json"
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false) {
            $error_msg = curl_error($ch);
            echo json_encode(["status" => "error", "message" => "Curl Error: " . $error_msg]);
            curl_close($ch);
            exit;
        }
        curl_close($ch);

        $result = json_decode($response, true);

        if ($httpCode !== 200) {
            echo json_encode(["status" => "error", "message" => "API Error: " . $httpCode]);
            exit;
        }

        $is_paid = false;
        if (isset($result['transactions'])) {
            foreach ($result['transactions'] as $trans) {
                $content = strtoupper($trans['content'] ?? $trans['transaction_content'] ?? '');
                
                $search = "THANHTOAN" . $payment_id;
                $cleanContent = str_replace(' ', '', $content);

                if (strpos($cleanContent, $search) !== false) {
                    $is_paid = true;
                    break;
                }
            }
        }

        if ($is_paid) {
            try {
                $this->db->beginTransaction();

                // Cập nhật trạng thái thanh toán
                $stmt = $this->db->prepare("UPDATE payments SET payment_status = 'paid' WHERE payment_id = ?");
                $stmt->execute([$payment_id]);

                // Lấy thông tin đơn hàng để xử lý ghế và thông báo
                $stmtPay = $this->db->prepare("
                    SELECT b.booking_id, b.number_of_people, b.departure_id 
                    FROM payments p
                    JOIN bookings b ON p.booking_id = b.booking_id
                    WHERE p.payment_id = ?
                ");
                $stmtPay->execute([$payment_id]);
                $payData = $stmtPay->fetch(PDO::FETCH_ASSOC);

                if ($payData) {
                    // 1. Xác nhận đơn hàng
                    $this->db->prepare("UPDATE bookings SET status = 'confirmed' WHERE booking_id = ?")
                        ->execute([$payData['booking_id']]);
                    
                    // 2. 🔥 CẬP NHẬT GHẾ VÀO CƠ SỞ DỮ LIỆU
                    $this->db->prepare("
                        UPDATE departures 
                        SET booked_seats = booked_seats + ?, 
                            available_seats = available_seats - ? 
                        WHERE departure_id = ?
                    ")->execute([
                        $payData['number_of_people'], 
                        $payData['number_of_people'], 
                        $payData['departure_id']
                    ]);

                    // 3. 🔥 LƯU THÔNG BÁO VÀO DATABASE CHO ADMIN
                    $message = "💰 Ting ting! Khách hàng vừa chuyển khoản thành công đơn #" . $payData['booking_id'];
                    $link = "manager.php?action=bookingDetail&id=" . $payData['booking_id'];
                    $type = "Thanh Toán";
                    
                    $stmtNotif = $this->db->prepare("INSERT INTO notifications (user_id, booking_id, type, link, message) VALUES (NULL, ?, ?, ?, ?)");
                    $stmtNotif->execute([$payData['booking_id'], $type, $link, $message]);
                }

                $this->db->commit();
                echo json_encode(["status" => "paid"]);

            } catch (Exception $e) {
                $this->db->rollBack();
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
        } else {
            echo json_encode(["status" => "pending"]);
        }
        exit;
    }
}