/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Realtime Chat Frontend Module
 */

export function initChatModule() {
    const chatBox = document.getElementById('chat-messages');
    const chatForm = document.getElementById('chat-form');
    const input = document.getElementById('message-input');
    const userNameInput = document.getElementById('user-name-input');
    const statusBadge = document.getElementById('ws-status-badge');

    if (!chatBox || !chatForm) return;

    // 1. Tải lịch sử tin nhắn ban đầu qua REST API
    loadMessageHistory();

    // 2. Kết nối tới NodeX Realtime WebSocket Server (Port 6001)
    let ws = null;

    function connectWebSocket() {
        try {
            ws = new WebSocket('ws://localhost:6001');

            ws.onopen = () => {
                if (statusBadge) {
                    statusBadge.className = 'px-3 py-1 rounded-full text-xs font-mono bg-emerald-500/10 border border-emerald-500/30 text-emerald-400';
                    statusBadge.textContent = '🟢 Realtime Active (Port 6001)';
                }
            };

            ws.onmessage = (event) => {
                try {
                    const payload = JSON.parse(event.data);
                    if (payload.event === 'new-message' && payload.data) {
                        appendMessage(payload.data);
                    }
                } catch (e) {
                    console.error('Lỗi parse tin nhắn WebSocket:', e);
                }
            };

            ws.onclose = () => {
                if (statusBadge) {
                    statusBadge.className = 'px-3 py-1 rounded-full text-xs font-mono bg-amber-500/10 border border-amber-500/30 text-amber-400';
                    statusBadge.textContent = '🟡 Reconnecting WebSocket...';
                }
                setTimeout(connectWebSocket, 3000);
            };

            ws.onerror = () => {
                if (statusBadge) {
                    statusBadge.className = 'px-3 py-1 rounded-full text-xs font-mono bg-red-500/10 border border-red-500/30 text-red-400';
                    statusBadge.textContent = '🔴 WS Offline (Micro-Reverb Standby)';
                }
            };
        } catch (err) {
            console.warn('Không thể khởi tạo kết nối WebSocket:', err);
        }
    }

    connectWebSocket();

    // 3. Xử lý gửi tin nhắn mới
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const content = input.value.trim();
        const userName = userNameInput ? userNameInput.value.trim() : 'Khách';

        if (!content) return;

        // Lấy CSRF token từ meta tag
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

        input.value = '';

        try {
            const response = await fetch('/api/chat/messages', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    user_name: userName,
                    content: content
                })
            });

            const result = await response.json();
            if (result.error) {
                alert('Lỗi gửi tin nhắn: ' + result.error.message);
            }
        } catch (err) {
            console.error('Lỗi khi gọi API gửi tin nhắn:', err);
        }
    });

    // Helper: Tải lịch sử tin nhắn
    async function loadMessageHistory() {
        try {
            const res = await fetch('/api/chat/messages');
            const result = await res.json();

            if (result.status === 'success' && Array.isArray(result.data)) {
                chatBox.innerHTML = '';
                if (result.data.length === 0) {
                    chatBox.innerHTML = '<div class="text-center text-xs text-zinc-400 font-mono py-8">Chưa có tin nhắn nào. Hãy là người đầu tiên trò chuyện!</div>';
                    return;
                }
                result.data.forEach(msg => appendMessage(msg));
            }
        } catch (err) {
            chatBox.innerHTML = '<div class="text-center text-xs text-red-400 font-mono py-8">Không thể kết nối CSDL tải tin nhắn cũ.</div>';
        }
    }

    // Helper: Thêm tin nhắn mới vào giao diện
    function appendMessage(msg) {
        // Xóa thông báo trống nếu có
        if (chatBox.children.length === 1 && chatBox.children[0].classList.contains('text-center')) {
            chatBox.innerHTML = '';
        }

        $div = document.createElement('div');
        $div.className = 'p-3.5 bg-zinc-900 border border-zinc-800 rounded-xl space-y-1 hover:border-zinc-700 transition-colors';
        $div.innerHTML = `
            <div class="flex items-center justify-between text-xs font-mono">
                <span class="font-semibold text-zinc-200">${escapeHtml(msg.user_name || msg.username || 'Khách')}</span>
                <span class="text-[11px] text-zinc-400">${escapeHtml(msg.created_at || 'Just now')}</span>
            </div>
            <p class="text-sm text-zinc-300 font-sans leading-relaxed">${escapeHtml(msg.content)}</p>
        `;
        chatBox.appendChild($div);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function escapeHtml(str) {
        return String(str).replace(/[&<>"']/g, (m) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        })[m]);
    }
}
