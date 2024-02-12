<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxBorrowItem implements ValidationRule
{
    public $allowed;
    public $borrowed;

    public function __construct($allowed, $borrowed)
    {
        $this->allowed = $allowed;
        $this->borrowed = $borrowed;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (count($value) > $this->allowed) {
            $fail("You have been borrowing {$this->borrowed} books, you can only borrow {$this->allowed} books remaining.");
        }
    }
}
