<?php

namespace App\Http\Controllers;

use App\Data\Flashcard;
use DOMDocument;
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
        $flashcardsFileContents = Storage::disk('local')->get('deutsch.xml');

        $flashcardsXML = new DOMDocument('1.0', 'UTF-8');
        $flashcardsXML->loadXML($flashcardsFileContents);

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
    public function show(Request $request, string $box)
    {
        return Inertia::render('subpages/BoxReview', ['flashcards' => Flashcard::getBoxByNumber($box)]);

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
