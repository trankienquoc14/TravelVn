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

    // --- HÀM XỬ LÝ HỦY ĐƠN & HOÀN TRẢ GHẾ (15 PHÚT HẾT HẠN) ---
    public function cancelBooking()
    {
        header('Content-Type: application/json');

        // Nhận ID từ JS
        $hash_id = $_POST['payment_id'] ?? '';
        $payment_id = is_numeric($hash_id) ? (int) $hash_id : decode_id($hash_id);

        if ($payment_id <= 0) {
            echo json_encode(["status" => "error", "message" => "Mã thanh toán không hợp lệ!"]);
            exit;
        }

        try {
            $stmt = $this->db->prepare("
                SELECT p.payment_status, b.booking_id, b.departure_id, b.number_of_people 
                FROM payments p
                JOIN bookings b ON p.booking_id = b.booking_id
                WHERE p.payment_id = ? FOR UPDATE
            ");
            $stmt->execute([$payment_id]);
            $info = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$info) {
                echo json_encode(["status" => "error", "message" => "Không tìm thấy giao dịch"]);
                exit;
            }

            if ($info['payment_status'] === 'pending') {
                $this->db->beginTransaction();

                // 1. 🔥 ĐÃ SỬA: HỦY ĐƠN THÌ PHẢI HOÀN LẠI GHẾ (Vì lúc đặt đã trừ ghế rồi)
                $this->db->prepare("UPDATE departures SET available_seats = available_seats + ?, booked_seats = booked_seats - ? WHERE departure_id = ?")
                    ->execute([$info['number_of_people'], $info['number_of_people'], $info['departure_id']]);

                // 2. Cập nhật trạng thái Hủy
                $this->db->prepare("UPDATE payments SET payment_status = 'cancelled' WHERE payment_id = ?")->execute([$payment_id]);
                $this->db->prepare("UPDATE bookings SET status = 'cancelled' WHERE booking_id = ?")->execute([$info['booking_id']]);

                // 3. Lưu thông báo Hủy đơn vào Database báo cho Admin
                $message = "⚠️ Khách hàng đã không hoàn tất thanh toán (Hết hạn 15 phút). Đơn #" . str_pad($info['booking_id'], 6, '0', STR_PAD_LEFT) . " đã bị hủy.";
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
        $payment_id = is_numeric($hash_id) ? (int) $hash_id : decode_id($hash_id);

        $booking_hash = $_GET['booking_id'] ?? '';
        $booking_id = !empty($booking_hash) ? (is_numeric($booking_hash) ? (int) $booking_hash : decode_id($booking_hash)) : 0;

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

    // --- KIỂM TRA THANH TOÁN (WEBHOOK TỰ CHẾ) ---
    // --- KIỂM TRA THANH TOÁN (WEBHOOK TỰ CHẾ) ---
    public function checkPaymentStatus()
    {
        header('Content-Type: application/json');

        $hash_id = $_GET['payment_id'] ?? '';
        $payment_id = is_numeric($hash_id) ? (int) $hash_id : decode_id($hash_id);

        if ($payment_id <= 0) {
            echo json_encode(["status" => "error", "message" => "Invalid Payment ID"]);
            exit;
        }

        // 1. KẾT HỢP LẤY booking_date ĐỂ CHẶN CÁC GIAO DỊCH QUÁ KHỨ (DO RESET DATABASE)
        $stmtCheck = $this->db->prepare("
            SELECT p.payment_status, p.amount, b.booking_date 
            FROM payments p
            JOIN bookings b ON p.booking_id = b.booking_id
            WHERE p.payment_id = ?
        ");
        $stmtCheck->execute([$payment_id]);
        $payCheck = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if (!$payCheck) {
            echo json_encode(["status" => "error", "message" => "Không tìm thấy giao dịch"]);
            exit;
        }

        if ($payCheck['payment_status'] === 'paid') {
            echo json_encode(["status" => "paid"]);
            exit;
        }

        $expectedAmount = (float) $payCheck['amount'];
        $bookingTime = strtotime($payCheck['booking_date']); // Thời điểm tạo đơn

        // 2. Gọi API SePay
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
            echo json_encode(["status" => "error", "message" => "Curl Error: " . curl_error($ch)]);
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
                $amount_in = (float) ($trans['amount_in'] ?? 0);
                $transTime = strtotime($trans['transaction_date'] ?? 'now'); // Lấy thời gian khách chuyển tiền

                // 🔥 ĐIỀU KIỆN 1: GIAO DỊCH PHẢI XẢY RA SAU KHI ĐƠN HÀNG ĐƯỢC TẠO
                if ($transTime >= $bookingTime) {

                    // 🔥 ĐIỀU KIỆN 2: ĐÚNG MÃ THANH TOÁN
                    $pattern = '/THANHTOAN\s*0*' . $payment_id . '\b/i';
                    if (preg_match($pattern, $content)) {

                        // 🔥 ĐIỀU KIỆN 3: ĐỦ HOẶC DƯ TIỀN
                        if ($amount_in >= $expectedAmount) {
                            $is_paid = true;
                            break;
                        }
                    }
                }
            }
        }

        if ($is_paid) {
            try {
                $this->db->beginTransaction();

                $stmt = $this->db->prepare("UPDATE payments SET payment_status = 'paid' WHERE payment_id = ?");
                $stmt->execute([$payment_id]);

                $stmtPay = $this->db->prepare("
                    SELECT b.booking_id, b.number_of_people, b.departure_id, b.user_id 
                    FROM payments p
                    JOIN bookings b ON p.booking_id = b.booking_id
                    WHERE p.payment_id = ?
                ");
                $stmtPay->execute([$payment_id]);
                $payData = $stmtPay->fetch(PDO::FETCH_ASSOC);

                if ($payData) {
                    $this->db->prepare("UPDATE bookings SET status = 'confirmed' WHERE booking_id = ?")
                        ->execute([$payData['booking_id']]);

                    $message = "💰 Ting ting! Khách hàng vừa chuyển khoản thành công đơn #" . str_pad($payData['booking_id'], 6, '0', STR_PAD_LEFT);
                    $link = "manager.php?action=bookingDetail&id=" . $payData['booking_id'];
                    $type = "Thanh Toán";

                    $stmtNotif = $this->db->prepare("INSERT INTO notifications (user_id, booking_id, type, link, message) VALUES (NULL, ?, ?, ?, ?)");
                    $stmtNotif->execute([$payData['booking_id'], $type, $link, $message]);

                    if (session_status() === PHP_SESSION_NONE)
                        session_start();
                    $_SESSION['realtime_notify'] = [
                        'target_role' => 'admin_group',
                        'type' => 'Thanh Toán',
                        'title' => 'Nhận chuyển khoản',
                        'message' => $message
                    ];
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