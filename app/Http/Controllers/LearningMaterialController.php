<?php

namespace App\Http\Controllers;

use App\Models\LearningMaterials;
use App\Jobs\ProcessLearningMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LearningMaterialController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:20480',
            'title' => 'required|string|max:200',
            'description' => 'nullable|string|max:500',
        ], [
            'file.required' => 'Please select a document file to upload.',
            'file.max' => 'Maximum file size allowed is 20MB.',
            'title.required' => 'Please enter a title for your learning material.',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());

        if (!in_array($ext, ['pdf', 'doc', 'docx', 'txt', 'pptx', 'ppt'])) {
            return back()->withErrors(['file' => 'Supported formats: PDF, DOC, DOCX, TXT, PPTX.'])->withInput();
        }

        $studentId = auth()->user()?->student?->student_id ?? (string) auth()->id();

        try {
            $filePath = $file->store('learning-materials', 'local');

            $material = LearningMaterials::create([
                'student_id' => $studentId,
                'title' => $request->title,
                'description' => $request->description ?: null,
                'original_file_path' => $filePath,
                'original_filename' => $file->getClientOriginalName(),
                'file_type' => $ext,
                'file_size' => $file->getSize(),
                'simplification_status' => 'processing',
            ]);

            // Dispatch to background queue so HTTP request completes instantly
            ProcessLearningMaterial::dispatch($material);

            return redirect()->route('simplification-service')
                ->with('success', 'Learning material "' . $request->title . '" uploaded! AI simplification is processing in the background.');

        } catch (\Exception $e) {
            Log::error('Upload/Simplification Exception: ' . $e->getMessage());
            return redirect()->route('simplification-service')
                ->with('error', 'Processing failed: ' . $e->getMessage());
        }
    }
}
