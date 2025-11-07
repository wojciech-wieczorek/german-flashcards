<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFlashcardRequest;
use App\Http\Requests\UpdateFlashcardRequest;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Http\Request;

class FlashcardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $flashcardsXML = app('flashcardsXML');

        $flashcards = $flashcardsXML->getElementsByTagName('flashcard');
        $output = [];

        $page = intval($request->query('page', 1));
        $maxPages = ceil($flashcards->length / 15);

        foreach ($flashcards as $index => $flashcard) {
            if ($index < ($page - 1) * 15) {
                continue;
            } elseif ($index >= $page * 15) {
                break;
            }

            $box = $flashcard->getElementsByTagName('box')->item(0)->nodeValue ?? '';
            $question = $flashcard->getElementsByTagName('question')->item(0)->nodeValue ?? '';
            $answer = $flashcard->getElementsByTagName('answer')->item(0)->nodeValue ?? '';

            $output[] = ['box' => $box, 'question' => $question, 'answer' => $answer, 'index' => $index];
        }


        return Inertia::render('Collection', ['flashcards' => $output, 'page' => $page, 'maxPages' => $maxPages]);
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
        $flashcardsXML = app('flashcardsXML');

        $flashcard = $flashcardsXML->createElement('flashcard');

        $box = $flashcardsXML->createElement('box', '1');
        $question = $flashcardsXML->createElement('question', htmlspecialchars($request->input('question')));
        $answer = $flashcardsXML->createElement('answer', htmlspecialchars($request->input('answer')));

        $flashcard->appendChild($box);
        $flashcard->appendChild($question);
        $flashcard->appendChild($answer);

        $flashcardsXML->documentElement->appendChild($flashcard);

        Storage::disk('local')->put(config('database_xml.filename'), $flashcardsXML->saveXML());

        return Inertia::render('Create', ['message' => "\"{$request->input('question')}\" flashcard was added successfully."]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $index)
    {
        $flashcardsXML = app('flashcardsXML');

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
        $flashcardsXML = app('flashcardsXML');

        $flashcard = $flashcardsXML->documentElement->getElementsByTagName('flashcard')->item($index);

        foreach ($request->except(['index']) as $key => $value) {
            $flashcard->getElementsByTagName($key)->item(0)->nodeValue = $value;
        }

        Storage::disk('local')->put(config('database_xml.filename'), $flashcardsXML->saveXML());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $index)
    {
        $flashcardsXML = app('flashcardsXML');

        $flashcards = $flashcardsXML->getElementsByTagName('flashcard');

        foreach ($flashcards as $i => $flashcard) {
            if ($index == $i) {
                $flashcard->remove();
                Storage::disk('local')->put(config('database_xml.filename'), $flashcardsXML->saveXML());

                return redirect('/flashcards');
            }
        }

        report('Flashcard not found.');

        return redirect('/flashcards');
    }
}
