<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak PDF - {{ $document->name }}</title>
    <style>
        @page {
            size: A4;
            margin: 3.0cm 3.0cm 3.0cm 4.0cm; /* Standard Indonesian official margin: Top, Right, Bottom, Left */
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000000;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }
        
        /* Interactive controls for screen view (hidden on print) */
        .no-print-banner {
            background-color: #f3f4f6;
            border-bottom: 2px solid #e5e7eb;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: system-ui, -apple-system, sans-serif;
            font-size: 14px;
            color: #374151;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .btn-print {
            background-color: #10b981;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.2s;
        }
        .btn-print:hover {
            background-color: #059669;
        }
        .btn-back {
            color: #4b5563;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-back:hover {
            color: #1f2937;
            text-decoration: underline;
        }

        .print-content {
            padding: 0;
        }

        /* Elements styling */
        h1 {
            font-size: 16pt;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            margin-top: 18pt;
            margin-bottom: 18pt;
        }
        h2 {
            font-size: 13pt;
            font-weight: bold;
            margin-top: 20pt;
            margin-bottom: 10pt;
            border-bottom: 1px solid #000000;
            padding-bottom: 3px;
        }
        h3 {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 14pt;
            margin-bottom: 8pt;
        }
        h4 {
            font-size: 11pt;
            font-weight: bold;
            margin-top: 12pt;
            margin-bottom: 6pt;
        }
        p {
            margin-top: 0in;
            margin-bottom: 8pt;
            text-align: justify;
        }
        ul, ol {
            margin-top: 0in;
            margin-bottom: 8pt;
            padding-left: 20pt;
        }
        li {
            margin-bottom: 4pt;
            text-align: justify;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 12pt;
            margin-bottom: 12pt;
        }
        table, th, td {
            border: 1px solid #000000;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            padding: 6pt 8pt;
            text-align: left;
            font-size: 11pt;
        }
        td {
            padding: 6pt 8pt;
            vertical-align: top;
            font-size: 11pt;
        }
        blockquote {
            border-left: 3pt solid #333333;
            padding-left: 10pt;
            margin: 12pt 0 12pt 20pt;
            color: #333333;
            font-style: italic;
            font-size: 11pt;
        }
        
        .borderless-table {
            border: none !important;
            margin-top: 30pt;
        }
        .borderless-table td {
            border: none !important;
            text-align: center;
            width: 50%;
            font-size: 12pt;
            line-height: 1.5;
        }

        /* Mermaid SVG responsive scaling for print */
        .mermaid {
            width: 100% !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            overflow: visible !important;
            margin: 15px 0 !important;
            page-break-inside: avoid !important;
        }
        .mermaid svg {
            max-width: 100% !important;
            height: auto !important;
        }

        /* Mixed Portrait and Landscape printing */
        @page landscape-page {
            size: A4 landscape;
            margin: 2.0cm 2.0cm 2.0cm 2.0cm;
        }
        .landscape-section {
            page: landscape-page;
            break-before: page;
            break-after: page;
        }

        /* Hide interactive elements during actual printing */
        @media print {
            .no-print-banner {
                display: none !important;
            }
            body {
                background-color: transparent;
            }
            .print-content {
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-banner">
        <div>
            <a href="{{ route('documents.index') }}" class="btn-back">← Kembali ke Riwayat</a>
        </div>
        <div style="display: flex; align-items: center; gap: 16px;">
            <span>Gunakan dialog cetak browser Anda untuk menyimpan sebagai PDF.</span>
            <button onclick="window.print()" class="btn-print">Cetak ke PDF</button>
        </div>
    </div>

    <div class="print-content">
        {!! $htmlContent !!}
    </div>

    @if(isset($document) && strtolower($document->type) === 'bahan ajar utama')
        <!-- Load Mermaid for rendering flowcharts in PDF print -->
        <script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
        <script>
            mermaid.initialize({ 
                startOnLoad: false, 
                theme: 'forest',
                securityLevel: 'loose',
                flowchart: {
                    useMaxWidth: true,
                    htmlLabels: true
                }
            });
        </script>
    @endif

    <script>
        // Trigger print dialog automatically when the page loads
        window.addEventListener('DOMContentLoaded', async () => {
            const mermaidElement = document.querySelector('.mermaid');
            if (mermaidElement) {
                try {
                    let code = mermaidElement.textContent.trim();
                    // Clean and sanitize code (using our robust regex sanitizers)
                    let cleanCode = code.replace(/```mermaid\s*/gi, '').replace(/```/g, '').trim();
                    
                    // Auto-wrap unquoted node texts in double quotes to prevent Mermaid syntax errors
                    cleanCode = cleanCode.replace(/([a-zA-Z0-9_-]+)\[([^"\n\]][^\n\]]*|[^"\n\]])\]/g, '$1["$2"]');
                    cleanCode = cleanCode.replace(/([a-zA-Z0-9_-]+)\{([^"\n\}][^\n\}]*|[^"\n\}])\}/g, '$1{"$2"}');
                    cleanCode = cleanCode.replace(/([a-zA-Z0-9_-]+)\(([^"\n\)]+)\)/g, (match, p1, p2) => {
                        const lowerId = p1.toLowerCase();
                        if (lowerId === 'graph' || lowerId === 'flowchart' || lowerId === 'subgraph') {
                            return match;
                        }
                        return `${p1}("${p2}")`;
                    });

                    // Strip semicolons at the end of lines to prevent Mermaid v10+ syntax errors
                    cleanCode = cleanCode.replace(/;+\s*$/gm, '');

                    mermaidElement.innerHTML = cleanCode;
                    mermaidElement.removeAttribute('data-processed');
                    
                    await mermaid.run({
                        nodes: [mermaidElement]
                    });
                } catch (err) {
                    console.error("Failed to render Mermaid in print page:", err);
                }
            }

            // Give a delay to ensure rendering and font load completes
            setTimeout(() => {
                window.print();
            }, 800);
        });
    </script>
</body>
</html>
