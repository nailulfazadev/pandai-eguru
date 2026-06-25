<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Display a listing of the authenticated user's documents.
     */
    public function index()
    {
        $documents = Auth::user()->documents()->orderBy('created_at', 'desc')->get()->groupBy('type');
        return view('riwayat', compact('documents'));
    }

    /**
     * Download the specified document as an MS Word (.doc) file.
     */
    public function downloadDoc($id)
    {
        $document = Auth::user()->documents()->findOrFail($id);

        $isLandscape = false;
        if (strtolower($document->type) === 'rubrik') {
            $htmlContent = $this->rubrikToHtml($document);
            $isLandscape = true;
        } elseif (strtolower($document->type) === 'bahan ajar') {
            $data = json_decode($document->content, true);
            $markdown = $data['handout'] ?? '';
            $htmlContent = $this->markdownToHtml($markdown);
        } elseif (strtolower($document->type) === 'bahan ajar utama') {
            $htmlContent = $this->bahanAjarUtamaToHtml($document);
        } elseif (strtolower($document->type) === 'lkpd') {
            $htmlContent = $this->lkpdToHtml($document);
        } elseif (strtolower($document->type) === 'prompt') {
            $htmlContent = $this->promptToHtml($document);
        } else {
            $htmlContent = $this->markdownToHtml($document->content, $document->type === 'Soal');
        }

        $pageCss = $isLandscape ? "
                @page WordSection1 {
                    size: 841.9pt 595.3pt; /* A4 Landscape */
                    mso-page-orientation: landscape;
                    margin: 2.0cm 2.0cm 2.0cm 2.0cm;
                    mso-header-margin: 1.0cm;
                    mso-footer-margin: 1.0cm;
                    mso-paper-source: 0;
                }
        " : "
                @page WordSection1 {
                    size: A4;
                    margin: 3.0cm 3.0cm 3.0cm 4.0cm; /* Standard Indonesian official margin */
                    mso-header-margin: 1.25cm;
                    mso-footer-margin: 1.25cm;
                    mso-paper-source: 0;
                }
        ";

        // HTML structure specifically crafted for Microsoft Word to parse correctly with standard styles
        $wordHtml = "
        <html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
        <head>
            <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
            <title>{$document->name}</title>
            <!--[if gte mso 9]>
            <xml>
                <w:WordDocument>
                    <w:View>Print</w:View>
                    <w:Zoom>100</w:Zoom>
                    <w:DoNotOptimizeForBrowser/>
                </w:WordDocument>
            </xml>
            <![endif]-->
            <style>
                {$pageCss}
                div.WordSection1 {
                    page: WordSection1;
                }
                body {
                    font-family: 'Times New Roman', Times, serif;
                    font-size: 12pt;
                    line-height: 1.5;
                    color: #000000;
                }
            </style>
        </head>
        <body>
            <div class='WordSection1'>
                {$htmlContent}
            </div>
        </body>
        </html>";

        $fileName = str_replace([' ', '/', '\\', '?', '*', ':', '|', '<', '>'], '_', $document->name) . '.doc';

        return response($wordHtml)
            ->header('Content-Type', 'application/octet-stream')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Show the specified document in a print-ready view that automatically triggers PDF generation.
     */
    public function printDoc($id)
    {
        $document = \Illuminate\Support\Facades\Auth::user()->documents()->findOrFail($id);
        
        if (strtolower($document->type) === 'rubrik') {
            $htmlContent = $this->rubrikToHtml($document);
        } elseif (strtolower($document->type) === 'bahan ajar') {
            $data = json_decode($document->content, true);
            $markdown = $data['handout'] ?? '';
            $htmlContent = $this->markdownToHtml($markdown);
        } elseif (strtolower($document->type) === 'bahan ajar utama') {
            $htmlContent = $this->bahanAjarUtamaToHtml($document, true);
        } elseif (strtolower($document->type) === 'lkpd') {
            $htmlContent = $this->lkpdToHtml($document);
        } elseif (strtolower($document->type) === 'prompt') {
            $htmlContent = $this->promptToHtml($document);
        } else {
            $htmlContent = $this->markdownToHtml($document->content, $document->type === 'Soal');
        }

        return view('print-doc', compact('document', 'htmlContent'));
    }

    /**
     * Helper to convert rubrik JSON to HTML for Word compatibility.
     */
    public function rubrikToHtml($document)
    {
        $data = json_decode($document->content, true);
        if (!$data || !isset($data['rubrik'])) return "<p>Data tidak valid.</p>";
        
        $rubrikJson = $data['rubrik'];
        $students = $data['students'] ?? [];
        
        $html = "<div style='text-align:center; margin-bottom: 20px;'>";
        $html .= "<h2 style='font-family: Arial, sans-serif; font-size: 18pt; margin-bottom:5px;'>" . htmlspecialchars($rubrikJson['title'] ?? 'Rubrik Penilaian') . "</h2>";
        $html .= "</div>";

        // Rubric Table
        $html .= "<table border='1' cellspacing='0' cellpadding='5' style='width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 11pt; margin-bottom: 30px;'>";
        
        if (isset($rubrikJson['criteria']) && count($rubrikJson['criteria']) > 0) {
            $html .= "<tr style='background-color: #f2f2f2;'>";
            $html .= "<th style='text-align: left; width: 20%;'>Aspek Penilaian</th>";
            foreach ($rubrikJson['criteria'][0]['levels'] as $level) {
                $html .= "<th style='text-align: left;'>" . htmlspecialchars($level['score']) . "</th>";
            }
            $html .= "</tr>";

            foreach ($rubrikJson['criteria'] as $crit) {
                $html .= "<tr>";
                $html .= "<td style='font-weight: bold; background-color: #fafafa;'>" . htmlspecialchars($crit['aspect']) . "</td>";
                foreach ($crit['levels'] as $level) {
                    $html .= "<td style='vertical-align: top;'>" . htmlspecialchars($level['description']) . "</td>";
                }
                $html .= "</tr>";
            }
        }
        $html .= "</table>";

        // Student Table
        if (!empty($students)) {
            $html .= "<br style='page-break-before: always; clear: both; mso-special-character: page-break;' />";
            $html .= "<div style='text-align:center; margin-bottom: 20px;'>";
            $html .= "<h2 style='font-family: Arial, sans-serif; font-size: 18pt; margin-bottom:5px;'>Lembar Penilaian Siswa</h2>";
            $html .= "</div>";

            $html .= "<table border='1' cellspacing='0' cellpadding='5' style='width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 11pt;'>";
            $html .= "<tr style='background-color: #f2f2f2;'>";
            $html .= "<th style='text-align: center; width: 5%;'>No</th>";
            $html .= "<th style='text-align: left; width: 25%;'>Nama Siswa</th>";
            
            foreach ($rubrikJson['criteria'] as $crit) {
                $html .= "<th style='text-align: center;'>" . htmlspecialchars($crit['aspect']) . "</th>";
            }
            $html .= "<th style='text-align: center;'>Nilai Akhir</th>";
            $html .= "</tr>";

            foreach ($students as $idx => $student) {
                $html .= "<tr>";
                $html .= "<td style='text-align: center;'>" . ($idx + 1) . "</td>";
                $html .= "<td>" . htmlspecialchars($student) . "</td>";
                foreach ($rubrikJson['criteria'] as $crit) {
                    $html .= "<td></td>";
                }
                $html .= "<td></td>";
                $html .= "</tr>";
            }
            $html .= "</table>";
        }

        return $html;
    }

    /**
     * Helper to convert markdown format to HTML for Word compatibility.
     */
    public function markdownToHtml($markdown, $isSoal = false)
    {
        // Normalize line endings
        $markdown = str_replace("\r\n", "\n", $markdown);
        $lines = explode("\n", $markdown);
        $html = '';
        
        $inList = false;
        $inOList = false;
        $inTable = false;
        $tableIsBorderless = false;
        foreach ($lines as $line) {
            $line = trim($line);

            // Handle raw HTML block lines
            if (preg_match('/^<([a-zA-Z1-6]+|!--)/', $line) || preg_match('/^<\//', $line)) {
                if ($inTable) {
                    $html .= "</tbody>\n</table>\n";
                    $inTable = false;
                    $tableIsBorderless = false;
                }
                if ($inList) {
                    $html .= "</ul>\n";
                    $inList = false;
                }
                if ($inOList) {
                    $html .= "</ol>\n";
                    $inOList = false;
                }
                $html .= $line . "\n";
                continue;
            }

            // Handle page break
            if (stripos($line, '[PAGE_BREAK]') !== false || stripos($line, '<!-- pagebreak -->') !== false || stripos($line, '\pagebreak') !== false) {
                if ($inTable) {
                    $html .= "</tbody>\n</table>\n";
                    $inTable = false;
                    $tableIsBorderless = false;
                }
                if ($inList) {
                    $html .= "</ul>\n";
                    $inList = false;
                }
                if ($inOList) {
                    $html .= "</ol>\n";
                    $inOList = false;
                }
                $html .= "<br style='page-break-before: always; clear: both; mso-special-character: page-break;' />\n";
                continue;
            }

            // Handle horizontal rule / page break / separator line
            if (preg_match('/^[\-\*_]{3,}$/', $line)) {
                if ($inTable) {
                    $html .= "</tbody>\n</table>\n";
                    $inTable = false;
                    $tableIsBorderless = false;
                }
                if ($inList) {
                    $html .= "</ul>\n";
                    $inList = false;
                }
                if ($inOList) {
                    $html .= "</ol>\n";
                    $inOList = false;
                }
                $html .= "<hr style='border: none; border-top: 1px solid #000000; height: 1px; margin: 20pt 0;' />\n";
                continue;
            }

            // Handle unordered lists
            if (preg_match('/^[\*\-\+]\s+(.*)$/', $line, $matches)) {
                if ($inTable) {
                    $html .= "</tbody>\n</table>\n";
                    $inTable = false;
                    $tableIsBorderless = false;
                }
                if ($inOList) {
                    $html .= "</ol>\n";
                    $inOList = false;
                }
                if (!$inList) {
                    $html .= "<ul style='margin-top: 0in; margin-bottom: 8pt; padding-left: 20pt;'>\n";
                    $inList = true;
                }
                $html .= "<li style='margin-bottom: 4pt; font-size: 12pt; font-family: \"Times New Roman\", Times, serif; text-align: justify; line-height: 1.5;'>" . $this->inlineMarkdown($matches[1]) . "</li>\n";
                continue;
            } else {
                if ($inList) {
                    $html .= "</ul>\n";
                    $inList = false;
                }
            }

            // Handle ordered lists
            if (preg_match('/^(\d+)\.\s+(.*)$/', $line, $matches)) {
                if ($inTable) {
                    $html .= "</tbody>\n</table>\n";
                    $inTable = false;
                    $tableIsBorderless = false;
                }
                if ($inList) {
                    $html .= "</ul>\n";
                    $inList = false;
                }
                $num = $matches[1];
                if ($isSoal) {
                    // LAYOUT CONTRACT LOCKED: DO NOT MODIFY. Aligned to MS Word grid (18pt / 0.25 inch) and using &#9; for native tab alignment.
                    // This prevents formatting breakage on Word/PDF exports.
                    $html .= "<p style='margin-top: 16pt; margin-bottom: 4pt; margin-left: 18pt; text-indent: -18pt; text-align: justify; font-size: 12pt; font-family: \"Times New Roman\", Times, serif; line-height: 1.5;'>{$num}.&#9;" . $this->inlineMarkdown($matches[2]) . "</p>\n";
                } else {
                    if (!$inOList) {
                        $html .= "<ol start='{$num}' style='margin-top: 0in; margin-bottom: 8pt; padding-left: 20pt;'>\n";
                        $inOList = true;
                    }
                    $html .= "<li value='{$num}' style='margin-bottom: 4pt; font-size: 12pt; font-family: \"Times New Roman\", Times, serif; text-align: justify; line-height: 1.5;'>" . $this->inlineMarkdown($matches[2]) . "</li>\n";
                }
                continue;
            } else {
                if ($inOList) {
                    $html .= "</ol>\n";
                    $inOList = false;
                }
            }
            
            // Handle tables
            if (preg_match('/^\|(.*)\|$/', $line, $matches)) {
                $rowContent = trim($matches[1]);
                // Check if separator row like |---|---|
                if (preg_match('/^[:\-\s\|]+$/', $rowContent)) {
                    continue;
                }
                
                $cols = explode('|', $rowContent);
                $cols = array_map('trim', $cols);
                $numCols = count($cols);
                
                if (!$inTable) {
                    $inTable = true;
                    // Check if the header row is completely empty (e.g., signature block)
                    $isBorderless = true;
                    foreach ($cols as $col) {
                        if ($col !== '') {
                            $isBorderless = false;
                            break;
                        }
                    }
                    
                    if ($isBorderless) {
                        $html .= "<table class='borderless-table' border='0' cellspacing='0' cellpadding='0' style='border: none; border-collapse: collapse; width: 100%; margin-top: 30pt;'>\n<tbody>\n";
                        $tableIsBorderless = true;
                    } else {
                        $html .= "<table class='standard-table' border='1' cellspacing='0' cellpadding='6' style='border-collapse: collapse; border: 1px solid #000000; width: 100%; margin-top: 12pt; margin-bottom: 12pt;'>\n";
                        $html .= "<thead>\n<tr style='background-color: #f2f2f2;'>\n";
                        $colIndex = 0;
                        foreach ($cols as $col) {
                            $widthAttr = '';
                            if ($numCols === 2) {
                                $widthAttr = $colIndex === 0 ? "width='30%'" : "width='70%'";
                            } elseif ($numCols === 3) {
                                if ($colIndex === 0) $widthAttr = "width='25%'";
                                elseif ($colIndex === 1) $widthAttr = "width='20%'";
                                else $widthAttr = "width='55%'";
                            }
                            $html .= "<th {$widthAttr} style='border: 1px solid #000000; padding: 6pt 8pt; font-weight: bold; color: #000000; text-align: left; font-size: 11pt; font-family: \"Times New Roman\", Times, serif; line-height: 1.3;'>" . $this->inlineMarkdown($col) . "</th>\n";
                            $colIndex++;
                        }
                        $html .= "</tr>\n</thead>\n<tbody>\n";
                        $tableIsBorderless = false;
                    }
                } else {
                    $html .= "<tr>\n";
                    $colIndex = 0;
                    foreach ($cols as $col) {
                        $widthAttr = '';
                        if ($tableIsBorderless) {
                            $widthAttr = "width='50%'";
                            $html .= "<td {$widthAttr} style='border: none; padding: 6pt 8pt; vertical-align: top; font-size: 12pt; font-family: \"Times New Roman\", Times, serif; text-align: center; line-height: 1.5;'>" . $this->inlineMarkdown($col) . "</td>\n";
                        } else {
                            if ($numCols === 2) {
                                $widthAttr = $colIndex === 0 ? "width='30%'" : "width='70%'";
                            } elseif ($numCols === 3) {
                                if ($colIndex === 0) $widthAttr = "width='25%'";
                                elseif ($colIndex === 1) $widthAttr = "width='20%'";
                                else $widthAttr = "width='55%'";
                            }
                            $html .= "<td {$widthAttr} style='border: 1px solid #000000; padding: 6pt 8pt; vertical-align: top; font-size: 11pt; font-family: \"Times New Roman\", Times, serif; line-height: 1.3;'>" . $this->inlineMarkdown($col) . "</td>\n";
                        }
                        $colIndex++;
                    }
                    $html .= "</tr>\n";
                }
                continue;
            } else {
                if ($inTable) {
                    $html .= "</tbody>\n</table>\n";
                    $inTable = false;
                    $tableIsBorderless = false;
                }
            }
            
            // Handle empty line
            if ($line === '') {
                continue;
            }
            
            // Handle headings
            if (preg_match('/^(#{1,6})\s+(.*)$/', $line, $matches)) {
                $level = strlen($matches[1]);
                $content = $this->inlineMarkdown($matches[2]);
                
                $style = '';
                if ($level === 1) {
                    $style = "style='font-family: \"Times New Roman\", Times, serif; font-size: 16pt; font-weight: bold; color: #000000; margin-top: 18pt; margin-bottom: 18pt; text-align: center; text-transform: uppercase;'";
                } elseif ($level === 2) {
                    $style = "style='font-family: \"Times New Roman\", Times, serif; font-size: 13pt; font-weight: bold; color: #000000; margin-top: 20pt; margin-bottom: 10pt; border-bottom: 1px solid #000000; padding-bottom: 3px;'";
                } elseif ($level === 3) {
                    $style = "style='font-family: \"Times New Roman\", Times, serif; font-size: 12pt; font-weight: bold; color: #000000; margin-top: 14pt; margin-bottom: 8pt;'";
                } else {
                    $style = "style='font-family: \"Times New Roman\", Times, serif; font-size: 11pt; font-weight: bold; color: #000000; margin-top: 12pt; margin-bottom: 6pt;'";
                }
                
                $html .= "<h{$level} {$style}>{$content}</h{$level}>\n";
                continue;
            }
            
            // Handle blockquotes
            if (preg_match('/^>\s*(.*)$/', $line, $matches)) {
                $html .= "<blockquote style='border-left: 3pt solid #333333; padding-left: 10pt; margin: 12pt 0 12pt 20pt; color: #333333; font-style: italic; font-size: 11pt; font-family: \"Times New Roman\", Times, serif;'><p style='margin: 0; font-family: \"Times New Roman\", Times, serif; font-size: 11pt; color: #333333; font-style: italic; line-height: 1.3;'>" . $this->inlineMarkdown($matches[1]) . "</p></blockquote>\n";
                continue;
            }
            // Handle sub-label for Two-Tier MCQ (Tingkat 2)
            if ($isSoal && preg_match('/^Tingkat 2\s*\(Alasan\):/i', $line)) {
                if ($inTable) {
                    $html .= "</tbody>\n</table>\n";
                    $inTable = false;
                    $tableIsBorderless = false;
                }
                if ($inList) {
                    $html .= "</ul>\n";
                    $inList = false;
                }
                $html .= "<p style='margin-top: 6pt; margin-bottom: 2pt; margin-left: 18pt; font-weight: bold; font-size: 12pt; font-family: \"Times New Roman\", Times, serif; line-height: 1.5;'>{$line}</p>\n";
                continue;
            }

            // Handle multiple-choice options (A. B. C. D. E.) for Soal documents
            if ($isSoal && preg_match('/^([A-E])\.\s+(.*)$/i', $line, $matches)) {
                if ($inTable) {
                    $html .= "</tbody>\n</table>\n";
                    $inTable = false;
                    $tableIsBorderless = false;
                }
                if ($inList) {
                    $html .= "</ul>\n";
                    $inList = false;
                }
                $optionLetter = strtoupper($matches[1]);
                $optionContent = $this->inlineMarkdown($matches[2]);
                // LAYOUT CONTRACT LOCKED: DO NOT MODIFY. Aligned to MS Word grid (margin-left: 36pt / 0.5 inch, text-indent: -18pt / -0.25 inch) with tab entity &#9;
                // This aligns option letters exactly under the question text, and wraps long text in line.
                $html .= "<p style='margin-top: 2pt; margin-bottom: 2pt; margin-left: 36pt; text-indent: -18pt; text-align: justify; font-size: 12pt; font-family: \"Times New Roman\", Times, serif; line-height: 1.5;'>{$optionLetter}.&#9;{$optionContent}</p>\n";
                continue;
            }

            // Default paragraph
            $html .= "<p style='margin-top: 0in; margin-bottom: 8pt; text-align: justify; font-size: 12pt; font-family: \"Times New Roman\", Times, serif; line-height: 1.5;'>" . $this->inlineMarkdown($line) . "</p>\n";
        }
        
        // Close remaining tags
        if ($inList) {
            $html .= "</ul>\n";
        }
        if ($inOList) {
            $html .= "</ol>\n";
        }
        if ($inTable) {
            $html .= "</tbody>\n</table>\n";
        }
        
        return $html;
    }

    private function inlineMarkdown($text)
    {
        // Bold: **text**
        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
        // Italic: *text*
        $text = preg_replace('/\*([^\*]+)\*/', '<em>$1</em>', $text);
        return $text;
    }

    public function bahanAjarUtamaToHtml($document, $isForPrint = false)
    {
        $data = json_decode($document->content, true);
        if (!$data) return "<p>Data tidak valid.</p>";

        $html = "<div style='text-align:center; margin-bottom: 20px;'>";
        $html .= "<h1 style='font-family: \"Times New Roman\", Times, serif; font-size: 18pt; font-weight: bold; text-transform: uppercase; margin-bottom:5px;'>" . htmlspecialchars($data['title'] ?? $document->name) . "</h1>";
        $html .= "<p style='font-size: 11pt; font-style: italic; color: #555555; margin-bottom: 30px;'>Bahan Ajar Lengkap</p>";
        $html .= "</div>";

        // Section 1: Materi Utama
        if (isset($data['materi'])) {
            $html .= "<h2 style='font-family: \"Times New Roman\", Times, serif; font-size: 14pt; font-weight: bold; border-bottom: 2px solid #000000; padding-bottom: 4px; margin-top: 24pt;'>I. RINGKASAN MATERI AJAR</h2>";
            $html .= $this->markdownToHtml($data['materi']);
        }

        // Section 2: Peta Konsep
        if (isset($data['concept_map'])) {
            $html .= "<br style='page-break-before: always; clear: both; mso-special-character: page-break;' />";
            if ($isForPrint) {
                $html .= "<div class='landscape-section'>";
            }
            $html .= "<h2 style='font-family: \"Times New Roman\", Times, serif; font-size: 14pt; font-weight: bold; border-bottom: 2px solid #000000; padding-bottom: 4px; margin-top: 24pt;'>II. DIAGRAM SIKLUS / PETA KONSEP</h2>";
            if ($isForPrint) {
                $html .= "<p style='font-style: italic; font-size: 10pt; color: #666666; margin-bottom: 15px;'>Visualisasi peta konsep (Halaman Landscape):</p>";
                $html .= "<div class='mermaid' id='print-mermaid-diagram' style='display: flex; justify-content: center; margin: 15px 0; font-family: monospace; font-size: 10pt;'>\n" . htmlspecialchars($data['concept_map']) . "\n</div>";
                $html .= "</div>";
            } else {
                $html .= "<p style='font-style: italic; font-size: 10pt; color: #666666;'>Struktur keterkaitan konsep kunci (format kode diagram):</p>";
                $html .= "<pre style='background-color: #f5f5f5; border: 1px solid #cccccc; padding: 10px; font-family: monospace; font-size: 10pt; white-space: pre-wrap;'>" . htmlspecialchars($data['concept_map']) . "</pre>";
            }
        }

        // Section 3: Lembar Kerja Peserta Didik (LKPD)
        if (isset($data['lkpd'])) {
            $html .= "<br style='page-break-before: always; clear: both; mso-special-character: page-break;' />";
            $html .= "<h2 style='font-family: \"Times New Roman\", Times, serif; font-size: 14pt; font-weight: bold; border-bottom: 2px solid #000000; padding-bottom: 4px; margin-top: 24pt;'>III. LEMBAR KERJA PESERTA DIDIK (LKPD)</h2>";
            $html .= $this->markdownToHtml($data['lkpd']);
        }

        // Section 4: Aktivitas Pembelajaran
        if (isset($data['classroom_activities']) && count($data['classroom_activities']) > 0) {
            $html .= "<br style='page-break-before: always; clear: both; mso-special-character: page-break;' />";
            $html .= "<h2 style='font-family: \"Times New Roman\", Times, serif; font-size: 14pt; font-weight: bold; border-bottom: 2px solid #000000; padding-bottom: 4px; margin-top: 24pt;'>IV. RENCANA AKTIVITAS PEMBELAJARAN KELAS</h2>";
            foreach ($data['classroom_activities'] as $act) {
                $html .= "<div style='margin-bottom: 18pt; border-left: 3px solid #58cc02; padding-left: 10pt;'>";
                $html .= "<h3 style='font-family: \"Times New Roman\", Times, serif; font-size: 12pt; font-weight: bold; margin-bottom: 4px;'>" . htmlspecialchars($act['name'] ?? '') . " (" . htmlspecialchars($act['duration'] ?? '') . ")</h3>";
                if (isset($act['objective'])) {
                    $html .= "<p style='font-size: 11pt; font-style: italic; margin-bottom: 6px;'>Tujuan: " . htmlspecialchars($act['objective']) . "</p>";
                }
                if (isset($act['steps'])) {
                    $html .= "<ol style='margin-top: 0in; margin-bottom: 8pt; padding-left: 20pt;'>";
                    foreach ($act['steps'] as $step) {
                        $html .= "<li style='margin-bottom: 2pt; font-size: 11pt;'>" . htmlspecialchars($step) . "</li>";
                    }
                    $html .= "</ol>";
                }
                $html .= "</div>";
            }
        }

        // Section 5: Kuis Formatif
        if (isset($data['quiz']) && count($data['quiz']) > 0) {
            $html .= "<br style='page-break-before: always; clear: both; mso-special-character: page-break;' />";
            $html .= "<h2 style='font-family: \"Times New Roman\", Times, serif; font-size: 14pt; font-weight: bold; border-bottom: 2px solid #000000; padding-bottom: 4px; margin-top: 24pt;'>V. LAMPIRAN: KUIS FORMATIF & KUNCI JAWABAN</h2>";
            $html .= "<ol style='padding-left: 20pt;'>";
            foreach ($data['quiz'] as $idx => $q) {
                $html .= "<li style='margin-bottom: 12pt; font-size: 11pt;'>";
                $html .= "<strong>" . htmlspecialchars($q['question']) . "</strong><br/>";
                if (isset($q['options'])) {
                    foreach ($q['options'] as $oIdx => $opt) {
                        $isCorrect = ($oIdx == ($q['answer'] ?? -1)) ? " <strong style='color:#58cc02;'>(Kunci Jawaban)</strong>" : "";
                        $html .= "- " . htmlspecialchars($opt) . "{$isCorrect}<br/>";
                    }
                }
                if (isset($q['explanation'])) {
                    $html .= "<em style='font-size: 10pt; color: #555555;'>Penjelasan: " . htmlspecialchars($q['explanation']) . "</em>";
                }
                $html .= "</li>";
            }
            $html .= "</ol>";
        }

        return $html;
    }

    public function lkpdToHtml($document)
    {
        $data = json_decode($document->content, true);
        if (!$data) return "<p>Data tidak valid.</p>";

        $html = "<div style='text-align:center; margin-bottom: 20px;'>";
        $html .= "<h1 style='font-family: \"Times New Roman\", Times, serif; font-size: 16pt; font-weight: bold; text-transform: uppercase; margin-bottom:5px;'>" . htmlspecialchars($data['judul'] ?? 'LEMBAR KERJA PESERTA DIDIK (LKPD)') . "</h1>";
        $html .= "<p style='font-size: 11pt; font-style: italic; color: #555555; margin-bottom: 25px;'>Lembar Kerja Siswa Mandiri / Kelompok</p>";
        $html .= "</div>";

        // Identity Table
        $html .= "<table border='0' cellspacing='0' cellpadding='3' style='width: 100%; border: none; margin-bottom: 20px; font-family: \"Times New Roman\", Times, serif; font-size: 11pt;'>";
        $html .= "<tr><td width='25%'><strong>Satuan Pendidikan</strong></td><td width='2%'>:</td><td>" . htmlspecialchars($data['satuan_pendidikan'] ?? '') . "</td></tr>";
        $html .= "<tr><td><strong>Kelas / Semester</strong></td><td>:</td><td>" . htmlspecialchars($data['kelas_semester'] ?? '') . "</td></tr>";
        $html .= "<tr><td><strong>Materi Ajar</strong></td><td>:</td><td>" . htmlspecialchars($data['materi_ajar'] ?? '') . "</td></tr>";
        $html .= "<tr><td><strong>Alokasi Waktu</strong></td><td>:</td><td>" . htmlspecialchars($data['alokasi_waktu'] ?? '') . "</td></tr>";
        $html .= "</table>";

        $html .= "<hr style='border: none; border-top: 1px solid #000000; height: 1px; margin-bottom: 20px;' />";

        // A. Indikator Pencapaian Kompetensi
        if (!empty($data['indikator'])) {
            $html .= "<h3 style='font-family: \"Times New Roman\", Times, serif; font-size: 12pt; font-weight: bold; margin-top: 15pt;'>A. Indikator Pencapaian Kompetensi</h3>";
            $html .= "<ul style='padding-left: 20pt; margin-top: 5pt; margin-bottom: 10pt;'>";
            foreach ($data['indikator'] as $ind) {
                $html .= "<li style='margin-bottom: 3pt; font-size: 11pt; text-align: justify;'>" . htmlspecialchars($ind) . "</li>";
            }
            $html .= "</ul>";
        }

        // B. Tujuan Pembelajaran
        if (!empty($data['tujuan'])) {
            $html .= "<h3 style='font-family: \"Times New Roman\", Times, serif; font-size: 12pt; font-weight: bold; margin-top: 15pt;'>B. Tujuan Pembelajaran</h3>";
            $html .= "<ul style='padding-left: 20pt; margin-top: 5pt; margin-bottom: 10pt;'>";
            foreach ($data['tujuan'] as $tuj) {
                $html .= "<li style='margin-bottom: 3pt; font-size: 11pt; text-align: justify;'>" . htmlspecialchars($tuj) . "</li>";
            }
            $html .= "</ul>";
        }

        // C. Petunjuk Belajar
        if (!empty($data['petunjuk_belajar'])) {
            $html .= "<h3 style='font-family: \"Times New Roman\", Times, serif; font-size: 12pt; font-weight: bold; margin-top: 15pt;'>C. Petunjuk Belajar</h3>";
            $html .= "<div style='margin-top: 5pt; margin-bottom: 10pt;'>";
            $html .= $this->markdownToHtml($data['petunjuk_belajar']);
            $html .= "</div>";
        }

        // D. Informasi Pendukung
        if (!empty($data['informasi_pendukung'])) {
            $html .= "<h3 style='font-family: \"Times New Roman\", Times, serif; font-size: 12pt; font-weight: bold; margin-top: 15pt;'>D. Informasi Pendukung</h3>";
            $html .= "<div style='margin-top: 5pt; margin-bottom: 10pt; border-left: 2px solid #a570ff; padding-left: 10pt; font-style: italic;'>";
            $html .= $this->markdownToHtml($data['informasi_pendukung']);
            $html .= "</div>";
        }

        // E. Langkah-langkah Kerja
        if (!empty($data['langkah_kerja'])) {
            $html .= "<h3 style='font-family: \"Times New Roman\", Times, serif; font-size: 12pt; font-weight: bold; margin-top: 15pt;'>E. Langkah-langkah Kerja</h3>";
            $html .= "<ol style='padding-left: 20pt; margin-top: 5pt; margin-bottom: 10pt;'>";
            foreach ($data['langkah_kerja'] as $lk) {
                $html .= "<li style='margin-bottom: 4pt; font-size: 11pt; text-align: justify;'>" . htmlspecialchars($this->stripPrefix($lk, 'step')) . "</li>";
            }
            $html .= "</ol>";
        }

        // F. Soal-soal Pertanyaan
        if (!empty($data['soal_soal'])) {
            $html .= "<h3 style='font-family: \"Times New Roman\", Times, serif; font-size: 12pt; font-weight: bold; margin-top: 15pt;'>F. Soal-soal Latihan / Evaluasi</h3>";
            $html .= "<ol style='padding-left: 20pt; margin-top: 5pt; margin-bottom: 10pt;'>";
            foreach ($data['soal_soal'] as $s) {
                $html .= "<li style='margin-bottom: 12pt; font-size: 11pt;'>";
                $html .= "<strong>" . htmlspecialchars($this->stripPrefix($s['pertanyaan'], 'question')) . "</strong>";
                $html .= "<div style='margin-top: 10pt; border-bottom: 1px dotted #999; height: 35px; width: 100%;'></div>";
                $html .= "<div style='margin-top: 5pt; border-bottom: 1px dotted #999; height: 35px; width: 100%;'></div>";
                $html .= "<div style='margin-top: 5pt; border-bottom: 1px dotted #999; height: 35px; width: 100%;'></div>";
                
                // Show answer key for reference at the bottom if printed / exported, but styled subtle
                if (isset($s['kunci_jawaban'])) {
                    $html .= "<div style='margin-top: 8pt; font-size: 9pt; color: #777777; font-style: italic;'>Kunci Jawaban/Rubrik: " . htmlspecialchars($s['kunci_jawaban']) . "</div>";
                }
                $html .= "</li>";
            }
            $html .= "</ol>";
        }

        return $html;
    }

    public function promptToHtml($document)
    {
        $data = json_decode($document->content, true);
        if (!$data) {
            return "<div style='font-family: Arial, sans-serif;'><pre style='white-space: pre-wrap;'>" . htmlspecialchars($document->content) . "</pre></div>";
        }

        $html = "<div style='font-family: Arial, sans-serif; margin-bottom: 20px;'>";
        $html .= "<h1 style='font-size: 16pt; font-weight: bold; text-align: center; text-transform: uppercase; color: #4B0082; margin-bottom: 5px;'>PROMPT GURU AI (SUPER PROMPT BUILDER)</h1>";
        $html .= "<p style='font-size: 10pt; text-align: center; color: #666666; margin-bottom: 25px;'>Hasil Racikan Instan PandAI untuk LLM (Gemini, ChatGPT, DeepSeek)</p>";
        $html .= "</div>";

        // Metadata table
        $html .= "<table border='1' cellspacing='0' cellpadding='6' style='width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 10.5pt; border-color: #dddddd;'>";
        $html .= "<tr style='background-color: #f9f9f9;'><td width='30%'><strong>Jenis Produk / Output</strong></td><td>" . htmlspecialchars($data['prompt_type'] ?? '-') . "</td></tr>";
        
        if (!empty($data['school_name'])) {
            $html .= "<tr><td><strong>Nama Sekolah / Instansi</strong></td><td>" . htmlspecialchars($data['school_name']) . "</td></tr>";
        }
        if (!empty($data['teacher_name'])) {
            $html .= "<tr><td><strong>Nama Guru</strong></td><td>" . htmlspecialchars($data['teacher_name']) . " (NIP: " . htmlspecialchars($data['teacher_nip'] ?: '-') . ")</td></tr>";
        }
        if (!empty($data['principal_name'])) {
            $html .= "<tr><td><strong>Nama Kepala Sekolah</strong></td><td>" . htmlspecialchars($data['principal_name']) . " (NIP: " . htmlspecialchars($data['principal_nip'] ?: '-') . ")</td></tr>";
        }
        if (!empty($data['time_allocation'])) {
            $html .= "<tr><td><strong>Alokasi Waktu</strong></td><td>" . htmlspecialchars($data['time_allocation']) . "</td></tr>";
        }
        if (!empty($data['theme'])) {
            $html .= "<tr><td><strong>Tema Proyek P5</strong></td><td>" . htmlspecialchars($data['theme']) . "</td></tr>";
        }
        if (!empty($data['subject']) && $data['subject'] !== '-') {
            $html .= "<tr><td><strong>Mata Pelajaran</strong></td><td>" . htmlspecialchars($data['subject']) . "</td></tr>";
        }
        
        $html .= "<tr><td><strong>Materi / Topik</strong></td><td>" . htmlspecialchars($data['topic'] ?? '-') . "</td></tr>";
        $html .= "<tr><td><strong>Kelas / Jenjang</strong></td><td>" . htmlspecialchars($data['grade'] ?? '-') . "</td></tr>";
        $html .= "<tr><td><strong>Model Pembelajaran</strong></td><td>" . htmlspecialchars($data['learning_model'] ?? '-') . "</td></tr>";
        $html .= "<tr><td><strong>Karakteristik Siswa</strong></td><td>" . htmlspecialchars($data['student_profile'] ?? '-') . "</td></tr>";
        
        if (!empty($data['cp'])) {
            $html .= "<tr><td><strong>Capaian Pembelajaran (CP)</strong></td><td>" . nl2br(htmlspecialchars($data['cp'])) . "</td></tr>";
        }
        if (!empty($data['tp'])) {
            $html .= "<tr><td><strong>Tujuan Pembelajaran (TP)</strong></td><td>" . nl2br(htmlspecialchars($data['tp'])) . "</td></tr>";
        }
        
        if (!empty($data['p5']) && is_array($data['p5'])) {
            $html .= "<tr><td><strong>Profil Pelajar Pancasila (P5)</strong></td><td>" . htmlspecialchars(implode(', ', $data['p5'])) . "</td></tr>";
        }
        if (!empty($data['supporting_components']) && is_array($data['supporting_components'])) {
            $html .= "<tr><td><strong>Komponen Pendukung</strong></td><td>" . htmlspecialchars(implode(', ', $data['supporting_components'])) . "</td></tr>";
        }
        
        if (!empty($data['additional_notes'])) {
            $html .= "<tr><td><strong>Catatan Tambahan</strong></td><td>" . htmlspecialchars($data['additional_notes']) . "</td></tr>";
        }
        $html .= "</table>";

        $html .= "<h3 style='font-size: 12pt; font-weight: bold; border-bottom: 1px solid #4B0082; padding-bottom: 5px; color: #4B0082;'>Salinan Prompt Utama</h3>";
        $html .= "<div style='background-color: #f5f5f5; border: 1px solid #e0e0e0; padding: 15px; border-radius: 5px; margin-top: 10px; font-family: monospace; font-size: 10pt; white-space: pre-wrap; line-height: 1.4; color: #333333;'>";
        $html .= htmlspecialchars($data['generated_prompt'] ?? '');
        $html .= "</div>";

        return $html;
    }

    private function stripPrefix($text, $type = 'step')
    {
        $text = trim($text);
        if ($type === 'step') {
            return trim(preg_replace('/^(langkah\s*\d+[:\.]?\s*|\d+[\.\s]+)/i', '', $text));
        } else {
            return trim(preg_replace('/^((pertanyaan|soal)\s*\d+[:\.]?\s*|\d+[\.\s]+)/i', '', $text));
        }
    }
}
