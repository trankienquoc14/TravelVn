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
    // --- THÊM HÀM NÀY ĐỂ XỬ LÝ HỦY ĐƠN & HOÀN TRẢ GHẾ ---
    public function cancelBooking()
    {
        header('Content-Type: application/json');

        // Nhận ID thanh toán từ JavaScript gửi lên qua POST
        $payment_id = $_POST['payment_id'] ?? 0;

        if (!$payment_id) {
            echo json_encode(["status" => "error", "message" => "Thiếu payment_id"]);
            exit;
        }

        try {
            // 1. Lấy thông tin đơn hàng và chuyến đi để biết số lượng ghế cần trả lại
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

            // 2. Chỉ thực hiện hủy nếu đơn hàng đang ở trạng thái 'pending' (chờ thanh toán)
            if ($info['payment_status'] === 'pending') {

                $this->db->beginTransaction();

                // Đổi trạng thái payments thành cancelled
                $stmt1 = $this->db->prepare("UPDATE payments SET payment_status = 'cancelled' WHERE payment_id = ?");
                $stmt1->execute([$payment_id]);

                // Đổi trạng thái bookings thành cancelled
                $stmt2 = $this->db->prepare("UPDATE bookings SET status = 'cancelled' WHERE booking_id = ?");
                $stmt2->execute([$info['booking_id']]);

                // 🔥 QUAN TRỌNG NHẤT: Cộng trả lại số ghế vào bảng departures
                $stmt3 = $this->db->prepare("
                    UPDATE departures 
                    SET available_seats = available_seats + ? 
                    WHERE departure_id = ?
                ");
                $stmt3->execute([$info['number_of_people'], $info['departure_id']]);

                $this->db->commit();

                echo json_encode(["status" => "success", "message" => "Đã hủy đơn và hoàn trả ghế thành công"]);
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
    // --- ĐÂY LÀ HÀM KIỂM TRA TRẠNG THÁI THANH TOÁN TỰ ĐỘNG ---
    public function checkPaymentStatus()
    {
        header('Content-Type: application/json');

        $hash_id = $_GET['payment_id'] ?? '';

        // 🔥 SỬA LỖI CHÍ MẠNG: Nếu đã là số thuần túy thì giữ nguyên, nếu là chuỗi băm thì mới giải mã
        $payment_id = is_numeric($hash_id) ? (int)$hash_id : decode_id($hash_id);

        if ($payment_id <= 0) {
            echo json_encode(["status" => "error", "message" => "ID thanh toán không hợp lệ: " . $hash_id]);
            exit;
        }

        // 1. Gọi API SePay để lấy danh sách giao dịch mới nhất
        $url = "https://my.sepay.vn/userapi/transactions/list?account_number=" . $this->accountNumber;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER = [
            "Authorization: Bearer " . $this->sepayToken,
            "Content-Type: application/json"
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false) {
            $error_msg = curl_error($ch);
            echo json_encode(["status" => "error", "message" => "Lỗi kết nối cURL: " . $error_msg]);
            curl_close($ch);
            exit;
        }
        curl_close($ch);

        if ($httpCode !== 200) {
            echo json_encode(["status" => "error", "message" => "Lỗi API SePay, Code: " . $httpCode]);
            exit;
        }

        $result = json_decode($response, true);
        $is_paid = false;

        if (isset($result['transactions'])) {
            foreach ($result['transactions'] as $trans) {
                // Đọc nội dung chuyển khoản
                $content = strtoupper($trans['content'] ?? $trans['transaction_content'] ?? '');

                // Tìm từ khóa THANHTOAN36 (loại bỏ mọi khoảng trắng)
                $search = "THANHTOAN" . $payment_id;
                $cleanContent = str_replace(' ', '', $content);

                if (strpos($cleanContent, $search) !== false) {
                    $is_paid = true;
                    break;
                }
            }
        }

        if ($is_paid) {
            // 2. Tiến hành cập nhật trạng thái đã thanh toán vào Cơ sở dữ liệu
            $stmt = $this->db->prepare("UPDATE payments SET payment_status = 'paid' WHERE payment_id = ?");
            $stmt->execute([$payment_id]);

            $stmtPay = $this->db->prepare("SELECT booking_id FROM payments WHERE payment_id = ?");
            $stmtPay->execute([$payment_id]);
            $payData = $stmtPay->fetch(PDO::FETCH_ASSOC);

            if ($payData) {
                $this->db->prepare("UPDATE bookings SET status = 'confirmed' WHERE booking_id = ?")
                    ->execute([$payData['booking_id']]);

                // === GIỮ NGUYÊN HÀM GỬI THÔNG BÁO PUSHER CŨ CỦA BẠN ===
                try {
                    $options = array(
                        'cluster' => 'ap1',
                        'useTLS' => true
                    );
                    $pusher = new Pusher\Pusher(
                        'dfb02b6665ceae1b4add',
                        '8897f5d7c596c6ca98eb',
                        '2146792',
                        $options
                    );
                    $data['message'] = "💰 Ting ting! Khách hàng vừa chuyển khoản thành công đơn #" . $payData['booking_id'];
                    $pusher->trigger('admin-channel', 'new-booking', $data);
                } catch (Exception $e) {
                    error_log("Lỗi Pusher: " . $e->getMessage());
                }
            }
            echo json_encode(["status" => "paid"]);
        } else {
            // Nếu chưa thấy tiền, tiếp tục trả về trạng thái chờ
            echo json_encode(["status" => "pending"]);
        }
        exit;
    }
}