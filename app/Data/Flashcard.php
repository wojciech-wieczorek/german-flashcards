<?php

namespace App\Data;

use Illuminate\Support\Facades\Storage;

class Flashcard
{
    public static function isUnique(string $question)
    {
        $flashcardsFileContents = Storage::disk('local')->get('deutsch.xml');

        $flashcardsXML = new \DOMDocument('1.0', 'UTF-8');
        $flashcardsXML->loadXML($flashcardsFileContents);

        $flashcards = $flashcardsXML->getElementsByTagName('flashcard');

        foreach ($flashcards as $flashcard) {
            if ($question == $flashcard->getElementsByTagName('question')->item(0)->nodeValue ?? '') {
                return false;
            }
        }

        return true;
    }

    public static function destroy(int $index)
    {
        $flashcardsFileContents = Storage::disk('local')->get('deutsch.xml');

        $flashcardsXML = new \DOMDocument('1.0', 'UTF-8');
        $flashcardsXML->loadXML($flashcardsFileContents);

        $flashcards = $flashcardsXML->getElementsByTagName('flashcard');

        foreach ($flashcards as $i => $flashcard) {
            if ($index == $i) {
                $flashcard->remove();
                Storage::disk('local')->put('deutsch.xml', $flashcardsXML->saveXML());

                return;
            }
        }

        report('Flashcard not found.');
    }

    public static function getBoxByNumber(int $box, bool $shuffle = true)
    {
        $flashcardsFileContents = Storage::disk('local')->get('deutsch.xml');

        $flashcardsXML = new \DOMDocument('1.0', 'UTF-8');
        $flashcardsXML->loadXML($flashcardsFileContents);

        $flashcards = $flashcardsXML->getElementsByTagName('flashcard');
        $output = [];

        foreach ($flashcards as $index => $flashcard) {
            if ($box == $flashcard->getElementsByTagName('box')->item(0)->nodeValue) {
                $question = $flashcard->getElementsByTagName('question')->item(0)->nodeValue ?? '';
                $answer = $flashcard->getElementsByTagName('answer')->item(0)->nodeValue ?? '';

                $output[] = ['box' => $box, 'question' => $question, 'answer' => $answer, 'index' => $index];
            }
        }

        if ($shuffle) {
            shuffle($output);
        }

        return $output;
    }
}
