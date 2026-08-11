<?php

namespace App\Livewire;

use App\Models\LearningMaterials;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LearningMaterialUpload extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public ?LearningMaterials $selectedMaterial = null;
    public bool $showPreviewModal = false;

    public function getStudentId(): string
    {
        return auth()->user()?->student?->student_id ?? (string) auth()->id();
    }

    public function getMaterialsProperty()
    {
        return LearningMaterials::with('summary')
            ->where('student_id', $this->getStudentId())
            ->latest()
            ->get();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                LearningMaterials::query()
                    ->with('summary')
                    ->where('student_id', $this->getStudentId())
                    ->latest()
            )
            ->columns([
                TextColumn::make('title')
                    ->label('Material Title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (LearningMaterials $record) => $record->description ?: 'No description provided'),

                TextColumn::make('file_type')
                    ->label('Format')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => strtoupper($state))
                    ->color('info')
                    ->description(fn (LearningMaterials $record) => $record->original_filename),

                TextColumn::make('simplification_status')
                    ->label('AI Status')
                    ->badge()
                    ->colors([
                        'success' => 'completed',
                        'amber' => fn ($state) => in_array($state, ['pending', 'processing']),
                        'danger' => 'failed',
                    ])
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'completed' => 'Completed',
                        'processing' => 'Processing AI...',
                        'pending' => 'Queued',
                        'failed' => 'Failed',
                        default => ucfirst($state),
                    }),

                TextColumn::make('simplification_percentage')
                    ->label('Word Reduction')
                    ->formatStateUsing(function ($state, LearningMaterials $record) {
                        if ($record->simplification_status !== 'completed') {
                            return '-';
                        }
                        return $state !== null ? '-' . number_format($state, 1) . '%' : 'N/A';
                    })
                    ->badge()
                    ->color('success')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Uploaded At')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('quiz')
                        ->label('AI Quiz')
                        ->icon('heroicon-m-sparkles')
                        ->color('purple')
                        ->visible(fn (LearningMaterials $record) => $record->simplification_status === 'completed')
                        ->url(fn (LearningMaterials $record) => route('materials.quiz-generator', $record->id)),

                    Action::make('audio')
                        ->label('Generate Audio')
                        ->icon('heroicon-m-speaker-wave')
                        ->color('warning')
                        ->url(fn (LearningMaterials $record) => route('audio-generator') . '?material=' . $record->id),

                    Action::make('preview')
                        ->label('Preview')
                        ->icon('heroicon-m-eye')
                        ->color('info')
                        ->action(fn (LearningMaterials $record) => $this->openPreviewModal($record->id)),

                    Action::make('downloadPdf')
                        ->label('PDF Report')
                        ->icon('heroicon-m-document-arrow-down')
                        ->color('success')
                        ->visible(fn (LearningMaterials $record) => $record->simplification_status === 'completed')
                        ->action(fn (LearningMaterials $record) => $this->downloadPdf($record->id)),

                    Action::make('delete')
                        ->label('Delete')
                        ->icon('heroicon-m-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Delete Learning Material')
                        ->modalDescription('Are you sure you want to delete this material and its generated summary?')
                        ->action(fn (LearningMaterials $record) => $this->deleteMaterial($record->id)),
                ])
                ->label('Options')
                ->icon('heroicon-m-ellipsis-vertical')
                ->color('primary')
                ->button()
            ])
            ->emptyStateHeading('No Simplified Materials Yet')
            ->emptyStateDescription('Upload a lecture document above to start AI simplification and summary generation.')
            ->emptyStateIcon('heroicon-o-document-text');
    }

    public function openPreviewModal($materialId)
    {
        $this->selectedMaterial = LearningMaterials::with('summary')->find($materialId);
        if ($this->selectedMaterial) {
            $this->showPreviewModal = true;
        }
    }

    public function downloadPdf($materialId)
    {
        $material = LearningMaterials::with('summary')->where('student_id', $this->getStudentId())->findOrFail($materialId);

        if (!$material->simplified_text) {
            session()->flash('error', 'Simplified content is not available yet.');
            return;
        }

        try {
            $pdfService = new \App\Services\PdfGenerationService();
            $pdf = $pdfService->generateSimplifiedPdf($material);

            $pdfFilename = 'simplified_' . $material->id . '_' . time() . '.pdf';
            $pdfRelativePath = 'simplified-materials/' . $pdfFilename;

            Storage::disk('local')->put($pdfRelativePath, $pdf->output());

            $material->update([
                'simplified_pdf_path' => $pdfRelativePath,
            ]);

            return response()->streamDownload(
                fn() => print($pdf->output()),
                'Simplified_' . \Illuminate\Support\Str::slug($material->title) . '.pdf'
            );
        } catch (\Exception $e) {
            Log::error('PDF generation error on download: ' . $e->getMessage());

            if ($material->simplified_pdf_path && Storage::disk('local')->exists($material->simplified_pdf_path)) {
                return Storage::disk('local')->download(
                    $material->simplified_pdf_path,
                    'Simplified_' . \Illuminate\Support\Str::slug($material->title) . '.pdf'
                );
            }

            session()->flash('error', 'Unable to generate PDF report: ' . $e->getMessage());
        }
    }

    public function deleteMaterial($materialId)
    {
        $material = LearningMaterials::where('student_id', $this->getStudentId())->find($materialId);

        if ($material) {
            if ($material->original_file_path && Storage::disk('local')->exists($material->original_file_path)) {
                Storage::disk('local')->delete($material->original_file_path);
            }
            if ($material->simplified_pdf_path && Storage::disk('local')->exists($material->simplified_pdf_path)) {
                Storage::disk('local')->delete($material->simplified_pdf_path);
            }
            $material->delete();

            session()->flash('success', 'Learning material deleted successfully.');
        }
    }

    public function render()
    {
        return view('livewire.learning-material-upload', [
            'materials' => $this->materials,
        ]);
    }
}
