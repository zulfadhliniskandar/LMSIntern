<?php

namespace App\Jobs;

use App\Models\NotebooklmGeneration;
use App\Services\NotebookLmService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateNotebooklmAudio implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public $timeout = 1200;
    public $tries = 2;

    public function __construct(private NotebooklmGeneration $notebooklmGen)
    {}

    public function handle(): void
    {
        try {
            $this->notebooklmGen->update(['status' => 'processing']);

            $service = new NotebookLmService();
            $result = $service->generate(
                $this->notebooklmGen->text_input,
                $this->notebooklmGen->title
            );

            $this->notebooklmGen->update([
                'status' => 'completed',
                'audio_file_path' => $result['file_path'],
                'file_size' => $result['file_size'],
                'notebook_id' => $result['notebook_id'],
            ]);

        } catch (\Exception $e) {
            $this->notebooklmGen->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            Log::error('NotebookLM Audio Overview generation failed: ' . $e->getMessage());
        }
    }

    public function failed(\Throwable $exception)
    {
        $this->notebooklmGen->update([
            'status' => 'failed',
            'error_message' => $exception->getMessage(),
        ]);
    }
}
