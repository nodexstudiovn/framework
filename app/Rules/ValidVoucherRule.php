<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Validation Rule: ValidVoucherRule
 */

namespace App\Rules;

class ValidVoucherRule implements Rule
{
    public function passes(string $attribute, mixed $value): bool
    {
        return !empty($value);
    }

    public function message(): string
    {
        return "The :attribute validation failed.";
    }
}