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