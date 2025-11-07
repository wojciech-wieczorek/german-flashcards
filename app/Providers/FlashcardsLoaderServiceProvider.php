<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class FlashcardsLoaderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('flashcardsXML', function ($flashcardsXML) {
            $flashcardsXML = new \DOMDocument('1.0', 'UTF-8');
            $flashcardsXML->preserveWhiteSpace = false;
            $flashcardsXML->formatOutput = true;

            if (Storage::disk('local')->exists(config('database_xml.filename'))) {
                $flashcardsFileContents = Storage::disk('local')->get(config('database_xml.filename'));
                $flashcardsXML->loadXML($flashcardsFileContents);
            } else {
                $flashcards = $flashcardsXML->createElement('flashcards');
                $flashcardsXML->appendChild($flashcards);

                Storage::disk('local')->put(config('database_xml.filename'), $flashcardsXML->saveXML());
            }

            return $flashcardsXML;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
