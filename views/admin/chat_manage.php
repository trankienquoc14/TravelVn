<?php include __DIR__ . "/../layouts/header.php"; ?>

<style>
    /* CUSTOM CSS CHO TRUNG TÂM HỖ TRỢ */
    :root {
        --chat-bg: #f8fafc;
        --chat-border: #e2e8f0;
        --chat-primary: #0ea5e9;
        --chat-primary-light: #e0f2fe;
        --chat-me: #0ea5e9;
        --chat-customer: #ffffff;
    }

    .support-card {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        border: 1px solid var(--chat-border);
        height: 85vh;
        min-height: 550px;
        overflow: hidden;
    }

    @media (max-width: 767.98px) {
        .support-card {
            height: auto;
            overflow: hidden;
        }

        .session-list-col {
            height: 40vh !important;
            border-bottom: 2px solid var(--chat-border);
        }

        .chat-main-col {
            height: 60vh !important;
        }
    }

    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .session-list-col {
        background: #ffffff;
    }

    .session-item {
        transition: all 0.2s ease;
        border-bottom: 1px solid var(--chat-border) !important;
        cursor: pointer;
        padding: 16px;
    }

    .session-item:hover {
        background: #f1f5f9;
    }

    .session-item.active {
        background: var(--chat-primary-light);
        border-right: 4px solid var(--chat-primary) !important;
    }

    .msg-bubble {
        max-width: 75%;
        padding: 14px 18px;
        font-size: 0.95rem;
        line-height: 1.5;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .msg-me {
        background: var(--chat-me);
        color: #ffffff;
        border-radius: 20px 20px 4px 20px;
        align-self: flex-end;
    }

    .msg-customer {
        background: var(--chat-customer);
        color: #1e293b;
        border: 1px solid var(--chat-border);
        border-radius: 20px 20px 20px 4px;
        align-self: flex-start;
    }

    .chat-input-area {
        background: #ffffff;
        padding: 15px 20px;
        border-top: 1px solid var(--chat-border);
    }

    .chat-input-wrapper {
        background: #f1f5f9;
        border-radius: 25px;
        padding: 6px 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chat-input-wrapper input {
        border: none;
        background: transparent;
        box-shadow: none !important;
        flex: 1;
    }

    .chat-input-wrapper button[type="button"] {
        background: transparent;
        border: none;
        color: #64748b;
        padding: 4px 8px;
        border-radius: 50%;
        transition: 0.2s;
    }

    .chat-input-wrapper button[type="button"]:hover {
        color: var(--chat-primary);
        background: #e2e8f0;
    }

    .btn-send {
        border-radius: 50%;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .info-sidebar {
        background: #ffffff;
        border-left: 1px solid var(--chat-border);
        padding: 20px;
    }

    .info-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--chat-primary-light);
        color: var(--chat-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 15px;
    }

    .chat-image {
        max-width: 220px;
        border-radius: 12px;
        cursor: pointer;
        border: 2px solid #e2e8f0;
    }

    .chat-audio {
        width: 100%;
        max-width: 240px;
        margin-top: 5px;
        height: 38px;
    }

    .file-box {
        padding: 10px 14px;
        border-radius: 8px;
        background: rgba(0, 0, 0, 0.05);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .msg-me .file-box a {
        color: white;
        text-decoration: underline;
    }

    .msg-customer .file-box a {
        color: var(--chat-primary);
        text-decoration: none;
    }

    .msg-location {
        color: inherit;
        text-decoration: underline;
        font-weight: bold;
    }

    #adminFilePreview {
        font-size: 0.85rem;
        padding: 6px 12px;
        background: #e0f2fe;
        border: 1px dashed #0ea5e9;
        border-radius: 8px;
        margin-bottom: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .recording {
        background: #ef4444 !important;
        color: white !important;
        animation: pulse 1s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }

    .temp-loader span {
        display: inline-block;
        width: 6px;
        height: 6px;
        background: currentColor;
        border-radius: 50%;
        animation: blink 1.4s infinite;
    }

    .temp-loader span:nth-child(2) {
        animation-delay: .2s;
    }

    .temp-loader span:nth-child(3) {
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

<div class="container-fluid py-4" style="background: var(--chat-bg);">
    <div class="row">
        <?php include __DIR__ . "/../layouts/sidebar_manager.php"; ?>

        <div class="col-lg-9">
            <div class="support-card">
                <div class="row g-0 h-100">

                    <div class="col-md-3 border-end h-100 d-flex flex-column session-list-col">
                        <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-white">
                            <h5 class="mb-0 fw-bold" style="color: #0f172a;">Hộp thư</h5>
                            <button class="btn btn-sm btn-light rounded-circle" onclick="loadSessions()"
                                title="Làm mới">
                                <i class="bi bi-arrow-clockwise text-primary"></i>
                            </button>
                        </div>
                        <div class="p-2 border-bottom">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-0"><i
                                        class="bi bi-search text-muted"></i></span>
                                <input type="text" class="form-control bg-light border-0" placeholder="Tìm kiếm...">
                            </div>
                        </div>
                        <div class="list-group list-group-flush overflow-auto flex-grow-1" id="sessionList">
                            <div class="text-center p-5 text-muted small">Đang tải hộp thư...</div>
                        </div>
                    </div>

                    <div class="col-md-6 h-100 d-flex flex-column chat-main-col" style="background: #f8fafc;">
                        <div id="chatHeader" class="p-3 border-bottom bg-white d-flex align-items-center fw-bold"
                            style="height: 70px; color: #0f172a;">
                            <span class="text-muted fw-normal"><i class="bi bi-chat-left-text me-2"></i>Chọn đoạn chat
                                để bắt đầu</span>
                        </div>

                        <div id="adminChatBody" class="p-4 flex-grow-1 overflow-auto d-flex flex-column gap-3">
                            <div class="text-center my-auto" style="opacity: 0.4;">
                                <img src="https://cdn-icons-png.flaticon.com/512/4080/4080911.png"
                                    style="width: 120px; margin-bottom: 20px;">
                                <h5 class="fw-bold">Trung tâm hỗ trợ TravelVN</h5>
                                <p class="text-muted small">Mọi tin nhắn từ khách hàng sẽ hiển thị tại đây.</p>
                            </div>
                        </div>

                        <div class="chat-input-area">
                            <form id="adminChatForm" class="d-none">
                                <input type="file" id="adminChatFile" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx"
                                    hidden>
                                <div id="adminFilePreview" class="d-none"></div>

                                <div class="chat-input-wrapper">
                                    <button type="button" title="Gửi tệp"
                                        onclick="document.getElementById('adminChatFile').click()"><i
                                            class="bi bi-paperclip fs-5"></i></button>
                                    <button type="button" title="Chia sẻ vị trí" onclick="sendAdminLocation()"><i
                                            class="bi bi-geo-alt fs-5"></i></button>
                                    <button type="button" id="adminRecordBtn" title="Ghi âm"><i class="bi bi-mic fs-5"
                                            id="adminRecordIcon"></i></button>

                                    <input type="text" id="adminChatInput" class="form-control px-2"
                                        placeholder="Nhập tin nhắn..." autocomplete="off">

                                    <button class="btn btn-primary btn-send" type="submit">
                                        <i class="bi bi-send-fill"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-3 h-100 d-flex flex-column info-sidebar d-none d-md-flex">
                        <div id="customerInfoPanel" class="text-center mt-4" style="opacity: 0.3;">
                            <div class="info-avatar"><i class="bi bi-person"></i></div>
                            <h6 class="fw-bold text-dark">Thông tin người dùng</h6>
                            <p class="small text-muted mb-4">Chưa có thông tin</p>
                            <hr class="text-muted">
                            <div class="text-start mt-4">
                                <p class="small text-muted mb-1"><i class="bi bi-clock me-2"></i>Trạng thái: <span
                                        class="badge bg-secondary">Offline</span></p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>

<script>
    let currentSessionId = null;
    let apiUrl = '<?= ($_SESSION['user']['role'] === 'admin') ? 'admin.php' : 'manager.php' ?>';
    let adminMediaRecorder = null;
    let adminAudioChunks = [];
    let adminSelectedFile = null;

    // =========================================================================
    // KHỞI TẠO KẾT NỐI SOCKET.IO
    // =========================================================================
    // LƯU Ý: Thay cái wss:// dưới đây bằng link thật của Render
    const socket = io("wss://travelvn-socketserver.onrender.com");

    socket.on("connect", () => {
        console.log("🟢 Đã kết nối với máy chủ Realtime!");
    });

    socket.on("new_message", function (data) {
        if (data.session_id === currentSessionId) {
            if (data.sender_type !== 'admin' && data.sender_type !== 'tour_manager') {
                let content = data.message_type === 'location' ? data.message : (data.message || data.file_url);
                appendMessageUI(data.sender_type, content, data.message_type);
                fetch(apiUrl + '?action=markAsRead&session_id=' + data.session_id, { method: 'POST' });
            }
        }
        loadSessions();
    });

    function loadSessions() {
        fetch(apiUrl + '?action=getSessions')
            .then(res => res.json())
            .then(data => {
                const list = document.getElementById('sessionList');
                if (data.length === 0) { list.innerHTML = '<div class="text-center p-4 text-muted small">Hộp thư trống</div>'; return; }
                list.innerHTML = '';

                data.forEach(s => {
                    const isActive = s.session_id === currentSessionId ? 'active' : '';
                    const senderName = s.sender_name || 'Khách ẩn danh';
                    const avatarLetter = senderName.charAt(0).toUpperCase();
                    let unreadBadge = (s.unread_count > 0 && s.session_id !== currentSessionId) ? `<span class="badge bg-danger rounded-pill ms-2" style="font-size: 0.65rem; padding: 3px 6px;">${s.unread_count}</span>` : '';
                    const msgStyle = (s.unread_count > 0 && s.session_id !== currentSessionId) ? 'fw-bold text-dark' : 'text-muted';

                    let previewText = s.message;
                    if (!previewText) previewText = "📎 Đã gửi tệp đính kèm";
                    if (previewText.includes('http://googleusercontent.com/maps.google.com/')) previewText = "📍 Đã gửi vị trí";

                    list.innerHTML += `
                        <div class="session-item d-flex gap-3 align-items-center ${isActive}" onclick="openChat('${s.session_id}', '${senderName}')" id="session-box-${s.session_id}">
                            <div class="rounded-circle bg-light text-primary d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 40px; height: 40px;">${avatarLetter}</div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark small text-truncate" style="max-width: 60%;">${senderName}</span>
                                    <div class="d-flex align-items-center">
                                        <span style="font-size: 10px;" class="text-muted">${new Date(s.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</span>
                                        ${unreadBadge}
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small text-truncate ${msgStyle}" style="max-width: 80%;">${previewText}</span>
                                    <button class="btn btn-sm text-danger p-0 border-0" onclick="deleteChatSession('${s.session_id}', event)" title="Kết thúc hỗ trợ"><i class="bi bi-x-circle-fill" style="font-size: 14px;"></i></button>
                                </div>
                            </div>
                        </div>
                    `;
                });
            });
    }

    function openChat(sessionId, senderName) {
        currentSessionId = sessionId;
        socket.emit("join_room", sessionId);
        document.getElementById('adminChatForm').classList.remove('d-none');
        clearAdminFilePreview();

        const sessionBox = document.getElementById(`session-box-${sessionId}`);
        if (sessionBox) {
            const badge = sessionBox.querySelector('.bg-danger'); if (badge) badge.remove();
            const msgText = sessionBox.querySelector('.small.text-truncate');
            if (msgText) { msgText.classList.remove('fw-bold', 'text-dark'); msgText.classList.add('text-muted'); }
        }

        fetch(apiUrl + '?action=markAsRead&session_id=' + sessionId, { method: 'POST' });

        document.getElementById('chatHeader').innerHTML = `
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;"><i class="bi bi-person-fill"></i></div>
                <div><div class="fw-bold mb-0 lh-1">${senderName}</div><small class="text-success" style="font-size: 11px;">● Đang hỗ trợ</small></div>
            </div>
        `;

        let userBadge = sessionId.startsWith('user_') ? '<span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">Thành viên TravelVN</span>' : '<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill">Khách chưa đăng nhập</span>';
        const panel = document.getElementById('customerInfoPanel');
        panel.style.opacity = '1';
        panel.innerHTML = `
            <div class="info-avatar">${senderName.charAt(0).toUpperCase()}</div>
            <h6 class="fw-bold text-dark mb-2">${senderName}</h6>${userBadge}
            <hr class="text-muted mt-4">
            <div class="text-start mt-4">
                <p class="small text-muted mb-2"><i class="bi bi-circle-fill text-success me-2" style="font-size: 8px;"></i>Trạng thái: Trực tuyến</p>
                <p class="small text-muted mb-2"><i class="bi bi-hdd-network me-2"></i>Kênh: Website TravelVN</p>
            </div>
        `;

        fetch(apiUrl + '?action=getHistory&session_id=' + sessionId)
            .then(res => res.json())
            .then(data => {
                const body = document.getElementById('adminChatBody');
                body.innerHTML = '';
                data.forEach(msg => {
                    let content = msg.message_type === 'location' ? msg.message : (msg.message || msg.file_url);
                    appendMessageUI(msg.sender_type, content, msg.message_type);
                });
            });

        document.querySelectorAll('.session-item').forEach(el => el.classList.remove('active'));
        event.currentTarget.classList.add('active');
    }

    function appendMessageUI(type, content, messageType = 'text') {
        const body = document.getElementById('adminChatBody');
        const isMe = (type === 'admin' || type === 'tour_manager' || type === 'guide');
        const bubbleClass = isMe ? 'msg-me' : 'msg-customer';
        const alignClass = isMe ? 'justify-content-end' : 'justify-content-start';

        const loader = document.getElementById('admin-temp-loader');
        if (loader) loader.remove();

        if (!content || content === 'null' || content === 'undefined') { content = ''; }

        let finalContent = '';
        switch (messageType) {
            case 'image':
                finalContent = `<img src="${content}" class="chat-image" style="min-width: 150px; min-height: 100px; background: rgba(0,0,0,0.05); object-fit: cover;" onclick="window.open('${content}', '_blank')">`;
                break;
            case 'audio':
                finalContent = `<audio controls class="chat-audio" style="min-width: 240px; height: 45px;"><source src="${content}"></audio>`;
                break;
            case 'location':
                finalContent = `<a href="${content}" target="_blank" class="msg-location ${isMe ? 'text-white' : ''}"><i class="bi bi-geo-alt-fill"></i> Mở bản đồ định vị</a>`;
                break;
            case 'file':
                let fileName = content ? content.split('/').pop() : 'Tài_liệu_đính_kèm.ext';
                finalContent = `<div class="file-box"><i class="bi bi-file-earmark-text fs-5"></i> <a href="${content}" target="_blank" class="${isMe ? 'text-white' : ''}">${fileName}</a></div>`;
                break;
            default:
                finalContent = content || '<span style="opacity: 0.6; font-style: italic;">Nội dung bị lỗi hoặc trống</span>';
        }

        const msgHtml = `<div class="d-flex ${alignClass} mb-1"><div class="msg-bubble ${bubbleClass}">${finalContent}</div></div>`;
        body.insertAdjacentHTML('beforeend', msgHtml);
        body.scrollTop = body.scrollHeight;
    }

    function showAdminLoading() {
        const body = document.getElementById('adminChatBody');
        body.insertAdjacentHTML('beforeend', `<div id="admin-temp-loader" class="d-flex justify-content-end mb-1"><div class="msg-bubble msg-me temp-loader"><span></span><span></span><span></span></div></div>`);
        body.scrollTop = body.scrollHeight;
    }

    const adminChatFile = document.getElementById('adminChatFile');
    const adminFilePreview = document.getElementById('adminFilePreview');

    adminChatFile.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        adminSelectedFile = file;
        adminFilePreview.classList.remove('d-none');
        adminFilePreview.innerHTML = `<span><i class="bi bi-paperclip me-1"></i> ${file.name}</span> <i class="bi bi-x-circle text-danger" style="cursor:pointer" onclick="clearAdminFilePreview()"></i>`;
    });

    function clearAdminFilePreview() {
        adminSelectedFile = null;
        adminChatFile.value = '';
        adminFilePreview.innerHTML = '';
        adminFilePreview.classList.add('d-none');
    }

    document.getElementById('adminChatForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const input = document.getElementById('adminChatInput');
        const textMsg = input.value.trim();
        if (!currentSessionId) return;

        if (adminSelectedFile) {
            showAdminLoading();
            const formData = new FormData();
            formData.append('file', adminSelectedFile);
            formData.append('session_id', currentSessionId);
            clearAdminFilePreview();

            fetch(apiUrl + '?action=uploadFile', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    const loader = document.getElementById('admin-temp-loader');
                    if (loader) loader.remove();

                    if (data && data.url) {
                        let ext = data.url.split('.').pop().toLowerCase();
                        let type = data.type || (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext) ? 'image' : 'file');
                        appendMessageUI('admin', data.url, type);
                        if (data.message_data) socket.emit("send_message", data.message_data);
                    } else { alert("Gửi tệp thất bại: " + (data.error || 'Server không thể lưu file')); }
                })
                .catch(err => {
                    const loader = document.getElementById('admin-temp-loader');
                    if (loader) loader.remove();
                    alert("Lỗi kết nối máy chủ khi tải lên tệp!");
                });
        }
        else if (textMsg) {
            appendMessageUI('admin', textMsg, 'text');
            input.value = '';

            const formData = new FormData();
            formData.append('message', textMsg);
            formData.append('session_id', currentSessionId);

            fetch(apiUrl + '?action=sendMessage', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        socket.emit("send_message", data.user_message);
                    }
                });
        }
    });

    function sendAdminLocation() {
        if (navigator.geolocation && currentSessionId) {
            showAdminLoading();
            navigator.geolocation.getCurrentPosition(function (pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                const mapLink = `https://maps.google.com/?q=$${lat},${lng}`;
                appendMessageUI('admin', mapLink, 'location');

                fetch(apiUrl + '?action=sendLocation', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `latitude=${lat}&longitude=${lng}&session_id=${currentSessionId}`
                })
                    .then(res => res.json())
                    .then(data => {
                        const loader = document.getElementById('admin-temp-loader');
                        if (loader) loader.remove();
                        if (data.status === 'success') socket.emit("send_message", data.message_data);
                    });
            });
        }
    }

    const adminRecordBtn = document.getElementById('adminRecordBtn');
    const adminRecordIcon = document.getElementById('adminRecordIcon');

    adminRecordBtn.onclick = async () => {
        if (!currentSessionId) return;
        if (adminMediaRecorder && adminMediaRecorder.state === "recording") {
            adminMediaRecorder.stop();
            return;
        }
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            adminMediaRecorder = new MediaRecorder(stream);
            adminAudioChunks = [];
            adminMediaRecorder.ondataavailable = e => { adminAudioChunks.push(e.data); };
            adminMediaRecorder.onstop = () => {
                adminRecordBtn.classList.remove('recording');
                adminRecordIcon.className = 'bi bi-mic fs-5';
                showAdminLoading();

                const audioType = adminMediaRecorder.mimeType || 'audio/webm';
                const audioBlob = new Blob(adminAudioChunks, { type: audioType });

                const formData = new FormData();
                formData.append('voice', audioBlob, 'admin_voice');
                formData.append('session_id', currentSessionId);

                fetch(apiUrl + '?action=uploadVoice', { method: 'POST', body: formData })
                    .then(res => res.json())
                    .then(data => {
                        const loader = document.getElementById('admin-temp-loader');
                        if (loader) loader.remove();
                        if (data && data.url) {
                            appendMessageUI('admin', data.url, 'audio');
                            if (data.message_data) socket.emit("send_message", data.message_data);
                        } else { alert("Ghi âm thất bại: " + (data.error || "Lỗi server")); }
                    })
                    .catch(err => {
                        const loader = document.getElementById('admin-temp-loader');
                        if (loader) loader.remove();
                        alert("Lỗi kết nối khi gửi ghi âm!");
                    });
                stream.getTracks().forEach(track => track.stop());
            };
            adminMediaRecorder.start();
            adminRecordBtn.classList.add('recording');
            adminRecordIcon.className = 'bi bi-stop-fill fs-5';
        } catch (err) {
            alert('Không tìm thấy Microphone hoặc chưa cấp quyền!');
            console.error(err);
        }
    };

    function deleteChatSession(sessionId, e) {
        if (e) e.stopPropagation();
        if (!confirm('Xóa toàn bộ cuộc trò chuyện này?')) return;
        const formData = new FormData(); formData.append('session_id', sessionId);
        fetch(apiUrl + '?action=deleteSession', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    if (currentSessionId === sessionId) {
                        currentSessionId = null;
                        document.getElementById('adminChatForm').classList.add('d-none');
                        document.getElementById('chatHeader').innerHTML = `<span class="text-muted fw-normal"><i class="bi bi-chat-left-text me-2"></i>Chọn đoạn chat để bắt đầu</span>`;
                        document.getElementById('adminChatBody').innerHTML = `<div class="text-center my-auto text-muted"><p>Nội dung trống</p></div>`;
                        document.getElementById('customerInfoPanel').style.opacity = '0.3';
                    }
                    loadSessions();
                }
            });
    }

    loadSessions();
</script>

<?php include __DIR__ . "/../layouts/footer.php"; ?>