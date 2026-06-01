</div>
<style>
    /* --- PREMIUM FOOTER --- */
    .footer-custom {
        background-color: #ceefff;
        border-top: 1px solid #e2e8f0;
        color: #475569;
        font-family: 'Inter', 'Segoe UI', sans-serif;
        margin-top: 60px;
    }

    .footer-top {
        padding: 60px 0 40px;
    }

    .footer-brand {
        font-size: 1.8rem;
        font-weight: 800;
        color: #0194f3;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .footer-title {
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 20px;
        font-size: 1.15rem;
    }

    .footer-links,
    .footer-contact {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li,
    .footer-contact li {
        margin-bottom: 15px;
    }

    .footer-links a {
        color: #64748b;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .footer-links a:hover {
        color: #0194f3;
        transform: translateX(5px);
    }

    .footer-contact li {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        line-height: 1.6;
    }

    .footer-contact i {
        color: #0194f3;
        font-size: 1.2rem;
        margin-top: 2px;
    }

    .social-links {
        display: flex;
        gap: 12px;
        margin-top: 25px;
    }

    .social-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e2e8f0;
        color: #475569;
        text-decoration: none;
        transition: 0.3s;
        font-size: 1.1rem;
    }

    .social-icon:hover {
        background-color: #0194f3;
        color: white;
        transform: translateY(-4px);
        box-shadow: 0 4px 10px rgba(1, 148, 243, 0.3);
    }

    .footer-bottom {
        background-color: #e2f8ff;
        padding: 20px 0;
        font-size: 0.95rem;
        border-top: 1px solid #e2e8f0;
    }

    .payment-methods {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .payment-methods i {
        font-size: 1.8rem;
        color: #94a3b8;
        transition: 0.3s;
    }

    .payment-methods i:hover {
        color: #0194f3;
    }

    /* ================= CSS KHUNG CHAT CHUYÊN NGHIỆP ================= */
    .chat-widget {
        position: fixed;
        bottom: 25px;
        right: 25px;
        z-index: 1050;
        font-family: 'Inter', sans-serif;
    }

    .chat-button {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0194f3, #00d2ff);
        color: white;
        border: none;
        box-shadow: 0 4px 20px rgba(1, 148, 243, 0.4);
        font-size: 26px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chat-button:hover {
        transform: scale(1.08) rotate(5deg);
        box-shadow: 0 6px 24px rgba(1, 148, 243, 0.5);
    }

    .chat-panel {
        display: none;
        width: 360px;
        height: 520px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.16);
        flex-direction: column;
        position: absolute;
        bottom: 75px;
        right: 0;
        overflow: hidden;
        border: 1px solid rgba(226, 232, 240, 0.8);
        transition: all 0.3s ease;
    }

    .chat-header {
        background: linear-gradient(135deg, #0194f3, #00b4f3);
        color: white;
        padding: 16px 20px;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chat-body {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 14px;
        scroll-behavior: smooth;
    }

    .chat-body::-webkit-scrollbar {
        width: 5px;
    }

    .chat-body::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    .chat-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .chat-footer {
        padding: 14px;
        border-top: 1px solid #f1f5f9;
        background: white;
    }

    .chat-footer form {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin: 0;
    }

    .chat-input-wrapper {
        display: flex;
        align-items: center;
        gap: 6px;
        background: #f1f5f9;
        padding: 4px 6px;
        border-radius: 24px;
        border: 1px solid transparent;
        transition: all 0.2s;
        width: 100%;
        box-sizing: border-box;
    }

    .chat-input-wrapper:focus-within {
        background: white;
        border-color: #0194f3;
        box-shadow: 0 0 0 3px rgba(1, 148, 243, 0.15);
    }

    .chat-input {
        flex: 1;
        min-width: 0;
        border: none;
        background: transparent;
        padding: 8px 4px;
        outline: none;
        font-size: 0.95rem;
        color: #1e293b;
    }

    .chat-footer button {
        flex-shrink: 0;
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 50%;
        background: transparent;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .chat-footer button:hover {
        background: #e2e8f0;
        color: #0194f3;
    }

    .chat-footer .chat-submit {
        background: #0194f3;
        color: white;
        width: 38px;
        height: 38px;
        margin-left: 2px;
    }

    .msg-bubble {
        max-width: 80%;
        padding: 12px 16px;
        border-radius: 18px;
        font-size: 0.95rem;
        line-height: 1.45;
        word-wrap: break-word;
        position: relative;
    }

    .msg-customer {
        background: linear-gradient(135deg, #0194f3, #00b4f3);
        color: white;
        align-self: flex-end;
        border-bottom-right-radius: 4px;
    }

    .msg-admin {
        background: white;
        color: #1e293b;
        align-self: flex-start;
        border-bottom-left-radius: 4px;
        border: 1px solid #e2e8f0;
    }

    .chat-image {
        max-width: 100%;
        border-radius: 12px;
        cursor: pointer;
    }

    .chat-audio {
        width: 100%;
        max-width: 220px;
        margin-top: 4px;
    }

    .file-box {
        padding: 10px 14px;
        border-radius: 12px;
        background: rgba(0, 0, 0, 0.04);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    #filePreview {
        padding: 8px 12px;
        background: #f0fdf4;
        border: 1px dashed #4ade80;
        border-radius: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
    }

    #filePreview .remove-preview {
        cursor: pointer;
        color: #ef4444;
        font-weight: bold;
    }

    .recording {
        background: #ef4444 !important;
        color: white !important;
        animation: pulse 1.2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    .chat-loading {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 8px 12px;
    }

    .chat-loading span {
        width: 6px;
        height: 6px;
        background: #94a3b8;
        border-radius: 50%;
        animation: blink 1.4s infinite both;
    }

    .chat-loading span:nth-child(2) {
        animation-delay: .2s;
    }

    .chat-loading span:nth-child(3) {
        animation-delay: .4s;
    }

    @keyframes blink {
        0% {
            opacity: .2;
        }

        20% {
            opacity: 1;
        }

        100% {
            opacity: .2;
        }
    }
</style>

<footer class="footer-custom">
    <div class="footer-top">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand"><i class="bi bi-globe-americas"></i> TravelVN</div>
                    <p class="text-muted pe-lg-4" style="line-height: 1.7;">
                        TravelVN tự hào là nền tảng đặt tour du lịch hàng đầu, mang đến cho bạn những trải nghiệm khám
                        phá thế giới tuyệt vời với chi phí tối ưu và dịch vụ tận tâm nhất.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-icon" title="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-icon" title="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-icon" title="YouTube"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="social-icon" title="TikTok"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-title">Về TravelVN</h5>
                    <ul class="footer-links">
                        <li><a href="index.php?action=about">Giới thiệu</a></li>
                        <li><a href="index.php?action=careers">Tuyển dụng</a></li>
                        <li><a href="index.php?action=blogs">Tin tức du lịch</a></li>
                        <li><a href="index.php?action=affiliate">Chương trình đại lý</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-title">Hỗ trợ khách hàng</h5>
                    <ul class="footer-links">
                        <li><a href="index.php?action=guide">Hướng dẫn đặt tour</a></li>
                        <li><a href="index.php?action=faq">Câu hỏi thường gặp (FAQ)</a></li>
                        <li><a href="index.php?action=policy">Chính sách hoàn/hủy</a></li>
                        <li><a href="index.php?action=policy">Quy chế hoạt động</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-title">Thông tin liên hệ</h5>
                    <ul class="footer-contact">
                        <li>
                            <i class="bi bi-geo-alt-fill"></i>
                            <a href="https://www.google.com/maps/search/?api=1&query=123+Nguyễn+Văn+Bảo,+Quận+Gò+Vấp,+TP.HCM"
                                target="_blank" rel="noopener noreferrer"
                                style="text-decoration: none; color: inherit;">
                                Tòa nhà TravelVN, 12 Nguyễn Văn Bảo, quận Gò Vấp, TP.HCM
                            </a>
                        </li>
                        <li>
                            <i class="bi bi-telephone-fill"></i>
                            <span>Hotline: <strong>1900 1234</strong><br><small>(Hỗ trợ 24/7)</small></span>
                        </li>
                        <li>
                            <i class="bi bi-envelope-fill"></i>
                            <span>support@travelvn.com</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
            <p class="mb-3 mb-md-0 fw-medium">© 2026 TravelVN. Đã đăng ký bản quyền.</p>
            <div class="payment-methods">
                <i class="bi bi-credit-card-fill" title="Thẻ tín dụng"></i>
                <i class="bi bi-cash-coin" title="Tiền mặt"></i>
                <i class="bi bi-qr-code-scan" title="Quét mã QR"></i>
                <i class="bi bi-wallet-fill" title="Ví điện tử"></i>
            </div>
        </div>
    </div>
</footer>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>

<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$currentAction = $_GET['action'] ?? '';
$excludedPages = ['admin.php', 'manager.php', 'guide.php'];
$excludedActions = ['login', 'register'];

// CHỈ RENDER KHUNG CHAT KHI KHÔNG PHẢI TRANG LOGIN / REGISTER
if (!in_array($currentPage, $excludedPages) && !in_array($currentAction, $excludedActions)):
    ?>
    <div class="chat-widget" id="chatWidgetContainer">
        <div class="chat-panel" id="chatPanel">
            <div class="chat-header">
                <span><i class="bi bi-headset me-2"></i>Hỗ trợ trực tuyến</span>
                <i class="bi bi-x-lg" style="cursor:pointer" onclick="toggleChat()"></i>
            </div>
            <div class="chat-body" id="chatBody">
                <div class="text-center text-muted small mt-2 mb-3">Chào mừng bạn đến với TravelVN. Chúng tôi có thể giúp gì
                    cho bạn?</div>
            </div>
            <div class="chat-footer">
                <form id="chatForm" autocomplete="off">
                    <input type="hidden" id="chatDepartureId"
                        value="<?= htmlspecialchars($booking['departure_id'] ?? '') ?>">
                    <input type="file" id="chatFile" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx" hidden>
                    <div id="filePreview" class="d-none"></div>
                    <div class="chat-input-wrapper">
                        <button type="button" title="Gửi tệp dữ liệu"
                            onclick="document.getElementById('chatFile').click()"><i class="bi bi-paperclip"></i></button>
                        <button type="button" title="Chia sẻ vị trí" onclick="sendLocation()"><i
                                class="bi bi-geo-alt"></i></button>
                        <button type="button" id="recordBtn" title="Ghi âm"><i class="bi bi-mic"
                                id="recordIcon"></i></button>
                        <input type="text" id="chatInput" class="chat-input" placeholder="Nhập tin nhắn...">
                        <button type="submit" class="chat-submit" title="Gửi"><i class="bi bi-send-fill"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <button class="chat-button position-relative" onclick="toggleChat()" id="chatMainButton">
            <i class="bi bi-chat-dots-fill"></i>
            <span id="bubble-chat-badge"
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none"
                style="font-size: 0.75rem; border: 2px solid white; padding: 4px 6px;">0</span>
        </button>
    </div>
<?php endif; ?>

<script>
    // 1. CÁC BIẾN CỦA KHUNG CHAT
    const chatPanel = document.getElementById('chatPanel');
    const chatBody = document.getElementById('chatBody');
    const chatInput = document.getElementById('chatInput');
    const chatFile = document.getElementById('chatFile');
    const filePreview = document.getElementById('filePreview');
    const recordBtn = document.getElementById('recordBtn');
    const recordIcon = document.getElementById('recordIcon');
    const chatForm = document.getElementById('chatForm');

    let isChatOpen = false;
    let mySessionId = '';
    let mediaRecorder = null;
    let audioChunks = [];
    let selectedFileObject = null;

    // 2. KHỞI TẠO SOCKET VÀ BIẾN GLOBAL
    const globalUserRole = '<?= $_SESSION['user']['role'] ?? 'customer' ?>';
    const socket = io("wss://travelvn-socketserver.onrender.com");
    window.globalSocket = socket; // Lưu vào window để dễ dàng gọi emit từ các file js khác

    socket.on("connect", () => {
        console.log("🟢 Khách hàng đã kết nối với máy chủ Realtime!");
        if (mySessionId) socket.emit("join_room", mySessionId);
    });

    // 3. LẮNG NGHE TIN NHẮN TỪ CHAT (GIỮ NGUYÊN NHƯ BẠN VIẾT)
    socket.on("new_message", function (data) {
        if (data && data.session_id === mySessionId && data.sender_type !== 'customer') {
            if (isChatOpen) {
                let content = data.message_type === 'location' ? data.message : (data.message || data.file_url);
                appendMessage('admin', content, data.message_type);
                fetch('index.php?action=markAsRead', { method: 'POST' });
            } else {
                updateCustomerChatBadge();
            }
        }
    });

    // 🔥 LẮNG NGHE THÔNG BÁO TỪ HỆ THỐNG ĐỂ KÊU TING TING VÀ HIỆN CHUÔNG ĐỎ
    socket.on("system_notification", function (data) {
        let isForMe = false;
        let myUserId = '<?= $_SESSION['user']['user_id'] ?? '' ?>';
        let myRole = '<?= $_SESSION['user']['role'] ?? 'customer' ?>';

        // 1. Phân loại người nhận
        if (data.target_role === 'all') isForMe = true;
        if (data.target_role === 'admin_group' && (myRole === 'admin' || myRole === 'tour_manager')) isForMe = true;
        if (data.target_user_id && String(data.target_user_id) === String(myUserId)) isForMe = true;

        if (isForMe) {
            // 🔥 FIX LỖI: Đưa biến typeName ra ngoài để không bị lỗi undefined
            let typeName = data.type || 'Hệ thống';

            // --- A. CẬP NHẬT CHUÔNG ĐỎ TRÊN HEADER ---
            let badge = document.getElementById('global-notif-badge');
            if (badge) {
                let currentCount = parseInt(badge.innerText) || 0;
                badge.innerText = currentCount + 1;
                badge.classList.remove('d-none');
            }

            // --- B. VẼ GIAO DIỆN CHÈN VÀO DANH SÁCH CHUÔNG ---
            let notifMenu = document.querySelector('.notif-dropdown-menu');
            if (notifMenu) {
                let emptyMsg = notifMenu.querySelector('.text-muted.text-center');
                if (emptyMsg) emptyMsg.remove();

                let bgClass = 'bg-primary';
                let icon = 'bi-info-circle';

                if (typeName === 'Thanh Toán' || (data.title && data.title.includes('Thanh toán'))) { bgClass = 'bg-success'; icon = 'bi-currency-dollar'; typeName = 'Thanh Toán'; }
                else if (typeName === 'Xác Nhận') { bgClass = 'bg-success'; icon = 'bi-check-circle'; typeName = 'Xác Nhận'; }
                else if (typeName === 'Hủy Đơn' || (data.title && data.title.includes('hủy'))) { bgClass = 'bg-danger'; icon = 'bi-x-circle'; typeName = 'Hủy Đơn'; }
                else if (typeName === 'Đơn Hàng' || (data.title && data.title.includes('đơn'))) { bgClass = 'bg-warning text-dark'; icon = 'bi-cart-check'; typeName = 'Đơn Hàng'; }
                else if (data.title && data.title.includes('Tin nhắn')) { bgClass = 'bg-info'; icon = 'bi-chat-dots'; typeName = 'Tin nhắn'; }

                let newItem = document.createElement('li');
                newItem.innerHTML = `
                    <a href="${data.link || '#'}" class="dropdown-item d-flex align-items-center py-3 border-bottom notif-item bg-light">
                        <div class="me-3">
                            <div class="rounded-circle ${bgClass} text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px;">
                                <i class="bi ${icon} fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="small text-uppercase fw-bold mb-1" style="color: #64748b; font-size: 0.75rem;">
                                ${typeName}
                            </div>
                            <div class="text-dark fw-bold" style="font-size: 0.9rem; line-height: 1.4;">
                                ${data.message}
                            </div>
                            <div class="small text-muted mt-1" style="font-size: 0.8rem;">
                                <i class="bi bi-clock me-1"></i> Vừa xong
                            </div>
                        </div>
                    </a>
                `;

                let headerLi = notifMenu.querySelector('li.sticky-top');
                if (headerLi) {
                    headerLi.insertAdjacentElement('afterend', newItem);
                } else {
                    notifMenu.prepend(newItem);
                }
            }

            // --- C. HIỂN THỊ POPUP VÀ KÊU TING TING ---
            if (!data.is_silent) {
                let bgColor = "linear-gradient(to right, #0194f3, #00d2ff)";
                let popupIcon = "🔔";

                if (data.type === 'success' || typeName === 'Thanh Toán' || typeName === 'Xác Nhận') 
                    { bgColor = "linear-gradient(to right, #00b09b, #96c93d)"; popupIcon = "✅"; }
                if (data.type === 'warning' || typeName === 'Đơn Hàng') 
                    { bgColor = "linear-gradient(to right, #f5af19, #f12711)"; popupIcon = "⚠️"; }
                if (data.type === 'error' || typeName === 'Hủy Đơn') 
                    { bgColor = "linear-gradient(to right, #ff416c, #ff4b2b)"; popupIcon = "❌"; }

                if (typeof Toastify !== 'undefined') {
                    Toastify({
                        text: `${popupIcon} <b>${data.title || typeName}</b><br><small>${data.message}</small>`,
                        duration: 6000,
                        close: true,
                        gravity: "bottom",
                        position: "right",
                        style: { background: bgColor, borderRadius: "12px", color: "#fff", padding: "14px 20px" },
                        escapeMarkup: false
                    }).showToast();
                }

                // Phát tiếng Ting Ting
                let audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
                audio.volume = 1.0;
                audio.play().catch(e => { console.log("Trình duyệt chặn âm thanh tự động"); });
            }
            if (data.type === 'success' || data.title.includes('Thanh toán')) {
                // Giả sử bạn gửi kèm booking_id trong data
                if (data.booking_id) {
                    let badge = document.getElementById("badge-" + data.booking_id);
                    if (badge) {
                        badge.className = "status-badge badge-confirmed";
                        badge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Đã thanh toán';
                    }
                }
            }

            // --- D. CHỈ HIỆN POPUP THÔNG BÁO ---
            if (!data.is_silent) {

                Toastify({
                    text: `${popupIcon} <b>${data.title || typeName}</b><br><small>${data.message}</small>`,
                    duration: 5000,
                    close: true,
                    gravity: "bottom",
                    position: "right",
                    escapeMarkup: false,
                    style: {
                        background: bgColor,
                        borderRadius: "12px"
                    }
                }).showToast();

            }
        }
    });
    // CÁC HÀM XỬ LÝ CHAT BÊN DƯỚI (Giữ nguyên y hệt code cũ của bạn)
    function scrollBottom() { if (chatBody) { chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: 'smooth' }); } }

    function updateCustomerChatBadge() {
        fetch('index.php?action=getCustomerUnreadCount')
            .then(res => res.json())
            .then(data => {
                const badge = document.getElementById('bubble-chat-badge');
                if (!badge) return;
                if (data && data.total > 0) { badge.innerText = data.total; badge.classList.remove('d-none'); }
                else { badge.classList.add('d-none'); }
            }).catch(e => console.log(e));
    }

    function hideBadge() {
        const badge = document.getElementById('bubble-chat-badge');
        if (badge) { badge.innerText = '0'; badge.classList.add('d-none'); }
    }

    function appendMessage(sender, content, messageType = 'text') {
        if (!chatBody) return;
        const existingLoader = document.getElementById('temp-chat-loader');
        if (existingLoader) existingLoader.remove();

        const div = document.createElement('div');
        div.className = `msg-bubble msg-${sender}`;

        switch (messageType) {
            case 'image':
                let imgSrc = content.startsWith('http') ? content : window.location.origin + content;
                div.innerHTML = `<img src="${imgSrc}"class="chat-image"onclick="window.open('${imgSrc}')">`;
                break;
            case 'audio':
                let audioSrc = content.startsWith('http') ? content : window.location.origin + '/' + content;
                div.innerHTML = `<audio controls class="chat-audio" style="min-width: 200px; height: 40px;"><source src="${audioSrc}"></audio>`;
                break;
            case 'location':
                div.innerHTML = `<a href="${content}" target="_blank">📍 Bản đồ vị trí</a>`;
                break;
            case 'file':
                const fileName = content.split('/').pop() || "Tài liệu";
                div.innerHTML = `<div class="file-box"><i class="bi bi-file-earmark-text"></i><a href="${content}" target="_blank">${fileName}</a></div>`;
                break;
            default:
                div.innerText = content;
        }
        chatBody.appendChild(div);
        scrollBottom();
    }

    function showLoadingIndicator() {
        const div = document.createElement('div');
        div.id = 'temp-chat-loader';
        div.className = 'msg-bubble msg-customer chat-loading';
        div.innerHTML = `<span></span><span></span><span></span>`;
        chatBody.appendChild(div);
        scrollBottom();
    }

    // 🔥 SỬA ĐỔI: Thêm `is_silent: true` cho mọi kịch bản chat (Text, File, Vị trí, Audio)
    function triggerChatNotification() {
        if (typeof window.globalSocket !== 'undefined') {
            window.globalSocket.emit("send_notification", {
                target_role: 'admin_group',
                type: 'info',
                title: '💬 Tin nhắn mới',
                message: 'Khách hàng vừa gửi tin nhắn hỗ trợ.',
                is_silent: true // 🔕 CHỈ HIỂN THỊ CHUÔNG, KHÔNG KÊU, KHÔNG TOAST POPUP
            });
        }
    }

    function loadChatHistory() {
        fetch('index.php?action=getHistory')
            .then(res => res.json())
            .then(data => {
                if (!data) return;
                chatBody.innerHTML = `<div class="text-center text-muted small mt-2 mb-3">Chào mừng bạn đến với TravelVN 👋</div>`;
                if (data.length > 0) {
                    mySessionId = data[0].session_id;
                    socket.emit("join_room", mySessionId);
                }
                data.forEach(msg => {
                    let content = msg.message_type === 'location' ? msg.message : (msg.message || msg.file_url);
                    appendMessage(msg.sender_type, content, msg.message_type);
                });
                scrollBottom();
            });
    }

    function toggleChat() {
        isChatOpen = !isChatOpen;
        chatPanel.style.display = isChatOpen ? 'flex' : 'none';
        if (isChatOpen) {
            loadChatHistory();
            fetch('index.php?action=markAsRead', { method: 'POST' });
            hideBadge();
        }
    }

    if (chatFile) {
        chatFile.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            selectedFileObject = file;
            filePreview.classList.remove('d-none');
            filePreview.innerHTML = `
            <span class="text-truncate" style="max-width: 200px;">📎 ${file.name}</span>
            <span class="remove-preview" onclick="clearFilePreview()">✕</span>`;
        });
    }

    function clearFilePreview() {
        selectedFileObject = null;
        if (chatFile) chatFile.value = '';
        filePreview.innerHTML = '';
        filePreview.classList.add('d-none');
    }

    if (chatForm) {
        chatForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const textMsg = chatInput.value.trim();
            const departureId = document.getElementById('chatDepartureId')?.value || '';

            if (selectedFileObject) {
                showLoadingIndicator();
                const formData = new FormData();
                formData.append('file', selectedFileObject);
                formData.append('departure_id', departureId);
                clearFilePreview();

                fetch('index.php?action=uploadFile', { method: 'POST', body: formData })
                    .then(res => res.json())
                    .then(data => {
                        const loader = document.getElementById('temp-chat-loader');
                        if (loader) loader.remove();
                        if (data && data.url) {
                            appendMessage('customer', data.url, data.type);
                            if (data.message_data && data.message_data.session_id && !mySessionId) {
                                mySessionId = data.message_data.session_id;
                                socket.emit("join_room", mySessionId);
                            }
                            if (data.message_data) socket.emit("send_message", data.message_data);

                            // 🔥 THÊM ĐOẠN NÀY: Gọi thông báo im lặng khi up file
                            triggerChatNotification();
                        } else { alert("Tải lên thất bại: " + (data.error || 'Server không thể lưu file')); }
                    })
                    .catch(err => {
                        const loader = document.getElementById('temp-chat-loader');
                        if (loader) loader.remove();
                        alert("Lỗi kết nối máy chủ khi tải lên file!");
                    });
            }
            else if (textMsg) {
                appendMessage('customer', textMsg, 'text');
                chatInput.value = '';
                const formData = new FormData();
                formData.append('message', textMsg);
                formData.append('sender_type', 'customer');
                formData.append('departure_id', departureId);

                fetch('index.php?action=sendMessage', { method: 'POST', body: formData })
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.session_id && !mySessionId) {
                            mySessionId = data.session_id;
                            socket.emit("join_room", mySessionId);
                        }
                        if (data && data.user_message) {
                            socket.emit("send_message", data.user_message);
                        }

                        // 🔥 THÊM ĐOẠN NÀY: Gọi thông báo im lặng khi chat text
                        triggerChatNotification();

                        if (data && data.is_first && data.bot_message) {
                            appendMessage('admin', data.bot_message.message, 'text');
                            socket.emit("send_message", data.bot_message);
                        }
                    });
            }
        });
    }

    function sendLocation() {
        if (navigator.geolocation) {
            showLoadingIndicator();
            navigator.geolocation.getCurrentPosition(function (pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                const mapLink = `https://maps.google.com/?q=${lat},${lng}`;
                appendMessage('customer', mapLink, 'location');

                fetch('index.php?action=sendLocation', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `latitude=${lat}&longitude=${lng}`
                })
                    .then(res => res.json())
                    .then(data => {
                        const loader = document.getElementById('temp-chat-loader');
                        if (loader) loader.remove();
                        if (data.message_data && data.message_data.session_id && !mySessionId) {
                            mySessionId = data.message_data.session_id;
                            socket.emit("join_room", mySessionId);
                        }
                        if (data.status === 'success') {
                            socket.emit("send_message", data.message_data);

                            // 🔥 THÊM ĐOẠN NÀY: Gọi thông báo im lặng khi gửi vị trí
                            triggerChatNotification();
                        }
                    });
            });
        }
    }

    if (recordBtn) {
        recordBtn.onclick = async () => {
            if (mediaRecorder && mediaRecorder.state === "recording") {
                mediaRecorder.stop();
                return;
            }
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];
                mediaRecorder.ondataavailable = e => { audioChunks.push(e.data); };
                mediaRecorder.onstop = () => {
                    recordBtn.classList.remove('recording');
                    recordIcon.className = 'bi bi-mic';
                    showLoadingIndicator();

                    const audioType = mediaRecorder.mimeType || 'audio/webm';
                    const audioBlob = new Blob(audioChunks, { type: audioType });
                    const formData = new FormData();
                    formData.append('voice', audioBlob, 'voice_record');
                    formData.append('departure_id', document.getElementById('chatDepartureId')?.value || '');

                    fetch('index.php?action=uploadVoice', { method: 'POST', body: formData })
                        .then(res => res.json())
                        .then(data => {
                            const loader = document.getElementById('temp-chat-loader');
                            if (loader) loader.remove();
                            if (data && data.url) {
                                appendMessage('customer', data.url, 'audio');
                                if (data.message_data && data.message_data.session_id && !mySessionId) {
                                    mySessionId = data.message_data.session_id;
                                    socket.emit("join_room", mySessionId);
                                }
                                if (data.message_data) {
                                    socket.emit("send_message", data.message_data);

                                    // 🔥 THÊM ĐOẠN NÀY: Gọi thông báo im lặng khi gửi voice
                                    triggerChatNotification();
                                }
                            } else { alert("Ghi âm thất bại: " + (data.error || 'Server không thể lưu file')); }
                        })
                        .catch(err => {
                            const loader = document.getElementById('temp-chat-loader');
                            if (loader) loader.remove();
                            alert("Lỗi kết nối khi gửi ghi âm!");
                        });
                    stream.getTracks().forEach(track => track.stop());
                };
                mediaRecorder.start();
                recordBtn.classList.add('recording');
                recordIcon.className = 'bi bi-stop-fill';
            } catch (err) {
                alert('Không tìm thấy Microphone hoặc bạn chưa cấp quyền.');
            }
        };
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateCustomerChatBadge();
    });
</script>
<?php if (!empty($_SESSION['realtime_notify'])): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            setTimeout(function () {
                if (typeof window.globalSocket !== 'undefined') {
                    // Lấy thông báo từ PHP Session và bắn thẳng lên máy chủ Socket
                    window.globalSocket.emit("send_notification", <?= json_encode($_SESSION['realtime_notify']) ?>);
                }
            }, 800); // Đợi 0.8s cho kết nối mạng ổn định rồi mới bắn
        });
    </script>
    <?php
    // Bắn xong thì xóa đi để không bị kêu lại khi F5
    unset($_SESSION['realtime_notify']);
endif;
?>