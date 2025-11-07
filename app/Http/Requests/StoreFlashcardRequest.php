<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFlashcardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
    * The URI that users should be redirected to if validation fails.
    *
    * @var string
    */
    protected $redirect = '/flashcards/new';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        function isQuestionUnique(string $question) {
            $flashcardsXML = app('flashcardsXML');

            $flashcards = $flashcardsXML->getElementsByTagName('flashcard');

            foreach ($flashcards as $flashcard) {
                if ($question == $flashcard->getElementsByTagName('question')->item(0)->nodeValue ?? '') {
                    return false;
                }
            }

            return true;
        }

        return [
            'question' => [
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (is_null($value)) {
                        $fail("Cannot be empty!");
                    }
                    
                    elseif (!isQuestionUnique($value)) {
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
