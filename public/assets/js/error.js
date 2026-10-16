/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * External Error & Exception Interactive Script
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Chuyển đổi Tabs trong giao diện Exception Inspector
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-tab');

            tabButtons.forEach(b => {
                b.classList.remove('text-white', 'border-indigo-500');
                b.classList.add('text-slate-400', 'border-transparent');
            });

            btn.classList.add('text-white', 'border-indigo-500');
            btn.classList.remove('text-slate-400', 'border-transparent');

            tabContents.forEach(c => c.classList.add('hidden'));
            if (targetId) {
                document.getElementById(targetId)?.classList.remove('hidden');
            }
        });
    });

    // 2. Nút sao chép chi tiết ngoại lệ vào Clipboard
    const copyBtn = document.getElementById('copyErrorBtn');
    if (copyBtn) {
        copyBtn.addEventListener('click', () => {
            const title = document.querySelector('h1')?.innerText || '';
            const fileInfo = document.querySelector('span.text-rose-400')?.innerText || '';
            const textToCopy = `${title}\nLocation: ${fileInfo}`;

            navigator.clipboard.writeText(textToCopy).then(() => {
                const originalHtml = copyBtn.innerHTML;
                copyBtn.innerHTML = '<span class="text-emerald-400 font-bold">✔ Đã sao chép!</span>';
                setTimeout(() => {
                    copyBtn.innerHTML = originalHtml;
                }, 2000);
            });
        });
    }

    // 3. Nút tải lại trang (trên giao diện lỗi 500)
    const reloadBtn = document.getElementById('reloadBtn');
    if (reloadBtn) {
        reloadBtn.addEventListener('click', () => {
            window.location.reload();
        });
    }

    // 4. Nút quay lại trang trước (trên giao diện lỗi 404)
    const backBtn = document.getElementById('backBtn');
    if (backBtn) {
        backBtn.addEventListener('click', () => {
            window.history.back();
        });
    }
});
