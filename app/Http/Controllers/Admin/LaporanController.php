<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\Pembayaran;
use App\Models\Kontingen;
use App\Models\KategoriLomba;
use App\Models\KelompokUsia;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanController extends Controller
{
    public function peserta(Request $request)
    {
        // Create base query
        $baseQuery = Peserta::query();
        
        // Apply filters to base query
        if ($request->has('kontingen_id') && $request->kontingen_id) {
            $baseQuery->where('kontingen_id', $request->kontingen_id);
        }
        
        if ($request->has('kategori_id') && $request->kategori_id) {
            $baseQuery->whereHas('subkategoriLomba', function ($q) use ($request) {
                $q->where('kategori_id', $request->kategori_id);
            });
        }
        
        if ($request->has('kelompok_usia_id') && $request->kelompok_usia_id) {
            $baseQuery->where('kelompok_usia_id', $request->kelompok_usia_id);
        }
        
        // Get statistics with separate queries (avoids clone issues)
        $statistics = [
            'total_peserta' => (clone $baseQuery)->count(),
            'peserta_valid' => (clone $baseQuery)->where('status_verifikasi', 'valid')->count(),
            'peserta_tidak_valid' => (clone $baseQuery)->where('status_verifikasi', 'tidak_valid')->count(),
            'peserta_pending' => (clone $baseQuery)->where('status_verifikasi', 'pending')->count(),
        ];
        
        // Get filter options
        $kontingens = Kontingen::with('pelatih')->get();
        $kategoris = KategoriLomba::all();
        $kelompokUsias = KelompokUsia::all();
        
        // Create query for DataTables with relationships
        $dataQuery = clone $baseQuery;
        $dataQuery->with(['kontingen.pelatih', 'subkategoriLomba.kategoriLomba', 'kelompokUsia', 'kelasTanding', 'dokumenPesertas']);
        
        if ($request->ajax() && !$request->has('exportcsv')) {
            return DataTables::of($dataQuery)
                ->addIndexColumn()
                ->editColumn('jenis_kelamin', function($row) {
                    return $row->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
                })
                ->editColumn('tanggal_lahir', function($row) {
                    return $row->tanggal_lahir->format('d/m/Y');
                })
                ->editColumn('berat_badan', function($row) {
                    return $row->berat_badan . ' kg';
                })
                ->addColumn('tinggi_badan', function($row) {
                    return $row->tinggi_badan ? $row->tinggi_badan . ' cm' : '-';
                })
                ->addColumn('kontingen', function($row) {
                    return $row->kontingen->nama;
                })
                ->addColumn('kategori', function($row) {
                    return $row->subkategoriLomba->kategoriLomba->nama . ' - ' . $row->subkategoriLomba->nama;
                })
                ->addColumn('kelas_tanding', function($row) {
                    return $row->kelasTanding ? $row->kelasTanding->label_keterangan : 'Belum ditentukan';
                })
                ->editColumn('status_verifikasi', function($row) {
                    $badges = [
                        'valid' => 'success',
                        'pending' => 'warning',
                        'tidak_valid' => 'danger'
                    ];
                    $badge = $badges[$row->status_verifikasi] ?? 'secondary';
                    return '<span class="badge bg-'.$badge.'">'.ucfirst($row->status_verifikasi).'</span>';
                })
                ->addColumn('dokumen', function($row) {
                    $links = [];
                    foreach ($row->dokumenPesertas as $doc) {
                        $url = \Illuminate\Support\Facades\Storage::url($doc->file_path);
                        $links[] = '<a href="'.$url.'" target="_blank" class="badge bg-info mb-1" style="text-decoration: none;"><i class="fas fa-file-alt"></i> '.$doc->jenis_dokumen.'</a>';
                    }
                    return empty($links) ? '<span class="text-muted small">Tidak ada</span>' : implode(' ', $links);
                })
                ->rawColumns(['status_verifikasi', 'dokumen'])
                ->make(true);
        }
        
        // For export CSV
        if ($request->has('exportcsv')) {
            return $this->exportPeserta($request);
        }
        
        return view('admin.laporan.peserta', compact('statistics', 'kontingens', 'kategoris', 'kelompokUsias'));
    }
    
    public function pembayaran(Request $request)
    {
        // Create base query
        $baseQuery = Pembayaran::query();
        
        // Apply filters
        if ($request->has('status') && $request->status) {
            $baseQuery->where('status', $request->status);
        }
        
        if ($request->has('kontingen_id') && $request->kontingen_id) {
            $baseQuery->where('kontingen_id', $request->kontingen_id);
        }
        
        // Get statistics with separate queries
        $statistics = [
            'total_pembayaran' => (clone $baseQuery)->count(),
            'total_tagihan' => (clone $baseQuery)->sum('total_tagihan'),
            'total_lunas' => (clone $baseQuery)->where('status', 'lunas')->sum('total_tagihan'),
            'belum_bayar' => (clone $baseQuery)->where('status', 'belum_bayar')->count(),
            'menunggu_verifikasi' => (clone $baseQuery)->where('status', 'menunggu_verifikasi')->count(),
            'lunas' => (clone $baseQuery)->where('status', 'lunas')->count(),
        ];
        
        // Get filter options
        $kontingens = Kontingen::with('pelatih')->get();
        
        // Create query for paginated data with relationships
        $dataQuery = clone $baseQuery;
        $dataQuery->with(['kontingen.pelatih', 'kontingen.pesertas']);
        $pembayarans = $dataQuery->paginate(20);
        
        if ($request->expectsJson()) {
            return response()->json([
                'pembayarans' => $pembayarans,
                'statistics' => $statistics
            ]);
        }
        
        return view('admin.laporan.pembayaran', compact('pembayarans', 'statistics', 'kontingens'));
    }
    
    /**
     * Helper: Apply common header styling to a spreadsheet
     */
    private function applyHeaderStyle($sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFC107'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
    }

    /**
     * Helper: Apply border styling to data range
     */
    private function applyDataStyle($sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
    }

    /**
     * Helper: Auto-size all columns in a sheet
     */
    private function autoSizeColumns($sheet, string $startCol, string $endCol): void
    {
        foreach (range($startCol, $endCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * Helper: Stream spreadsheet as XLSX download
     */
    private function downloadXlsx(Spreadsheet $spreadsheet, string $filename)
    {
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
        }, $filename, $headers);
    }

    public function exportPeserta(Request $request)
    {
        $filename = 'laporan_peserta_' . date('Y-m-d_His') . '.xlsx';
        
        // Create base query
        $baseQuery = Peserta::query();
        
        // Apply filters
        if ($request->has('kontingen_id') && $request->kontingen_id) {
            $baseQuery->where('kontingen_id', $request->kontingen_id);
        }
        
        if ($request->has('kategori_id') && $request->kategori_id) {
            $baseQuery->whereHas('subkategoriLomba', function ($q) use ($request) {
                $q->where('kategori_id', $request->kategori_id);
            });
        }
        
        if ($request->has('kelompok_usia_id') && $request->kelompok_usia_id) {
            $baseQuery->where('kelompok_usia_id', $request->kelompok_usia_id);
        }
        
        if ($request->has('kelas_tanding_id') && $request->kelas_tanding_id) {
            $baseQuery->where('kelas_tanding_id', $request->kelas_tanding_id);
        }
        
        // Add relationships needed for export
        $baseQuery->with(['kontingen.pelatih', 'subkategoriLomba.kategoriLomba', 'kelompokUsia', 'kelasTanding', 'dokumenPesertas']);
        $pesertas = $baseQuery->get();
        
        // Create Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Peserta');

        // Title row
        $sheet->mergeCells('A1:O1');
        $sheet->setCellValue('A1', 'LAPORAN DATA PESERTA');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '212121']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Subtitle with date
        $sheet->mergeCells('A2:O2');
        $sheet->setCellValue('A2', 'Diekspor pada: ' . date('d/m/Y H:i'));
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '666666']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Header columns (row 4)
        $headerColumns = ['No', 'Nama Peserta', 'NIK', 'Jenis Kelamin', 'Tanggal Lahir', 'Berat Badan', 'Tinggi Badan (cm)',
                          'Kontingen', 'Pelatih', 'Kategori', 'Subkategori', 'Kelompok Usia',
                          'Kelas Tanding', 'Status Verifikasi', 'Dokumen'];
        
        $col = 'A';
        foreach ($headerColumns as $header) {
            $sheet->setCellValue($col . '4', $header);
            $col++;
        }
        $sheet->getRowDimension(4)->setRowHeight(25);
        $this->applyHeaderStyle($sheet, 'A4:O4');

        // Data rows (start from row 5)
        $row = 5;
        foreach ($pesertas as $index => $peserta) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $peserta->nama);
            $sheet->setCellValue('C' . $row, $peserta->nik ? "'".$peserta->nik : '-');
            $sheet->setCellValue('D' . $row, $peserta->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan');
            $sheet->setCellValue('E' . $row, $peserta->tanggal_lahir->format('d/m/Y'));
            $sheet->setCellValue('F' . $row, $peserta->berat_badan . ' kg');
            $sheet->setCellValue('G' . $row, $peserta->tinggi_badan ? $peserta->tinggi_badan . ' cm' : '-');
            $sheet->setCellValue('H' . $row, $peserta->kontingen->nama);
            $sheet->setCellValue('I' . $row, $peserta->kontingen->pelatih->nama);
            $sheet->setCellValue('J' . $row, $peserta->subkategoriLomba->kategoriLomba->nama);
            $sheet->setCellValue('K' . $row, $peserta->subkategoriLomba->nama);
            $sheet->setCellValue('L' . $row, $peserta->kelompokUsia->nama);
            $sheet->setCellValue('M' . $row, $peserta->kelasTanding->label_keterangan ?? '-');
            $sheet->setCellValue('N' . $row, ucfirst($peserta->status_verifikasi));
            
            // Dokumen text
            $dokumenList = [];
            foreach ($peserta->dokumenPesertas as $doc) {
                $url = asset('storage/' . $doc->file_path);
                $dokumenList[] = $doc->jenis_dokumen . ': ' . $url;
            }
            $dokumenText = empty($dokumenList) ? 'Tidak ada' : implode("\n", $dokumenList);
            $sheet->setCellValue('O' . $row, $dokumenText);
            // Enable text wrapping for document cell
            $sheet->getStyle('O' . $row)->getAlignment()->setWrapText(true);

            // Center align columns
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('N' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $row++;
        }

        // Apply borders to all data
        if ($pesertas->count() > 0) {
            $this->applyDataStyle($sheet, 'A5:O' . ($row - 1));

            // Zebra striping for better readability
            for ($r = 5; $r < $row; $r++) {
                if ($r % 2 == 1) {
                    $sheet->getStyle('A' . $r . ':O' . $r)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'FFF8E1'],
                        ],
                    ]);
                }
            }
        }

        // Auto-size columns
        $this->autoSizeColumns($sheet, 'A', 'O');

        // Freeze header row
        $sheet->freezePane('A5');

        return $this->downloadXlsx($spreadsheet, $filename);
    }
    
    public function exportPembayaran(Request $request)
    {
        $filename = 'laporan_pembayaran_' . date('Y-m-d_His') . '.xlsx';
        
        // Create base query
        $baseQuery = Pembayaran::query();
        
        // Apply filters
        if ($request->has('status') && $request->status) {
            $baseQuery->where('status', $request->status);
        }
        
        if ($request->has('kontingen_id') && $request->kontingen_id) {
            $baseQuery->where('kontingen_id', $request->kontingen_id);
        }
        
        // Add relationships needed for export
        $baseQuery->with(['kontingen.pelatih', 'kontingen.pesertas']);
        $pembayarans = $baseQuery->get();
        
        // Create Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Pembayaran');

        // Title row
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'LAPORAN DATA PEMBAYARAN');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '212121']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Subtitle with date
        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', 'Diekspor pada: ' . date('d/m/Y H:i'));
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '666666']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Header columns (row 4)
        $headerColumns = ['No', 'Kontingen', 'Pelatih', 'Total Tagihan', 'Status',
                          'Tanggal Verifikasi', 'Jumlah Peserta'];
        
        $col = 'A';
        foreach ($headerColumns as $header) {
            $sheet->setCellValue($col . '4', $header);
            $col++;
        }
        $sheet->getRowDimension(4)->setRowHeight(25);
        $this->applyHeaderStyle($sheet, 'A4:G4');

        // Data rows (start from row 5)
        $row = 5;
        $totalTagihan = 0;
        $totalPeserta = 0;

        foreach ($pembayarans as $index => $pembayaran) {
            $jumlahPeserta = $pembayaran->kontingen->pesertas->count();
            $totalTagihan += $pembayaran->total_tagihan;
            $totalPeserta += $jumlahPeserta;

            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $pembayaran->kontingen->nama);
            $sheet->setCellValue('C' . $row, $pembayaran->kontingen->pelatih->nama);
            $sheet->setCellValue('D' . $row, $pembayaran->total_tagihan);
            $sheet->setCellValue('E' . $row, ucfirst(str_replace('_', ' ', $pembayaran->status)));
            $sheet->setCellValue('F' . $row, $pembayaran->verified_at ? $pembayaran->verified_at->format('d/m/Y H:i') : '-');
            $sheet->setCellValue('G' . $row, $jumlahPeserta);

            // Format currency column
            $sheet->getStyle('D' . $row)->getNumberFormat()->setFormatCode('#,##0');
            // Center align columns
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $row++;
        }

        // Apply borders to all data
        if ($pembayarans->count() > 0) {
            $this->applyDataStyle($sheet, 'A5:G' . ($row - 1));

            // Zebra striping
            for ($r = 5; $r < $row; $r++) {
                if ($r % 2 == 1) {
                    $sheet->getStyle('A' . $r . ':G' . $r)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'FFF8E1'],
                        ],
                    ]);
                }
            }

            // Summary/Total row
            $sheet->setCellValue('A' . $row, '');
            $sheet->setCellValue('B' . $row, '');
            $sheet->setCellValue('C' . $row, 'TOTAL');
            $sheet->setCellValue('D' . $row, $totalTagihan);
            $sheet->setCellValue('E' . $row, '');
            $sheet->setCellValue('F' . $row, '');
            $sheet->setCellValue('G' . $row, $totalPeserta);

            $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray([
                'font' => ['bold' => true, 'size' => 11],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E8F5E9'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ]);
            $sheet->getStyle('D' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Auto-size columns
        $this->autoSizeColumns($sheet, 'A', 'G');

        // Freeze header row
        $sheet->freezePane('A5');

        return $this->downloadXlsx($spreadsheet, $filename);
    }
}