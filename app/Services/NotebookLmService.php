<?php

namespace App\Services;

use Exception;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Log;

class NotebookLmService
{
    private string $pythonPath;
    private string $scriptPath;

    public function __construct()
    {
        $path = config('services.python.path', env('PYTHON_PATH', PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3'));
        if (PHP_OS_FAMILY === 'Windows' && strtolower($path) === 'python3') {
            $path = 'python';
        }
        $this->pythonPath = $path;
        $this->scriptPath = storage_path('scripts/notebooklm_tts.py');
    }

    public function generate(string $text, string $title = 'NotebookLM Audio Overview', ?string $storagePath = null): array
    {
        if (empty(trim($text))) {
            throw new Exception("Source text cannot be empty.");
        }

        $filename = 'notebooklm_' . time() . '_' . uniqid() . '.mp3';
        $relative_path = 'audio-files/' . $filename;
        $output_path = storage_path('app/' . $relative_path);

        @mkdir(dirname($output_path), 0775, true);

        $defaultProfileJson = env('USERPROFILE') ? env('USERPROFILE') . '/.notebooklm/profiles/default/storage_state.json' : null;
        $appAuthJson = storage_path('app/notebooklm_auth/storage_state.json');

        if ($defaultProfileJson && file_exists($defaultProfileJson)) {
            @mkdir(dirname($appAuthJson), 0775, true);
            @copy($defaultProfileJson, $appAuthJson);
        }

        $scriptService = new AudioScriptGeneratorService();
        $text = $scriptService->cleanTextForTts($text);

        $payload = [
            'text' => $text,
            'title' => $title,
            'output_path' => $output_path,
            'storage_path' => file_exists($appAuthJson) ? $appAuthJson : null,
        ];

        $process = new Process([$this->pythonPath, $this->scriptPath]);
        $process->setInput(json_encode($payload));
        $process->setTimeout(1200);
        $process->run();

        if (!$process->isSuccessful() && (PHP_OS_FAMILY === 'Windows' || strtolower($this->pythonPath) === 'python3')) {
            $process = new Process(['python', $this->scriptPath]);
            $process->setInput(json_encode($payload));
            $process->setTimeout(1200);
            $process->run();
        }

        if (!$process->isSuccessful()) {
            $err = trim($process->getErrorOutput() ?: $process->getOutput());
            throw new Exception("NotebookLM Process Error: " . ($err ?: 'Unknown process failure'));
        }

        $output = trim($process->getOutput());
        $result = json_decode($output, true);

        if (!$result || empty($result['success'])) {
            throw new Exception('NotebookLM Error: ' . ($result['error'] ?? 'Failed to generate audio overview. Make sure NotebookLM is authenticated.'));
        }

        if (!file_exists($output_path) || filesize($output_path) === 0) {
            throw new Exception('Downloaded NotebookLM audio file is missing or empty.');
        }

        return [
            'file_path' => $relative_path,
            'full_path' => $output_path,
            'file_size' => $this->formatFileSize(filesize($output_path)),
            'notebook_id' => $result['notebook_id'] ?? null,
            'duration' => null,
        ];
    }

    private function formatFileSize(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }
}
