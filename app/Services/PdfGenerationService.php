<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class PdfGenerationService
{
    public function generateSimplifiedPdf($learningMaterial)
    {
        $html = $this->generateHtml($learningMaterial);

        $pdf = Pdf::loadHTML($html)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('isPhpEnabled', true);

        $pdf->setPaper('a4', 'portrait');

        return $pdf;
    }

    private function generateHtml($learningMaterial)
    {
        $summary = $learningMaterial->summary;
        $title = htmlspecialchars($learningMaterial->title ?? 'Learning Material');
        $originalFilename = htmlspecialchars($learningMaterial->original_filename ?? 'Document');
        $generatedDate = date('M d, Y H:i');

        $origWords = (int) ($learningMaterial->original_word_count ?? 0);
        $simpWords = (int) ($learningMaterial->simplified_word_count ?? 0);
        $origLevel = htmlspecialchars($learningMaterial->original_reading_level ?? 'Advanced');
        $simpLevel = htmlspecialchars($learningMaterial->simplified_reading_level ?? 'High School');
        $origScore = (float) ($learningMaterial->original_readability_score ?? 45.0);
        $simpScore = (float) ($learningMaterial->simplified_readability_score ?? 75.0);
        $percentage = (float) ($learningMaterial->simplification_percentage ?? 0.0);

        $keyPointsList = '';
        if ($summary && !empty($summary->key_points)) {
            $keyPointsArray = is_array($summary->key_points) ? $summary->key_points : json_decode($summary->key_points, true);
            if (is_array($keyPointsArray)) {
                foreach ($keyPointsArray as $point) {
                    $keyPointsList .= '<li>' . htmlspecialchars($point) . '</li>';
                }
            }
        }

        $keyPointsSection = '';
        if (!empty($keyPointsList)) {
            $keyPointsSection = <<<KEYPOINTS
    <!-- Key Points Section -->
    <div class="section-title">Key Takeaways & Core Ideas</div>
    <div class="key-points-card">
        <ul class="key-points-list">
            {$keyPointsList}
        </ul>
    </div>
KEYPOINTS;
        }

        $summaryText = $summary ? Str::markdown($summary->summary) : '';

        $paragraphsHtml = $this->formatParagraphs($learningMaterial->simplified_text);

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{$title} - Simplified Learning Material</title>
    <style>
        @page {
            margin: 35px 35px;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.6;
            color: #1e293b;
            font-size: 13px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 15px;
            margin-bottom: 22px;
        }
        .header-title {
            color: #4f46e5;
            font-size: 22px;
            font-weight: bold;
            margin: 0;
        }
        .header-meta {
            font-size: 11px;
            color: #64748b;
            text-align: right;
        }
        .report-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px;
            margin-bottom: 22px;
        }
        .report-title {
            font-size: 13px;
            font-weight: bold;
            color: #334155;
            margin-bottom: 10px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 5px;
        }
        .stats-table {
            width: 100%;
            border-collapse: collapse;
        }
        .stats-table td {
            width: 50%;
            vertical-align: top;
            padding: 4px 8px;
        }
        .stats-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 12px;
        }
        .stats-card-title {
            font-weight: bold;
            font-size: 12px;
            color: #4f46e5;
            margin-bottom: 6px;
        }
        .stat-row {
            margin-bottom: 4px;
            font-size: 11px;
        }
        .stat-label {
            color: #64748b;
        }
        .stat-val {
            font-weight: bold;
            color: #0f172a;
        }
        .badge-green {
            background-color: #10b981;
            color: #ffffff;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-indigo {
            background-color: #6366f1;
            color: #ffffff;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }
        .section-title {
            color: #1e1b4b;
            font-size: 15px;
            font-weight: bold;
            margin-top: 22px;
            margin-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 5px;
        }
        .summary-box {
            background-color: #f0f9ff;
            border-left: 4px solid #0284c7;
            padding: 10px 14px;
            border-radius: 0 6px 6px 0;
            font-size: 12px;
            color: #0c4a6e;
            margin-bottom: 18px;
        }
        .summary-box p {
            margin: 0 0 8px 0;
        }
        .summary-box p:last-child {
            margin-bottom: 0;
        }
        .key-points-card {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-left: 4px solid #10b981;
            border-radius: 0 8px 8px 0;
            padding: 12px 16px;
            margin-bottom: 20px;
        }
        .key-points-list {
            margin: 6px 0 0 0;
            padding-left: 20px;
            color: #166534;
        }
        .key-points-list li {
            margin-bottom: 6px;
            line-height: 1.5;
            font-size: 12px;
        }
        .content-body {
            font-size: 12.5px;
            line-height: 1.65;
            color: #0f172a;
        }
        .content-body p {
            margin-bottom: 12px;
            text-align: justify;
        }
        .content-body h2, .content-body h3, .content-body h4 {
            color: #3730a3;
            margin-top: 18px;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .content-body h2 {
            font-size: 16px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
        }
        .content-body h3 {
            font-size: 14px;
            border-left: 3px solid #6366f1;
            padding-left: 8px;
            background-color: #f5f3ff;
            padding-top: 4px;
            padding-bottom: 4px;
            border-radius: 0 4px 4px 0;
        }
        .content-body h4 {
            font-size: 13px;
        }
        .content-body ul {
            margin: 8px 0 14px 10px;
            padding-left: 18px;
            list-style-type: disc;
        }
        .content-body ol {
            margin: 8px 0 14px 10px;
            padding-left: 18px;
        }
        .content-body li {
            margin-bottom: 6px;
            line-height: 1.6;
            color: #1e293b;
        }
        .content-body strong, .content-body b {
            color: #312e81;
            font-weight: bold;
        }
        .content-body blockquote {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            margin: 12px 0;
            padding: 10px 14px;
            border-radius: 0 6px 6px 0;
            color: #1e40af;
            font-size: 12px;
        }
        .content-body blockquote p {
            margin: 0;
        }
        .footer {
            margin-top: 35px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            font-size: 10px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <table class="header-table">
        <tr>
            <td>
                <div class="header-title">Simplified Learning Material</div>
                <div style="font-size: 14px; font-weight: bold; color: #334155; margin-top: 4px;">{$title}</div>
            </td>
            <td class="header-meta">
                <strong>Source:</strong> {$originalFilename}<br>
                <strong>Generated:</strong> {$generatedDate}
            </td>
        </tr>
    </table>

    <!-- Readability & Simplification Summary Report -->
    <div class="report-box">
        <div class="report-title">Simplification Metrics & Readability Analysis</div>
        <table class="stats-table">
            <tr>
                <td>
                    <div class="stats-card">
                        <div class="stats-card-title">Original Document</div>
                        <div class="stat-row">
                            <span class="stat-label">Word Count:</span>
                            <span class="stat-val">{$origWords} words</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Reading Level:</span>
                            <span class="badge-indigo">{$origLevel}</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Readability Score:</span>
                            <span class="stat-val">{$origScore} / 100</span>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="stats-card">
                        <div class="stats-card-title">Simplified Version</div>
                        <div class="stat-row">
                            <span class="stat-label">Word Count:</span>
                            <span class="stat-val">{$simpWords} words</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Reading Level:</span>
                            <span class="badge-green">{$simpLevel}</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Readability Score:</span>
                            <span class="stat-val">{$simpScore} / 100</span>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <div style="text-align: center; margin-top: 10px; font-size: 11px; color: #059669; font-weight: bold;">
            Simplification Reduction: {$percentage}% shorter & clearer
        </div>
    </div>

    <!-- Summary Section -->
    <div class="section-title">Summary & Key Concepts</div>
    <div class="summary-box">
        {$summaryText}
    </div>

    {$keyPointsSection}

    <!-- Simplified Content -->
    <div class="section-title">Simplified Study Content</div>
    <div class="content-body">
        {$paragraphsHtml}
    </div>

    <!-- Footer -->
    <div class="footer">
        Generated automatically by Learning Management System Text Simplification Engine.<br>
        Designed to assist student learning and enhance reading comprehension.
    </div>
</body>
</html>
HTML;
    }

    private function formatParagraphs(?string $text): string
    {
        if (empty($text)) {
            return '<p><em>No simplified text available.</em></p>';
        }

        // Normalize unicode bullets to markdown bullets
        $normalized = str_replace(['•', '‣', '⁃'], '-', $text);

        // If text contains Markdown list or heading syntax, render with Str::markdown
        if (preg_match('/^[\s\-\*\d\.\#\>]/m', $normalized) || str_contains($normalized, '**') || str_contains($normalized, '###')) {
            return Str::markdown($normalized);
        }

        // Fallback: Format plain text into bulleted list if multiple lines start with bullet markers
        $paragraphs = explode("\n\n", $normalized);
        $html = '';

        foreach ($paragraphs as $para) {
            $trimmed = trim($para);
            if (empty($trimmed)) continue;

            $lines = explode("\n", $trimmed);
            if (count($lines) > 1) {
                $isList = true;
                foreach ($lines as $line) {
                    if (!preg_match('/^[\s\-\*\d\.]+/u', trim($line))) {
                        $isList = false;
                        break;
                    }
                }
                if ($isList) {
                    $html .= '<ul>';
                    foreach ($lines as $line) {
                        $item = trim(preg_replace('/^[\s\-\*\d\.]+/u', '', $line));
                        $html .= '<li>' . htmlspecialchars($item) . '</li>';
                    }
                    $html .= '</ul>';
                    continue;
                }
            }

            $html .= '<p>' . nl2br(htmlspecialchars($trimmed)) . '</p>';
        }

        return $html ?: '<p>' . nl2br(htmlspecialchars($normalized)) . '</p>';
    }
}
