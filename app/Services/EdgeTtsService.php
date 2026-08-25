<?php

namespace App\Services;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class EdgeTtsService
{
    private $voices = [
        'ms-MY' => [
            'male' => [
                'name' => 'ms-MY-OsmanNeural',
                'display' => 'Osman (Malaysian)',
                'styles' => ['default', 'newscast', 'instruction', 'cheering', 'customer-service']
            ],
            'female' => [
                'name' => 'ms-MY-YasminNeural',
                'display' => 'Yasmin (Malaysian)',
                'styles' => ['default', 'newscast', 'instruction', 'cheering', 'customer-service']
            ]
        ],
        'en-US' => [
            'female' => [
                'name' => 'en-US-JennyNeural',
                'display' => 'Jenny (American)',
                'styles' => ['default', 'newscast', 'instruction', 'cheering', 'customer-service']
            ],
            'male' => [
                'name' => 'en-US-GuyNeural',
                'display' => 'Guy (American)',
                'styles' => ['default', 'newscast', 'instruction', 'cheering', 'customer-service']
            ]
        ],

        'en-GB' => [
            'female' => [
                'name' => 'en-GB-SoniaNeural',
                'display' => 'Sonia (British)',
                'styles' => ['default', 'newscast', 'instruction', 'cheering', 'customer-service']
            ],
            'male' => [
                'name' => 'en-GB-ThomasNeural',
                'display' => 'Thomas (British)',
                'styles' => ['default', 'newscast', 'instruction', 'cheering', 'customer-service']
            ]
        ],

        'en-AU' => [
            'female' => [
                'name' => 'en-AU-NatashaNeural',
                'display' => 'Natasha (Australian)',
                'styles' => ['default', 'newscast']
            ],
            'male' => [
                'name' => 'en-AU-KenNeural',
                'display' => 'Ken (Australian)',
                'styles' => ['default', 'newscast']
            ]
        ],

        'en-CA' => [
            'female' => [
                'name' => 'en-CA-ClaraNeural',
                'display' => 'Clara (Canadian)',
                'styles' => ['default', 'newscast', 'instruction', 'cheering', 'customer-service']
            ],
            'male' => [
                'name' => 'en-CA-LiamNeural',
                'display' => 'Liam (Canadian)',
                'styles' => ['default', 'newscast', 'instruction', 'cheering', 'customer-service']
            ]
        ],
    ];

    private $style = [
        'default' => 'Natural and neutral tone',
        'newscast' => 'Professional news presenter style',
        'lecture' => 'Educational lecture tone',
        'instruction' => 'Clear instructional tone',
        'cheering' => 'Enthusiastic and motivational tone',
        'customer-service' => 'Friendly customer service tone'
    ];

    public function getAvailableVoices()
    {
        return $this->voices;
    }

    public function getVoice($language = 'en-US', $gender = 'female')
    {
        return $this->voices[$language][$gender] ?? null;
    }

    public function getStyles()
    {
        return $this->style;
    }

    public function getStyleForVoice($language = 'en-US', $gender = 'female')
    {
        $voice = $this->getVoice($language, $gender);
        if (!$voice) return [];
        $styles = [];
        foreach ($voice['styles'] as $style) {
            $styles[$style] = $this->style[$style] ?? ucfirst($style);
        }
        return $styles;
    }

    public function generate($text, $voiceName, $style = 'default', $rate = 1.0, $pitch = 0, $generationMode = 'solo', $language = 'en-US')
    {
        if (!$text) {
            throw new \Exception("Text cannot be empty");
        }

        if (mb_strlen($text) > 50000) {
            throw new \Exception("Text too long (maximum 50,000 characters)");
        }

        $filename = 'audio_' . time() . '_' . uniqid() . '.mp3';
        $relative_path = 'audio-files/' . $filename;
        $output_path = storage_path('app/' . $relative_path);

        @mkdir(dirname($output_path), 0775, true);

        $scriptService = new AudioScriptGeneratorService();
        $text = $scriptService->cleanTextForTts($text);

        try {
            $result = $this->callPythonScript([
                'text' => $text,
                'output_path' => $output_path,
                'voice_name' => $voiceName,
                'rate' => (float) $rate,
                'style' => $style,
                'generation_mode' => $generationMode,
                'language' => $language,
            ]);

            if (empty($result['success'])) {
                throw new \Exception('TTS Error: ' . ($result['error'] ?? 'Unknown error'));
            }

            if (!file_exists($output_path) || filesize($output_path) === 0) {
                throw new \Exception('Output audio file is missing or empty.');
            }

            return [
                'file_path' => $relative_path,
                'full_path' => $output_path,
                'duration' => $this->getAudioDuration($output_path),
                'file_size' => filesize($output_path),
                'voice_name' => $voiceName,
                'style' => $style,
            ];

        } catch (\Exception $exception) {
            if (file_exists($output_path)) {
                @unlink($output_path);
            }

            throw $exception;
        }
    }

    private function callPythonScript($args)
    {
        $pythonScript = base_path('storage/scripts/generate_tts.py');
        $pythonPath = config('services.python.path', PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3');

        if (PHP_OS_FAMILY === 'Windows' && strtolower($pythonPath) === 'python3') {
            $pythonPath = 'python';
        }

        $process = new Process([
            $pythonPath,
            $pythonScript,
        ]);

        $process->setInput(json_encode($args));
        $process->setTimeout(300);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $output = trim($process->getOutput());

        try {
            return json_decode($output, true);
        } catch (\Exception $e) {
            throw new \Exception("Invalid JSON output from Python script: " . $output);
        }
    }

    private function getAudioDuration($file_path)
    {
        $process = new Process([
            'ffprobe',
            '-v', 'error',
            '-show_entries',
            'format=duration',
            '-of',
            'default=noprint_wrappers=1:nokey=1',
            $file_path
        ]);

        try {
            $process->run();
            $duration = (int) $process->getOutput();
            return max(1, $duration);
        } catch (\Exception $e) {
            return null;
        }
    }
}
