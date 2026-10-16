<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Strict View Hygiene Inspection Engine
 */

namespace NodeX\View;

use NodeX\View\Exceptions\ViewHygieneViolationException;

class ViewHygieneChecker
{
    /**
     * Thao tác quét và đối soát quy chuẩn vệ sinh View ở tầng ứng dụng.
     * CẤM TUYỆT ĐỐI: Mã PHP thuần (<?php, <?=, @php), inline <style>, inline <script> trong sub-views, và inline event handler attributes (onclick=, style=).
     */
    public static function check(string $filePath, string $rawContent): void
    {
        $lines = explode("\n", $rawContent);
        $normalizedPath = str_replace('\\', '/', $filePath);
        $isLayout = str_contains($normalizedPath, 'layouts/');

        foreach ($lines as $index => $line) {
            $lineNumber = $index + 1;
            $trimmed = trim($line);

            // 1. Kiểm tra mã PHP thuần (<?php, <?=, <% hoặc directive @php)
            if (preg_match('/<\?php|<\?=|<%|@php/i', $line)) {
                throw new ViewHygieneViolationException(
                    "🚨 VI PHẠM QUY CHUẨN VIEW HYGIENE: Phát hiện mã PHP thuần ('{$trimmed}') tại dòng {$lineNumber} trong file view [{$filePath}]. " .
                    "NodeX Framework nghiêm cấm tuyệt đối viết PHP thuần trong View. 100% Business Logic phải xử lý ở Action/Controller."
                );
            }

            // 2. Kiểm tra thẻ <style> inline
            if (preg_match('/<style[\s>]/i', $line)) {
                throw new ViewHygieneViolationException(
                    "🚨 VI PHẠM QUY CHUẨN VIEW HYGIENE: Phát hiện thẻ inline <style> tại dòng {$lineNumber} trong file view [{$filePath}]. " .
                    "NodeX Framework cấm viết CSS inline trong Blade View. Vui lòng sử dụng Tailwind CSS hoặc tách module CSS trong resources/css/."
                );
            }

            // 3. Kiểm tra thẻ <script> inline trong sub-view (chỉ layout chính mới được phép nạp script CDN/Module)
            if (!$isLayout && preg_match('/<script[\s>]/i', $line) && !str_contains($line, '@vite')) {
                throw new ViewHygieneViolationException(
                    "🚨 VI PHẠM QUY CHUẨN VIEW HYGIENE: Phát hiện thẻ inline <script> tại dòng {$lineNumber} trong sub-view [{$filePath}]. " .
                    "NodeX Framework cấm viết JS inline trong file sub-view. Vui lòng tách JS module trong resources/js/modules/ và import qua Vite."
                );
            }

            // 4. Kiểm tra các thuộc tính inline style và event handlers thuần HTML (onclick=, onload=, style=)
            if (preg_match('/\b(onclick|onload|onchange|onsubmit|onkeyup|onmouseover|style)\s*=/i', $line) && !$isLayout) {
                throw new ViewHygieneViolationException(
                    "🚨 VI PHẠM QUY CHUẨN VIEW HYGIENE: Phát hiện thuộc tính inline style/event handler ('{$trimmed}') tại dòng {$lineNumber} trong file [{$filePath}]. " .
                    "Vui lòng sử dụng Tailwind CSS và lắng nghe Event bằng JS module tách biệt."
                );
            }
        }
    }
}
