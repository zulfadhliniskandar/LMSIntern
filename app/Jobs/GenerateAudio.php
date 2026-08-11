<?php

namespace App\Jobs;

use App\Models\AudioGeneration;
use App\Services\EdgeTtsService;
use App\Services\AudioScriptGeneratorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateAudio implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public $timeout = 360;
    public $tries = 2;

    /**
     * Create a new job instance.
     */
    public function __construct(private AudioGeneration $audioGeneration)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->audioGeneration->update(['status' => 'processing']);

            // 1. Send extracted text to Gemini AI to generate a polished audio narration script
            $scriptService = new AudioScriptGeneratorService();
            $aiScript = $scriptService->generateScript(
                $this->audioGeneration->text_input,
                $this->audioGeneration->style,
                $this->audioGeneration->language ?? 'en-US',
                $this->audioGeneration->generation_mode ?? 'solo'
            );

            $this->audioGeneration->update([
                'ai_script' => $aiScript,
            ]);

            // 2. Synthesize audio speech from the AI-refined script using Edge TTS
            $ttsService = new EdgeTtsService();
            $result = $ttsService->generate(
                $aiScript,
                $this->audioGeneration->voice_name,
                $this->audioGeneration->style,
                $this->audioGeneration->speech_rate,
                $this->audioGeneration->pitch,
                $this->audioGeneration->generation_mode ?? 'solo',
                $this->audioGeneration->language ?? 'en-US'
            );

            $this->audioGeneration->update([
                'status' => 'completed',
                'audio_file_path' => $result['file_path'],
                'file_size' => $result['file_size'],
                'audio_duration' => $result['duration'],
            ]);
        } catch (\Exception $e) {
            $this->audioGeneration->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            Log::error('Audio generation failed: ' . $e->getMessage());
        }
    }

    public function failed(\Throwable $exception)
    {
        $this->audioGeneration->update([
            'status' => 'failed',
            'error_message' => $exception->getMessage(),
        ]);
    }
}
