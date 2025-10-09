<?php

namespace App\Http\Controllers;

use App\Data\Flashcard;
use App\Http\Requests\StoreFlashcardRequest;
use App\Http\Requests\UpdateFlashcardRequest;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class FlashcardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flashcardsFileContents = Storage::disk('local')->get('deutsch.xml');

        $flashcardsXML = new \DOMDocument('1.0', 'UTF-8');
        $flashcardsXML->loadXML($flashcardsFileContents);

        $flashcards = $flashcardsXML->getElementsByTagName('flashcard');
        $output = [];

        foreach ($flashcards as $index => $flashcard) {
            $box = $flashcard->getElementsByTagName('box')->item(0)->nodeValue ?? '';
            $question = $flashcard->getElementsByTagName('question')->item(0)->nodeValue ?? '';
            $answer = $flashcard->getElementsByTagName('answer')->item(0)->nodeValue ?? '';

            $output[] = ['box' => $box, 'question' => $question, 'answer' => $answer, 'index' => $index];
        }

        return Inertia::render('Collection', ['flashcards' => $output]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFlashcardRequest $request)
    {
        $flashcardsXML = new \DOMDocument('1.0', 'UTF-8');
        $flashcardsXML->preserveWhiteSpace = false;
        $flashcardsXML->formatOutput = true;

        if (Storage::disk('local')->exists('deutsch.xml')) {
            $flashcardsFileContents = Storage::disk('local')->get('deutsch.xml');
            $flashcardsXML->loadXML($flashcardsFileContents);
            $flashcards = $flashcardsXML->documentElement;

        } else {
            $flashcards = $flashcardsXML->createElement('flashcards');
            $flashcardsXML->appendChild($flashcards);
        }

        $flashcard = $flashcardsXML->createElement('flashcard');

        $box = $flashcardsXML->createElement('box', '1');
        $question = $flashcardsXML->createElement('question', htmlspecialchars($request->input('question')));
        $answer = $flashcardsXML->createElement('answer', htmlspecialchars($request->input('answer')));

        $flashcard->appendChild($box);
        $flashcard->appendChild($question);
        $flashcard->appendChild($answer);
        $flashcards->appendChild($flashcard);

        Storage::disk('local')->put('deutsch.xml', $flashcardsXML->saveXML());

        return Inertia::render('Create', ['message' => "\"{$request->input('question')}\" flashcard was added successfully."]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $index)
    {
        $flashcardsFileContents = Storage::disk('local')->get('deutsch.xml');

        $flashcardsXML = new \DOMDocument('1.0', 'UTF-8');
        $flashcardsXML->loadXML($flashcardsFileContents);

        $flashcards = $flashcardsXML->getElementsByTagName('flashcard');
        $flashcard = $flashcards[$index];

        $question = $flashcard->getElementsByTagName('question')->item(0)->nodeValue;
        $answer = $flashcard->getElementsByTagName('answer')->item(0)->nodeValue;
        $box = $flashcard->getElementsByTagName('box')->item(0)->nodeValue;

        return ['index' => $index, 'question' => $question, 'answer' => $answer, 'box' => $box];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $index)
    {
        return Inertia::render('subpages/FlashcardModifyForm', ['flashcard' => $this->show($index)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFlashcardRequest $request, int $index)
    {
        $flashcardsXML = new \DOMDocument('1.0', 'UTF-8');
        $flashcardsXML->preserveWhiteSpace = false;
        $flashcardsXML->formatOutput = true;

        $flashcardsFileContents = Storage::disk('local')->get('deutsch.xml');
        $flashcardsXML->loadXML($flashcardsFileContents);
        $flashcards = $flashcardsXML->documentElement;

        $flashcard = $flashcards->getElementsByTagName('flashcard')->item($index);

        foreach ($request->except(['index']) as $key => $value) {
            $flashcard->getElementsByTagName($key)->item(0)->nodeValue = $value;
        }

        Storage::disk('local')->put('deutsch.xml', $flashcardsXML->saveXML());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $index)
    {
        Flashcard::destroy($index);

        return $this->index();
    }
}
