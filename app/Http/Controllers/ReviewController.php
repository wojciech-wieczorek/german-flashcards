<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flashcardsXML = app('flashcardsXML');

        $flashcards = $flashcardsXML->getElementsByTagName('flashcard');
        $boxSizes = [];

        foreach ($flashcards as $flashcard) {
            $box = $flashcard->getElementsByTagName('box')->item(0)->nodeValue;
            isset($boxSizes[$box]) ? $boxSizes[$box] += 1 : $boxSizes[$box] = 1;
        }

        return Inertia::render('Review', ['boxSizes' => $boxSizes]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $box, bool $shuffle = true)
    {
        $flashcardsXML = app('flashcardsXML');

        $flashcards = $flashcardsXML->getElementsByTagName('flashcard');
        $boxFlashcards = [];

        foreach ($flashcards as $index => $flashcard) {
            if ($box == $flashcard->getElementsByTagName('box')->item(0)->nodeValue) {
                $question = $flashcard->getElementsByTagName('question')->item(0)->nodeValue ?? '';
                $answer = $flashcard->getElementsByTagName('answer')->item(0)->nodeValue ?? '';

                $boxFlashcards[] = ['box' => $box, 'question' => $question, 'answer' => $answer, 'index' => $index];
            }
        }

        if ($shuffle) {
            shuffle($boxFlashcards);
        }

        return Inertia::render('subpages/BoxReview', ['flashcards' => $boxFlashcards]);

        // if ($request->method()) {
        //     return Inertia::render('subpages/BoxReview');
        // } else {
        //     return redirect()->back();
        // }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
