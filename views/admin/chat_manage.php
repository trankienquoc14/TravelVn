<script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>

<script>
    let currentSessionId = null;
    let apiUrl = '<?= ($_SESSION['user']['role'] === 'admin') ? 'admin.php' : 'manager.php' ?>';
    let adminMediaRecorder = null;
    let adminAudioChunks = [];
    let adminSelectedFile = null;

    // =========================================================================
    // KHỞI TẠO KẾT NỐI SOCKET.IO (THAY THẾ PUSHER)
    // =========================================================================
    const socket = io("wss://travelvn-socketserver.onrender.com"); // <-- Link máy chủ Render của bạn

    // Lắng nghe sự kiện kết nối thành công (Tùy chọn: Để log kiểm tra)
    socket.on("connect", () => {
        console.log("🟢 Đã kết nối với máy chủ Realtime!");
    });

    // Lắng nghe tin nhắn mới từ Socket.io
    socket.on("new_message", function (data) {
        // Chỉ vẽ tin nhắn ra màn hình nếu đang mở đúng phòng chat đó
        if (data.session_id === currentSessionId) {
            // Không tự vẽ lại tin nhắn của chính mình (vì ajax gửi thành công đã tự vẽ rồi)
            if (data.sender_type !== 'admin' && data.sender_type !== 'tour_manager') {
                let content = data.message_type === 'location' ? data.message : (data.message || data.file_url);
                appendMessageUI(data.sender_type, content, data.message_type);
                fetch(apiUrl + '?action=markAsRead&session_id=' + data.session_id, { method: 'POST' });
            }
        }
        // Cập nhật lại danh sách hộp thư bên trái (để nhảy tin nhắn mới nhất lên đầu)
        loadSessions();
    });


    // 1. TẢI HỘP THƯ (Giữ nguyên)
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

    // 2. MỞ KHUNG CHAT
    function openChat(sessionId, senderName) {
        currentSessionId = sessionId;

        // Báo cho máy chủ Socket biết Admin vừa chui vào phòng (session_id) này
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

    // 3. VẼ TIN NHẮN THEO ĐỊNH DẠNG (Giữ nguyên)
    function appendMessageUI(type, content, messageType = 'text') {
        const body = document.getElementById('adminChatBody');
        const isMe = (type === 'admin' || type === 'tour_manager' || type === 'guide');
        const bubbleClass = isMe ? 'msg-me' : 'msg-customer';
        const alignClass = isMe ? 'justify-content-end' : 'justify-content-start';

        const loader = document.getElementById('admin-temp-loader');
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

    // 4. PREVIEW VÀ UPLOAD FILE (Giữ nguyên)
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

    // =========================================================================
    // 5. GỬI TIN NHẮN VÀ PHÁT QUA SOCKET.IO
    // =========================================================================
    document.getElementById('adminChatForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const input = document.getElementById('adminChatInput');
        const textMsg = input.value.trim();
        if (!currentSessionId) return;

        // Trạng thái 1: Admin gửi file / ảnh đính kèm
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

                        // 🔥 Bắn tín hiệu qua Socket.io
                        if (data.message_data) socket.emit("send_message", data.message_data);

                    } else {
                        alert("Gửi tệp thất bại: " + (data.error || 'Server không thể lưu file'));
                    }
                })
                .catch(err => {
                    const loader = document.getElementById('admin-temp-loader');
                    if (loader) loader.remove();
                    alert("Lỗi kết nối máy chủ khi tải lên tệp!");
                });
        }
        // Trạng thái 2: Admin gửi chữ (Text)
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
                        // 🔥 Bắn tín hiệu qua Socket.io
                        socket.emit("send_message", data.user_message);
                    }
                });
        }
    });

    // 6. GỬI ĐỊNH VỊ QUA SOCKET.IO
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
                        // 🔥 Bắn tín hiệu qua Socket.io
                        if (data.status === 'success') socket.emit("send_message", data.message_data);
                    });
            });
        }
    }

    // 7. GHI ÂM VÀ GỬI QUA SOCKET.IO
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
                            // 🔥 Bắn tín hiệu qua Socket.io
                            if (data.message_data) socket.emit("send_message", data.message_data);
                        } else {
                            alert("Ghi âm thất bại: " + (data.error || "Lỗi server"));
                        }
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

    // 8. XÓA CUỘC TRÒ CHUYỆN (Giữ nguyên)
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