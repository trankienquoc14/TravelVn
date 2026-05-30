<?php include __DIR__ . '/../layouts/header.php'; ?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">

<style>
    :root {
        --guide-primary: #0ea5e9;
        --guide-bg: #f8fafc;
        --guide-card: #ffffff;
        --guide-border: #e2e8f0;
        --guide-text: #0f172a;
    }

    body {
        background-color: var(--guide-bg);
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .chat-widget {
        display: none !important;
    }

    .chat-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 15px;
    }

    .chat-card {
        background: var(--guide-card);
        border-radius: 24px;
        border: 1px solid var(--guide-border);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        height: 75vh;
        min-height: 500px;
        overflow: hidden;
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

    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* CSS TIN NHẮN CÁCH LY */
    .guide-msg-bubble {
        max-width: 80%;
        padding: 12px 18px;
        border-radius: 18px;
        font-size: 0.95rem;
        line-height: 1.5;
        position: relative;
        word-wrap: break-word;
    }

    .guide-msg-me {
        background: var(--guide-primary);
        color: white;
        border-bottom-right-radius: 4px;
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.2);
    }

    .guide-msg-customer {
        background: white;
        color: var(--guide-text);
        border-bottom-left-radius: 4px;
        border: 1px solid var(--guide-border);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .chat-input-wrapper {
        background: var(--guide-bg);
        border-radius: 20px;
        padding: 4px;
        border: 1px solid transparent;
        transition: all 0.3s ease;
    }

    .chat-input-wrapper:focus-within {
        border-color: var(--guide-primary);
        background: white;
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
    }

    .chat-input-wrapper button.tool-btn {
        background: transparent;
        border: none;
        color: #64748b;
        padding: 6px 10px;
        border-radius: 50%;
        transition: 0.2s;
    }

    .chat-input-wrapper button.tool-btn:hover {
        color: var(--guide-primary);
        background: #e2e8f0;
    }

    .session-item {
        transition: all 0.2s ease;
        border: none !important;
        border-left: 4px solid transparent !important;
        cursor: pointer;
    }

    .session-item:hover {
        background: #f8fafc;
    }

    .session-item.active-session {
        background: #f0f9ff;
        border-left: 4px solid var(--guide-primary) !important;
    }

    /* CSS Xử lý Responsive Mobile */
    @media (max-width: 767.98px) {
        .chat-card {
            height: 85vh !important;
        }

        .chat-card .col-md-4 {
            height: 35% !important;
            border-right: none !important;
            border-bottom: 2px solid var(--guide-border) !important;
        }

        .chat-card .col-md-8 {
            height: 65% !important;
        }
    }

    /* 🔥 CSS NÂNG CẤP MEDIA */
    .chat-image {
        max-width: 220px;
        border-radius: 12px;
        cursor: pointer;
        border: 2px solid var(--guide-border);
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

    .guide-msg-me .file-box a {
        color: white;
        text-decoration: underline;
    }

    .guide-msg-customer .file-box a {
        color: var(--guide-primary);
        text-decoration: none;
    }

    .msg-location {
        color: inherit;
        text-decoration: underline;
        font-weight: bold;
    }

    #guideFilePreview {
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

<div class="chat-container">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div
            style="background: #e0f2fe; color: #0ea5e9; width: 55px; height: 55px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem;">
            <i class="bi bi-chat-right-text-fill"></i>
        </div>
        <div>
            <h1 style="font-size: 1.8rem; font-weight: 800; color: #0f172a; margin: 0;">Trung tâm hỗ trợ khách đoàn</h1>
            <p class="text-muted mb-0">Trả lời nhanh các thắc mắc của khách hàng trong tour bạn phụ trách.</p>
        </div>
    </div>

    <div class="chat-card">
        <div class="row g-0 h-100">
            <div class="col-md-4 border-end d-flex flex-column bg-white h-100">
                <div class="p-3 border-bottom">
                    <div class="chat-input-wrapper d-flex align-items-center px-3 py-1" style="background: #f1f5f9;">
                        <i class="bi bi-search text-muted me-2"></i>
                        <input type="text" class="form-control border-0 shadow-none bg-transparent px-0"
                            placeholder="Tìm kiếm khách..." style="font-size: 0.95rem;">
                    </div>
                </div>
                <div class="list-group list-group-flush overflow-auto flex-grow-1" id="sessionList"
                    style="min-height: 0;">
                    <div class="text-center p-5 text-muted small">Đang tải danh sách khách hàng...</div>
                </div>
            </div>

            <div class="col-md-8 d-flex flex-column h-100" style="background: #f8fafc;">
                <div id="chatHeader" class="p-3 bg-white border-bottom d-flex align-items-center fw-bold shadow-sm z-1"
                    style="min-height: 70px; color: #0f172a;">
                    <span class="text-muted fw-normal small"><i class="bi bi-info-circle me-2"></i>Chọn một cuộc hội
                        thoại từ bên trái</span>
                </div>

                <div id="guideChatBody" class="p-4 flex-grow-1 overflow-auto d-flex flex-column gap-3"
                    style="min-height: 0;">
                    <div class="text-center my-auto">
                        <img src="https://cdn-icons-png.flaticon.com/512/4080/4080911.png"
                            style="width: 100px; opacity: 0.5;">
                        <p class="text-muted mt-3 small">Bắt đầu tư vấn cho khách hàng của bạn ngay bây giờ</p>
                    </div>
                </div>

                <div id="chatFooter" class="p-3 bg-white border-top d-none">
                    <form id="guideChatForm">
                        <input type="file" id="guideChatFile" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx" hidden>
                        <div id="guideFilePreview" class="d-none"></div>

                        <div class="chat-input-wrapper d-flex align-items-center ps-2 pe-1 py-1"
                            style="background: #f1f5f9;">
                            <button type="button" class="tool-btn" title="Gửi tệp"
                                onclick="document.getElementById('guideChatFile').click()"><i
                                    class="bi bi-paperclip fs-5"></i></button>
                            <button type="button" class="tool-btn" title="Chia sẻ vị trí"
                                onclick="sendGuideLocation()"><i class="bi bi-geo-alt fs-5"></i></button>
                            <button type="button" class="tool-btn" id="guideRecordBtn" title="Ghi âm"><i
                                    class="bi bi-mic fs-5" id="guideRecordIcon"></i></button>

                            <input type="text" id="guideChatInput"
                                class="form-control border-0 shadow-none bg-transparent px-2"
                                placeholder="Nhập nội dung trả lời khách..." autocomplete="off"
                                style="font-size: 0.95rem;">

                            <button class="btn btn-primary d-flex align-items-center justify-content-center ms-2"
                                type="submit" style="border-radius: 12px; width: 40px; height: 40px; min-width: 40px;">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>

<script>
    let currentSessionId = null;
    let guideMediaRecorder = null;
    let guideAudioChunks = [];
    let guideSelectedFile = null;

    // =========================================================================
    // KHỞI TẠO KẾT NỐI SOCKET.IO
    // =========================================================================
    const socket = io("wss://travelvn-socketserver.onrender.com"); // <-- Link máy chủ Render

    socket.on("connect", () => {
        console.log("🟢 Guide đã kết nối với máy chủ Realtime!");
    });

    socket.on("new_message", async function (data) {
        if (data.session_id === currentSessionId && data.sender_type === 'customer') {
            let content = data.message_type === 'location' ? data.message : (data.message || data.file_url);
            appendMessageUI(data.sender_type, content, data.message_type);
            await fetch('guide.php?action=markAsRead&session_id=' + data.session_id, { method: 'POST' });
        }
        loadSessions();
    });


    // 1. LOAD DANH SÁCH KHÁCH HÀNG (Giữ nguyên)
    function loadSessions() {
        fetch('guide.php?action=getSessions&t=' + new Date().getTime())
            .then(res => res.json())
            .then(data => {
                const list = document.getElementById('sessionList');
                if (data.length === 0) {
                    list.innerHTML = '<div class="text-center p-5 text-muted small"><i class="bi bi-chat-square-dots fs-1 d-block mb-2 opacity-50"></i>Chưa có khách hàng nào nhắn tin.</div>';
                    return;
                }
                list.innerHTML = '';
                data.forEach(s => {
                    const isActive = s.session_id === currentSessionId ? 'active-session' : '';
                    const unreadBadge = (s.unread_count > 0 && s.session_id !== currentSessionId)
                        ? `<span class="badge bg-danger rounded-pill" style="font-size: 0.65rem;">${s.unread_count}</span>`
                        : '';
                    const msgStyle = (s.unread_count > 0 && s.session_id !== currentSessionId) ? 'fw-bold text-dark' : 'text-muted';

                    let previewText = s.message;
                    if (!previewText) previewText = "📎 Đã gửi tệp đính kèm";
                    if (previewText.includes('http://googleusercontent.com/maps.google.com/')) previewText = "📍 Đã chia sẻ vị trí";

                    list.innerHTML += `
                        <button onclick="openChat('${s.session_id}', '${s.sender_name || 'Khách vãng lai'}')" 
                                class="list-group-item list-group-item-action p-3 session-item ${isActive}">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-primary small text-truncate" style="max-width: 60%;">${s.sender_name || 'Khách vãng lai'}</span>
                                <div class="d-flex align-items-center gap-1">
                                    <span style="font-size: 10px;" class="text-muted">${new Date(s.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</span>
                                    ${unreadBadge}
                                </div>
                            </div>
                            <div class="text-truncate mb-1 ${msgStyle}" style="font-size: 0.85rem; max-width: 200px;">${previewText}</div>
                            <div class="text-truncate" style="font-size: 0.75rem; color: #10b981;">
                                <i class="bi bi-geo-alt-fill me-1"></i>${s.tour_name || 'Đang hỗ trợ'}
                            </div>
                        </button>`;
                });
            });
    }

    // 2. MỞ ĐOẠN CHAT
    async function openChat(sessionId, senderName) {
        currentSessionId = sessionId;

        // Báo cho máy chủ Socket biết Guide vừa chui vào phòng (session_id) này
        socket.emit("join_room", sessionId);

        document.getElementById('chatFooter').classList.remove('d-none');
        clearGuideFilePreview();
        document.getElementById('chatHeader').innerHTML = `<i class="bi bi-person-circle me-2 text-primary"></i> Đang hỗ trợ: ${senderName}`;

        try {
            await fetch('guide.php?action=markAsRead&session_id=' + sessionId, { method: 'POST' });

            const res = await fetch(`guide.php?action=getHistory&session_id=${sessionId}&t=` + new Date().getTime());
            const data = await res.json();

            const body = document.getElementById('guideChatBody');
            body.innerHTML = '';
            data.forEach(msg => {
                let content = msg.message_type === 'location' ? msg.message : (msg.message || msg.file_url);
                appendMessageUI(msg.sender_type, content, msg.message_type);
            });
            body.scrollTop = body.scrollHeight;

            loadSessions();
        } catch (error) { console.error('Lỗi khi mở đoạn chat:', error); }
    }

    // 3. VẼ TIN NHẮN THEO ĐỊNH DẠNG (FILE, AUDIO, MAP) (Giữ nguyên)
    function appendMessageUI(type, content, messageType = 'text') {
        const body = document.getElementById('guideChatBody');
        const isMe = (type !== 'customer');

        const loader = document.getElementById('guide-temp-loader');
        if (loader) loader.remove();

        if (!content || content === 'null' || content === 'undefined') {
            content = '';
        }

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
                finalContent = content || '<span style="opacity: 0.6; font-style: italic;">Nội dung bị trống</span>';
        }

        const msgHtml = `
            <div class="d-flex ${isMe ? 'justify-content-end' : 'justify-content-start'} mb-1">
                <div class="guide-msg-bubble ${isMe ? 'guide-msg-me' : 'guide-msg-customer'}">
                    ${finalContent}
                </div>
            </div>`;
        body.insertAdjacentHTML('beforeend', msgHtml);
        body.scrollTop = body.scrollHeight;
    }

    function showGuideLoading() {
        const body = document.getElementById('guideChatBody');
        body.insertAdjacentHTML('beforeend', `<div id="guide-temp-loader" class="d-flex justify-content-end mb-1"><div class="guide-msg-bubble guide-msg-me temp-loader"><span></span><span></span><span></span></div></div>`);
        body.scrollTop = body.scrollHeight;
    }

    // 4. XỬ LÝ PREVIEW & UPLOAD TỆP ĐÍNH KÈM (Giữ nguyên)
    const guideChatFile = document.getElementById('guideChatFile');
    const guideFilePreview = document.getElementById('guideFilePreview');

    guideChatFile.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        guideSelectedFile = file;
        guideFilePreview.classList.remove('d-none');
        guideFilePreview.innerHTML = `<span><i class="bi bi-paperclip me-1"></i> ${file.name}</span> <i class="bi bi-x-circle text-danger" style="cursor:pointer" onclick="clearGuideFilePreview()"></i>`;
    });

    function clearGuideFilePreview() {
        guideSelectedFile = null;
        guideChatFile.value = '';
        guideFilePreview.innerHTML = '';
        guideFilePreview.classList.add('d-none');
    }

    // =========================================================================
    // 5. GỬI TIN NHẮN VÀ PHÁT QUA SOCKET.IO
    // =========================================================================
    document.getElementById('guideChatForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const input = document.getElementById('guideChatInput');
        const textMsg = input.value.trim();
        if (!currentSessionId) return;

        // Trạng thái 1: Guide gửi file
        if (guideSelectedFile) {
            showGuideLoading();
            const formData = new FormData();
            formData.append('file', guideSelectedFile);
            formData.append('session_id', currentSessionId);
            clearGuideFilePreview();

            fetch('guide.php?action=uploadFile', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    const loader = document.getElementById('guide-temp-loader');
                    if (loader) loader.remove();

                    if (data && data.url) {
                        let ext = data.url.split('.').pop().toLowerCase();
                        let type = data.type || (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext) ? 'image' : 'file');

                        appendMessageUI('guide', data.url, type);

                        // 🔥 Bắn tín hiệu qua Socket.io
                        if (data.message_data) socket.emit("send_message", data.message_data);

                        loadSessions();
                    } else {
                        alert("Gửi tệp thất bại: " + (data.error || "Lỗi server"));
                    }
                })
                .catch(err => {
                    const loader = document.getElementById('guide-temp-loader');
                    if (loader) loader.remove();
                    alert("Lỗi kết nối máy chủ khi gửi tệp!");
                });
        }
        // Trạng thái 2: Guide gửi chữ
        else if (textMsg) {
            appendMessageUI('guide', textMsg, 'text');
            input.value = '';

            const formData = new FormData();
            formData.append('message', textMsg);
            formData.append('session_id', currentSessionId);

            fetch('guide.php?action=sendMessage', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        // 🔥 Bắn tín hiệu qua Socket.io
                        socket.emit("send_message", data.user_message);
                    }
                });
        }
    });

    // 6. GỬI VỊ TRÍ QUA SOCKET.IO
    function sendGuideLocation() {
        if (navigator.geolocation && currentSessionId) {
            showGuideLoading();
            navigator.geolocation.getCurrentPosition(function (pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                const mapLink = `https://maps.google.com/?q=$${lat},${lng}`;
                appendMessageUI('guide', mapLink, 'location');

                fetch('guide.php?action=sendLocation', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `latitude=${lat}&longitude=${lng}&session_id=${currentSessionId}`
                })
                    .then(res => res.json())
                    .then(data => {
                        const loader = document.getElementById('guide-temp-loader');
                        if (loader) loader.remove();
                        // 🔥 Bắn tín hiệu qua Socket.io
                        if (data.status === 'success') socket.emit("send_message", data.message_data);
                        loadSessions();
                    });
            });
        }
    }

    // 7. GHI ÂM (VOICE) VÀ GỬI QUA SOCKET.IO
    const guideRecordBtn = document.getElementById('guideRecordBtn');
    const guideRecordIcon = document.getElementById('guideRecordIcon');

    guideRecordBtn.onclick = async () => {
        if (!currentSessionId) return;
        if (guideMediaRecorder && guideMediaRecorder.state === "recording") {
            guideMediaRecorder.stop();
            return;
        }
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            guideMediaRecorder = new MediaRecorder(stream);
            guideAudioChunks = [];
            guideMediaRecorder.ondataavailable = e => { guideAudioChunks.push(e.data); };
            guideMediaRecorder.onstop = () => {
                guideRecordBtn.classList.remove('recording');
                guideRecordIcon.className = 'bi bi-mic fs-5';
                showGuideLoading();

                const audioType = guideMediaRecorder.mimeType || 'audio/webm';
                const audioBlob = new Blob(guideAudioChunks, { type: audioType });

                const formData = new FormData();
                formData.append('voice', audioBlob, 'guide_voice');
                formData.append('session_id', currentSessionId);

                fetch('guide.php?action=uploadVoice', { method: 'POST', body: formData })
                    .then(res => res.json())
                    .then(data => {
                        const loader = document.getElementById('guide-temp-loader');
                        if (loader) loader.remove();

                        if (data && data.url) {
                            appendMessageUI('guide', data.url, 'audio');

                            // 🔥 Bắn tín hiệu qua Socket.io
                            if (data.message_data) socket.emit("send_message", data.message_data);

                            loadSessions();
                        } else {
                            alert("Ghi âm thất bại: " + (data.error || "Lỗi server"));
                        }
                    })
                    .catch(err => {
                        const loader = document.getElementById('guide-temp-loader');
                        if (loader) loader.remove();
                        alert("Lỗi kết nối khi gửi ghi âm!");
                    });

                stream.getTracks().forEach(track => track.stop());
            };

            guideMediaRecorder.start();
            guideRecordBtn.classList.add('recording');
            guideRecordIcon.className = 'bi bi-stop-fill fs-5';
        } catch (err) {
            alert('Lỗi truy cập Microphone! Vui lòng cấp quyền.');
        }
    };

    loadSessions();
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>