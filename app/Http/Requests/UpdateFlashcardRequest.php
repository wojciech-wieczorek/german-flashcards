<?php

namespace App\Http\Requests;

use App\Data\Flashcard;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFlashcardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'question' => [
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (! Flashcard::isUnique($value)) {
                        $fail("Flashcard '{$value}' already exists!");
                    }
                },
            ],
        ];
    }
}
