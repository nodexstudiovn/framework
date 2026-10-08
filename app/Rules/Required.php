<?php

namespace App\Rules;

class Required implements Rule
{
    public function passes(string $attribute, mixed $value): bool
    {
        if (is_null($value)) {
            return false;
        }

        if (is_string($value) && trim($value) === '') {
            return false;
        }

        if (is_array($value) && count($value) === 0) {
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return "The :attribute field is required.";
    }
}
