<?php

namespace App\Http\Controllers;

use App\Models\AudioGeneration;
use App\Models\NotebooklmGeneration;
use Illuminate\Support\Facades\Storage;

class AudioController extends Controller
{
    public function stream(AudioGeneration $audio)
    {
        $audio->recordPlay();
        $path = $this->resolvePath($audio->audio_file_path);

        if (!$path) {
            abort(404, 'Audio file not found');
        }

        return response()->file($path, [
            'Content-Type' => 'audio/mpeg',
        ]);
    }

    public function download(AudioGeneration $audio)
    {
        $audio->recordDownload();
        $path = $this->resolvePath($audio->audio_file_path);

        if (!$path) {
            abort(404, 'Audio file not found');
        }

        return response()->download($path, $audio->title . '.mp3');
    }

    public function streamNotebooklm(NotebooklmGeneration $notebooklmGen)
    {
        $notebooklmGen->recordPlay();
        $path = $this->resolvePath($notebooklmGen->audio_file_path);

        if (!$path) {
            abort(404, 'Audio file not found');
        }

        return response()->file($path, [
            'Content-Type' => 'audio/mpeg',
        ]);
    }

    public function downloadNotebooklm(NotebooklmGeneration $notebooklmGen)
    {
        $notebooklmGen->recordDownload();
        $path = $this->resolvePath($notebooklmGen->audio_file_path);

        if (!$path) {
            abort(404, 'Audio file not found');
        }

        return response()->download($path, $notebooklmGen->title . '.mp3');
    }

    private function resolvePath(?string $relativePath): ?string
    {
        if (!$relativePath) {
            return null;
        }

        if (Storage::disk('local')->exists($relativePath)) {
            return Storage::disk('local')->path($relativePath);
        }

        $directPath = storage_path('app/' . $relativePath);
        if (file_exists($directPath)) {
            return $directPath;
        }

        return null;
    }
}