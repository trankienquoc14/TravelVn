<?php
require_once __DIR__ . '/../config/database.php';

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

    // Hàm tạo mã QR (Đã chỉnh sang Sacombank để SePay dễ quét)
    public function createQR($amount, $info)
    {
        $bank = "Sacombank"; // Đổi thành Sacombank cho khớp với tài khoản bạn đã kết nối
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

    public function payment()
    {
        // --- SỬA TẠI ĐÂY: Dùng hàm decode_id() để giải mã chuỗi từ URL thay vì ép kiểu (int) ---
        $hash_payment = $_GET['payment_id'] ?? '';
        $hash_booking = $_GET['booking_id'] ?? '';

        $payment_id = decode_id($hash_payment);
        $booking_id = decode_id($hash_booking);
        // -------------------------------------------------------------------------------------

        if ($payment_id <= 0 && $booking_id > 0) {
            $stmtFind = $this->db->prepare("SELECT payment_id FROM payments WHERE booking_id = ? LIMIT 1");
            $stmtFind->execute([$booking_id]);
            $row = $stmtFind->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $payment_id = $row['payment_id'];
            }
        }

        if ($payment_id <= 0) {
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

        // Bạn có thể giữ hàm tạo QR mặc định của bạn ở đây
        // $qr_url = $this->createQR($amount, $info);

        require __DIR__ . '/../views/payment.php';
    }

    // --- ĐÂY LÀ HÀM QUAN TRỌNG NHẤT: THAY THẾ WEBHOOK ---
    public function checkPaymentStatus()
    {
        header('Content-Type: application/json');

        $payment_id = $_GET['payment_id'] ?? 0;

        if (!$payment_id) {
            echo json_encode([
                "status" => "error",
                "message" => "Thiếu payment_id"
            ]);
            exit;
        }

        // Lấy thông tin thanh toán từ DB trước
        $stmt = $this->db->prepare("
        SELECT p.*, b.user_id
        FROM payments p
        JOIN bookings b ON p.booking_id=b.booking_id
        WHERE p.payment_id=?
    ");

        $stmt->execute([$payment_id]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$payment) {
            echo json_encode([
                "status" => "error",
                "message" => "Không tìm thấy giao dịch"
            ]);
            exit;
        }

        // Nếu đã thanh toán rồi thì khỏi kiểm tra API
        if ($payment['payment_status'] == 'paid') {
    echo json_encode([
        "status" => "paid",
        "realtime" => [
            "event" => "payment_success",
            "user_id" => $payment['user_id'],
            "booking_id" => $payment['booking_id'],
            "payment_id" => $payment_id,
            "status_text" => "Đã xác nhận",
            "badge_class" => "badge-confirmed",
            "message" => "Đơn hàng #" . str_pad($payment['booking_id'], 6, '0', STR_PAD_LEFT) . " đã được thanh toán."
        ]
    ]);
    exit;
}

        // Gọi SePay API
        $url = "https://my.sepay.vn/userapi/transactions/list?account_number="
            . $this->accountNumber;

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $this->sepayToken,
                "Content-Type: application/json"
            ]
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo json_encode([
                "status" => "error",
                "message" => curl_error($ch)
            ]);
            exit;
        }

        curl_close($ch);

        $result = json_decode($response, true);

        $isPaid = false;

        if (isset($result['transactions'])) {

            foreach ($result['transactions'] as $trans) {

                $content = strtoupper(
                    $trans['content']
                    ?? $trans['transaction_content']
                    ?? ''
                );

                $cleanContent = str_replace(
                    [' ', '-', '.'],
                    '',
                    $content
                );

                $search = "THANHTOAN" . $payment_id;

                // kiểm tra nội dung
                $matchContent = strpos(
                    $cleanContent,
                    $search
                ) !== false;

                // kiểm tra số tiền
                $money = (int) (
                    $trans['amount_in']
                    ?? $trans['amount']
                    ?? 0
                );

                $matchAmount =
                    $money == (int) $payment['amount'];

                if ($matchContent && $matchAmount) {

                    $isPaid = true;
                    break;
                }
            }
        }

        if ($isPaid) {

            try {

                $this->db->beginTransaction();

                $stmt = $this->db->prepare("
                UPDATE payments
                SET payment_status='paid'
                WHERE payment_id=?
            ");

                $stmt->execute([$payment_id]);

                $stmt = $this->db->prepare("
                UPDATE bookings
                SET status='confirmed'
                WHERE booking_id=?
            ");

                $stmt->execute([
                    $payment['booking_id']
                ]);

                $this->db->commit();

                echo json_encode([
    "status" => "paid",
    "realtime" => [
        "event" => "payment_success",
        "user_id" => $payment['user_id'],
        "booking_id" => $payment['booking_id'],
        "payment_id" => $payment_id,
        "status_text" => "Đã xác nhận",
        "badge_class" => "badge-confirmed",
        "message" => "Thanh toán QR thành công. Đơn hàng #" . str_pad($payment['booking_id'], 6, '0', STR_PAD_LEFT) . " đã được xác nhận."
    ]
]);

            } catch (Exception $e) {

                $this->db->rollBack();

                echo json_encode([
                    "status" => "error",
                    "message" => $e->getMessage()
                ]);
            }

        } else {

            echo json_encode([
                "status" => "pending"
            ]);
        }

        exit;
    }
}