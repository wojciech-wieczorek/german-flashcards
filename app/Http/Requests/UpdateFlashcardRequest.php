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
        $requestIndex = $this->route('index');

        $isQuestionUnique = function (string $question) use ($requestIndex) {
            $flashcardsXML = app('flashcardsXML');

            $flashcards = $flashcardsXML->getElementsByTagName('flashcard');

            foreach ($flashcards as $index => $flashcard) {
                if($index == $requestIndex) {
                    continue;
                }
                
                if ($question == $flashcard->getElementsByTagName('question')->item(0)->nodeValue ?? '') {
                    return false;
                }
            }

            return true;
        };

        return [
            'question' => [
                function (string $attribute, mixed $value, \Closure $fail) use ($isQuestionUnique) {
                    if (is_null($value)) {
                        $fail("Cannot be empty!");
                    }
                    
                    elseif (! $isQuestionUnique($value)) {
                        $fail("Flashcard '{$value}' already exists!");
                    }
                }
            ],
            'answer' => [
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (is_null($value)) {
                        $fail("Cannot be empty!");
                    }
                }
            ]
        ];
    }
}
