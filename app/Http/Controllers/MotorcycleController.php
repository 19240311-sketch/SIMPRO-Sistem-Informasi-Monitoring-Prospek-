<?php

namespace App\Http\Controllers;

use App\Models\Motorcycle;
use App\Models\MotorcycleColor;
use App\Models\MotorcycleInstallment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MotorcycleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Motorcycle::with(['colors', 'installments'])->orderBy('nama_model')->orderBy('varian');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_model', 'like', "%{$search}%")
                  ->orWhere('varian', 'like', "%{$search}%")
                  ->orWhere('merk', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status_aktif', $request->status === 'active');
        }

        $motorcycles = $query->get();

        return view('master.motorcycles.index', compact('motorcycles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'merk' => ['required', 'string', 'max:50'],
            'nama_model' => ['required', 'string', 'max:100'],
            'varian' => ['nullable', 'string', 'max:50'],
            'harga_otr' => ['required', 'numeric', 'min:0'],
            'warna' => ['nullable', 'string'],
            'tenor_bulan' => ['nullable', 'array'],
            'tenor_bulan.*' => ['required', 'integer', 'min:1'],
            'nominal_angsuran' => ['nullable', 'array'],
            'nominal_angsuran.*' => ['required', 'numeric', 'min:0'],
        ], [
            'merk.required' => 'Brand / Merk motor wajib diisi.',
            'nama_model.required' => 'Nama tipe / model motor wajib diisi.',
            'harga_otr.required' => 'Harga OTR resmi wajib diisi.',
            'harga_otr.numeric' => 'Harga OTR harus berupa nominal angka.',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $motorcycle = Motorcycle::create([
                'merk' => $validated['merk'],
                'nama_model' => $validated['nama_model'],
                'varian' => $validated['varian'] ?: 'Standard',
                'harga_otr' => (int) $validated['harga_otr'],
                'status_aktif' => true,
            ]);

            // Process colors
            if (!empty($validated['warna'])) {
                $colors = array_filter(array_map('trim', explode(',', $validated['warna'])));
                foreach ($colors as $color) {
                    if (!empty($color)) {
                        $motorcycle->colors()->create(['nama_warna' => $color]);
                    }
                }
            }

            // Process tenor & installments
            if (!empty($validated['tenor_bulan']) && !empty($validated['nominal_angsuran'])) {
                foreach ($validated['tenor_bulan'] as $idx => $tenor) {
                    $nominal = $validated['nominal_angsuran'][$idx] ?? null;
                    if ($tenor && $nominal) {
                        $motorcycle->installments()->updateOrCreate(
                            ['tenor_bulan' => (int) $tenor],
                            ['nominal_angsuran' => (int) $nominal]
                        );
                    }
                }
            }
        });

        return redirect()->route('master.motorcycles.index')
            ->with('success', 'Master data motor baru berhasil disimpan!');
    }

    public function update(Request $request, Motorcycle $motorcycle): RedirectResponse
    {
        $validated = $request->validate([
            'merk' => ['required', 'string', 'max:50'],
            'nama_model' => ['required', 'string', 'max:100'],
            'varian' => ['nullable', 'string', 'max:50'],
            'harga_otr' => ['required', 'numeric', 'min:0'],
            'status_aktif' => ['required', 'boolean'],
            'warna' => ['nullable', 'string'],
            'tenor_bulan' => ['nullable', 'array'],
            'tenor_bulan.*' => ['required', 'integer', 'min:1'],
            'nominal_angsuran' => ['nullable', 'array'],
            'nominal_angsuran.*' => ['required', 'numeric', 'min:0'],
        ], [
            'merk.required' => 'Brand / Merk motor wajib diisi.',
            'nama_model.required' => 'Nama tipe / model motor wajib diisi.',
            'harga_otr.required' => 'Harga OTR resmi wajib diisi.',
        ]);

        DB::transaction(function () use ($validated, $motorcycle) {
            $motorcycle->update([
                'merk' => $validated['merk'],
                'nama_model' => $validated['nama_model'],
                'varian' => $validated['varian'] ?: 'Standard',
                'harga_otr' => (int) $validated['harga_otr'],
                'status_aktif' => (bool) $validated['status_aktif'],
            ]);

            // Refresh colors
            $motorcycle->colors()->delete();
            if (!empty($validated['warna'])) {
                $colors = array_filter(array_map('trim', explode(',', $validated['warna'])));
                foreach ($colors as $color) {
                    if (!empty($color)) {
                        $motorcycle->colors()->create(['nama_warna' => $color]);
                    }
                }
            }

            // Refresh tenor & installments
            $motorcycle->installments()->delete();
            if (!empty($validated['tenor_bulan']) && !empty($validated['nominal_angsuran'])) {
                foreach ($validated['tenor_bulan'] as $idx => $tenor) {
                    $nominal = $validated['nominal_angsuran'][$idx] ?? null;
                    if ($tenor && $nominal) {
                        $motorcycle->installments()->create([
                            'tenor_bulan' => (int) $tenor,
                            'nominal_angsuran' => (int) $nominal,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('master.motorcycles.index')
            ->with('success', 'Master data motor berhasil diperbarui!');
    }

    public function toggleStatus(Motorcycle $motorcycle): RedirectResponse
    {
        $motorcycle->update([
            'status_aktif' => !$motorcycle->status_aktif,
        ]);

        $statusText = $motorcycle->status_aktif ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('master.motorcycles.index')
            ->with('success', "Status motor {$motorcycle->nama_model} berhasil {$statusText}.");
    }

    public function destroy(Motorcycle $motorcycle): RedirectResponse
    {
        if ($motorcycle->prospects()->exists()) {
            return redirect()->route('master.motorcycles.index')
                ->with('error', 'Motor ini tidak dapat dihapus karena sudah memiliki relasi data prospek. Silakan nonaktifkan statusnya.');
        }

        $motorcycle->installments()->delete();
        $motorcycle->colors()->delete();
        $motorcycle->delete();

        return redirect()->route('master.motorcycles.index')
            ->with('success', 'Data motor berhasil dihapus.');
    }

    /**
     * Delete multiple selected motorcycles
     */
    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = $request->input('selected_ids', []);
        if (empty($ids) || !is_array($ids)) {
            return redirect()->route('master.motorcycles.index')
                ->with('error', 'Tidak ada data motor yang dipilih untuk dihapus.');
        }

        $motorcycles = Motorcycle::whereIn('id', $ids)->get();
        $deletedCount = 0;
        $skippedCount = 0;

        foreach ($motorcycles as $moto) {
            if ($moto->prospects()->exists()) {
                $skippedCount++;
            } else {
                $moto->installments()->delete();
                $moto->colors()->delete();
                $moto->delete();
                $deletedCount++;
            }
        }

        $msg = "Berhasil menghapus {$deletedCount} data motor terpilih.";
        if ($skippedCount > 0) {
            $msg .= " ({$skippedCount} motor tidak dihapus karena sudah dipakai pada data prospek).";
        }

        return redirect()->route('master.motorcycles.index')
            ->with('success', $msg);
    }

    /**
     * Delete all motorcycles in master (except those tied to prospects)
     */
    public function deleteAll(): RedirectResponse
    {
        $motorcycles = Motorcycle::all();
        $deletedCount = 0;
        $skippedCount = 0;

        foreach ($motorcycles as $moto) {
            if ($moto->prospects()->exists()) {
                $skippedCount++;
            } else {
                $moto->installments()->delete();
                $moto->colors()->delete();
                $moto->delete();
                $deletedCount++;
            }
        }

        $msg = "Seluruh Master Data Motor berhasil dibersihkan ({$deletedCount} motor dihapus).";
        if ($skippedCount > 0) {
            $msg .= " ({$skippedCount} motor tetap aman karena digunakan pada data prospek).";
        }

        return redirect()->route('master.motorcycles.index')
            ->with('success', $msg);
    }

    /**
     * Download Sample CSV Template for Master Data Motor
     */
    public function downloadTemplate(): StreamedResponse
    {
        $fileName = 'Template_Master_Data_Motor_Yamaha.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM for Excel compatibility

            // CSV Header
            fputcsv($handle, [
                'Brand',
                'Nama Model',
                'Varian',
                'Harga OTR',
                'Warna (Pisahkan Koma)',
                'Angsuran 11 Bulan',
                'Angsuran 23 Bulan',
                'Angsuran 35 Bulan',
                'Angsuran 47 Bulan',
            ]);

            // Sample Rows
            fputcsv($handle, [
                'Yamaha',
                'Fazzio Hybrid',
                'Neo',
                '22700000',
                'Neo Silver, Neo Dull Blue, Neo Red, Neo Orange, Neo Mint',
                '2250000',
                '1290000',
                '980000',
                '835000',
            ]);

            fputcsv($handle, [
                'Yamaha',
                'Fazzio Hybrid',
                'Lux',
                '23350000',
                'Lux White Pearl, Lux Prestige Silver, Lux Matte Black',
                '2315000',
                '1325000',
                '1010000',
                '860000',
            ]);

            fputcsv($handle, [
                'Yamaha',
                'Grand Filano Hybrid',
                'Neo',
                '27050000',
                'Neo Dull Blue, Neo Matte Black, Neo Red, Neo Pink Mauve',
                '2680000',
                '1535000',
                '1170000',
                '995000',
            ]);

            fputcsv($handle, [
                'Yamaha',
                'NMAX Turbo 155',
                'Tech MAX Ultimate',
                '45250000',
                'Magma Black, Elixir Dark',
                '4480000',
                '2565000',
                '1955000',
                '1665000',
            ]);

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Import Master Data Motor from dropped/uploaded Excel (.xlsx, .xls), CSV, or JSON file
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file_motor' => ['required', 'file', 'max:15360'], // Max 15MB
        ], [
            'file_motor.required' => 'Silakan pilih atau drop file data motor yang ingin diimpor.',
            'file_motor.file' => 'File yang diunggah tidak valid.',
            'file_motor.max' => 'Ukuran file maksimal 15MB.',
        ]);

        $file = $request->file('file_motor');
        $extension = strtolower($file->getClientOriginalExtension());
        $filePath = $file->getRealPath();

        $importedCount = 0;

        try {
            DB::beginTransaction();

            if (in_array($extension, ['xlsx', 'xls'])) {
                // Process Excel Workbook (Multi-Sheet Dealer Matrix & Flat Table)
                $importedCount = $this->processExcelWorkbook($filePath);
            } elseif ($extension === 'json') {
                $content = file_get_contents($filePath);
                $jsonData = json_decode($content, true);

                if (!is_array($jsonData)) {
                    throw new \Exception('Format JSON tidak valid atau bukan berupa array data motor.');
                }

                foreach ($jsonData as $item) {
                    $this->processMotorDataRow($item);
                    $importedCount++;
                }
            } else {
                // Parse CSV / TXT / TSV
                $handle = fopen($filePath, 'r');
                if (!$handle) {
                    throw new \Exception('Gagal membaca file data motor.');
                }

                // Detect delimiter (comma, semicolon, tab)
                $firstLine = fgets($handle);
                rewind($handle);

                $delimiter = ',';
                if (strpos($firstLine, ';') !== false && substr_count($firstLine, ';') > substr_count($firstLine, ',')) {
                    $delimiter = ';';
                } elseif (strpos($firstLine, "\t") !== false && substr_count($firstLine, "\t") > substr_count($firstLine, ',')) {
                    $delimiter = "\t";
                }

                // Read header
                $rawHeaders = fgetcsv($handle, 0, $delimiter);
                if (!$rawHeaders) {
                    throw new \Exception('File CSV kosong atau tidak memiliki baris judul (header).');
                }

                $headers = array_map(function ($h) {
                    return strtolower(trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $h)));
                }, $rawHeaders);

                while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                    if (empty(array_filter($row))) {
                        continue;
                    }

                    $mappedRow = [];
                    foreach ($headers as $index => $colName) {
                        $mappedRow[$colName] = isset($row[$index]) ? trim($row[$index]) : '';
                    }

                    $this->processMotorCsvRow($mappedRow, $headers);
                    $importedCount++;
                }

                fclose($handle);
            }

            DB::commit();

            return redirect()->route('master.motorcycles.index')
                ->with('success', "Berhasil mengimpor {$importedCount} tipe motor beserta harga OTR, warna, dan opsi angsuran dari file Anda!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('master.motorcycles.index')
                ->with('error', 'Gagal mengimpor file: ' . $e->getMessage());
        }
    }

    /**
     * Process Excel Workbook across all sheets (Supports multi-block dealer rate matrices)
     */
    private function processExcelWorkbook(string $filePath): int
    {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $totalImported = 0;

        foreach ($spreadsheet->getAllSheets() as $sheet) {
            $sheetTitle = trim($sheet->getTitle());
            $rows = $sheet->toArray(null, true, true, true); // Returns array keyed [rowNumber][colLetter]

            if (empty($rows)) {
                continue;
            }

            $importedFromSheet = $this->parseDealerSheetRows($rows, $sheetTitle);
            $totalImported += $importedFromSheet;
        }

        return $totalImported;
    }

    /**
     * Parse single worksheet that may contain one or more dealer table blocks
     */
    /**
     * Parse single worksheet that may contain one or more dealer table blocks
     */
    private function parseDealerSheetRows(array $rows, string $sheetTitle): int
    {
        $imported = 0;
        $rowKeys = array_values(array_keys($rows));
        $totalRows = count($rowKeys);
        $i = 0;

        // Clean any accidental invalid motor entries (e.g. containing percentage symbols)
        Motorcycle::where('nama_model', 'like', '%\%%')->delete();

        while ($i < $totalRows) {
            $rowNum = $rowKeys[$i];
            $row = $rows[$rowNum];

            // Check if this row is a genuine Model Header row (contains actual motor name and OTR price)
            $modelInfo = $this->detectMotorBlockHeader($row, $sheetTitle);

            if ($modelInfo !== null) {
                $namaModel = $modelInfo['nama_model'];
                $varian = $modelInfo['varian'];
                $hargaOtr = $modelInfo['harga_otr'];

                // Scan next 1-4 rows for Tenor Header (e.g. 11, 17, 23, 29, 35)
                $tenorColMap = [];
                $tenorRowIdx = -1;

                for ($j = $i + 1; $j <= min($i + 4, $totalRows - 1); $j++) {
                    $testRowNum = $rowKeys[$j];
                    $testRow = $rows[$testRowNum];

                    $detectedTenors = $this->detectTenorRow($testRow);
                    if (count($detectedTenors) >= 2) {
                        $tenorColMap = $detectedTenors;
                        $tenorRowIdx = $j;
                        break;
                    }
                }

                // If tenors found, scan next rows for first DP/Angsuran row (standard base installment)
                $installments = [];
                $lastDataRowIdx = $i;

                if ($tenorRowIdx !== -1 && !empty($tenorColMap)) {
                    for ($k = $tenorRowIdx + 1; $k <= min($tenorRowIdx + 30, $totalRows - 1); $k++) {
                        $dataRowNum = $rowKeys[$k];
                        $dataRow = $rows[$dataRowNum];

                        // Stop if we hit a blank row or the next motor header
                        if (empty(array_filter($dataRow)) || $this->detectMotorBlockHeader($dataRow, $sheetTitle) !== null) {
                            break;
                        }

                        $lastDataRowIdx = $k;

                        if (empty($installments)) {
                            $angsuranList = [];
                            foreach ($tenorColMap as $colLetter => $tenor) {
                                $val = $this->parseNumber($dataRow[$colLetter] ?? 0);
                                if ($val > 100000) { // Valid monthly installment amount
                                    $angsuranList[$tenor] = $val;
                                }
                            }

                            if (!empty($angsuranList)) {
                                $installments = $angsuranList;
                            }
                        }
                    }
                }

                // Save or Update to Database
                $this->saveMotorcycleRecord([
                    'merk' => 'Yamaha',
                    'nama_model' => $namaModel,
                    'varian' => $varian,
                    'harga_otr' => $hargaOtr,
                    'warna' => $this->getDefaultColorsForModel($namaModel, $varian),
                    'installments' => $installments,
                ]);

                $imported++;

                // Fast-forward row pointer past the entire DP matrix block of this motor
                $i = max($i + 1, $lastDataRowIdx + 1);
                continue;
            }

            $i++;
        }

        return $imported;
    }

    /**
     * Detect if a row represents the start of a motorcycle block
     */
    private function detectMotorBlockHeader(array $row, string $sheetTitle): ?array
    {
        // 1. If ANY cell in this row contains a percentage symbol (%), it is a DP row, NOT a motor header!
        foreach ($row as $val) {
            if ($val !== null && strpos((string)$val, '%') !== false) {
                return null;
            }
        }

        $textCandidates = [];
        $otrCandidates = [];

        foreach ($row as $col => $val) {
            if ($val === null || $val === '') {
                continue;
            }

            $num = $this->parseNumber($val);
            $cleanStr = trim((string)$val);

            // Filter out table headers, percentages, or pure numeric strings
            if (
                strlen($cleanStr) >= 3 &&
                preg_match('/[a-zA-Z]/', $cleanStr) &&
                !preg_match('/^(dp|uang muka|angsuran|program|total|no|tenor|bulan|diskon|subsidi|rate)/i', $cleanStr) &&
                !preg_match('/^\d+[\s.,%]/', $cleanStr)
            ) {
                $textCandidates[] = $cleanStr;
            }

            // Check if number is in valid motorcycle OTR price range (e.g. 15,000,000 to 200,000,000)
            if ($num >= 15000000 && $num <= 200000000) {
                $otrCandidates[] = $num;
            }
        }

        // Must have BOTH a valid text name candidate AND an OTR candidate on the same row
        if (empty($textCandidates) || empty($otrCandidates)) {
            return null;
        }

        $rawModel = $textCandidates[0];
        $hargaOtr = $otrCandidates[0];

        // Parse Model Name & Variant
        $parsed = $this->extractModelAndVariant($rawModel, $sheetTitle);

        return [
            'nama_model' => $parsed['nama_model'],
            'varian' => $parsed['varian'],
            'harga_otr' => $hargaOtr,
        ];
    }

    /**
     * Detect tenor header row (contains numbers like 11, 17, 23, 29, 35, 47, etc.)
     */
    private function detectTenorRow(array $row): array
    {
        $tenorMap = [];
        $validTenors = [11, 12, 17, 18, 23, 24, 29, 30, 35, 36, 41, 47, 48, 59, 60];

        foreach ($row as $col => $val) {
            if ($val === null || $val === '') {
                continue;
            }

            $valNum = (int) preg_replace('/[^0-9]/', '', (string)$val);
            if (in_array($valNum, $validTenors)) {
                // Keep the first group of regular angsuran (prevent duplicate tenors from ANGSURAN PROGRAM)
                if (!in_array($valNum, $tenorMap)) {
                    $tenorMap[$col] = $valNum;
                }
            }
        }

        return $tenorMap;
    }

    /**
     * Helper to split full model text into Clean Model Name and Variant
     */
    private function extractModelAndVariant(string $rawText, string $sheetTitle): array
    {
        $clean = trim(preg_replace('/\s+/', ' ', $rawText));
        $clean = preg_replace('/^(yamaha|all new|new)\s+/i', '', $clean);

        $variantKeywords = [
            'Tech MAX Ultimate' => 'Tech MAX Ultimate',
            'Tech MAX' => 'Tech MAX',
            'Turbo Ultimate' => 'Tech MAX Ultimate',
            'Turbo' => 'Turbo',
            'Connected / ABS' => 'Connected/ABS',
            'Connected/ABS' => 'Connected/ABS',
            'ABS' => 'ABS',
            'Connected' => 'Connected',
            'Neo S' => 'Neo S',
            'Neo' => 'Neo',
            'Lux' => 'Lux',
            'Alpha' => 'Alpha',
            'Cyber City' => 'Cyber City',
            'Standard' => 'Standard',
            'Monster Energy' => 'Monster Energy MotoGP',
            'World GP' => 'World GP 60th',
        ];

        $foundVariant = 'Standard';
        $modelBase = $clean;

        foreach ($variantKeywords as $key => $varName) {
            if (stripos($clean, $key) !== false) {
                $foundVariant = $varName;
                $modelBase = trim(preg_replace('/' . preg_quote($key, '/') . '/i', '', $clean));
                break;
            }
        }

        // If modelBase became empty, fallback to sheetTitle
        if (empty($modelBase)) {
            $modelBase = $sheetTitle;
        }

        return [
            'nama_model' => ucwords(strtolower($modelBase)),
            'varian' => $foundVariant,
        ];
    }

    /**
     * Default popular colors for Yamaha models if not specified in spreadsheet
     */
    private function getDefaultColorsForModel(string $namaModel, string $varian): string
    {
        $upper = strtoupper($namaModel . ' ' . $varian);

        if (strpos($upper, 'FAZZIO') !== false) {
            if (strpos($upper, 'LUX') !== false) {
                return 'Lux White Pearl, Lux Prestige Silver, Lux Matte Black';
            }
            return 'Neo Silver, Neo Dull Blue, Neo Red, Neo Mint, Neo Yellow, Neo Orange';
        }

        if (strpos($upper, 'FILANO') !== false) {
            if (strpos($upper, 'LUX') !== false) {
                return 'Lux White Pearl, Lux Dark Magma';
            }
            return 'Neo Dull Blue, Neo Matte Black, Neo Red, Neo Pink Mauve, Neo Yellow';
        }

        if (strpos($upper, 'NMAX') !== false) {
            if (strpos($upper, 'TECH MAX') !== false) {
                return 'Magma Black, Elixir Dark Silver';
            }
            return 'Matte Black, Metallic Red, Dull Blue, Ceramic White';
        }

        if (strpos($upper, 'AEROX') !== false) {
            return 'Cyber City, Metallic Black, Silver Cyan, Racing Blue, Signature Black';
        }

        if (strpos($upper, 'XMAX') !== false) {
            return 'Magma Black, Matte Blue, Metallic Red, Premium Black';
        }

        if (strpos($upper, 'XSR') !== false) {
            return 'Metallic Black Elegance, Matte Silver Premium, Light Blue Wanderlust';
        }

        if (strpos($upper, 'WR') !== false) {
            return 'Yamaha Racing Blue, Black Metallic';
        }

        return 'Hitam, Putih, Merah, Biru, Abu-Abu';
    }

    /**
     * Save motorcycle with colors and installments
     */
    private function saveMotorcycleRecord(array $data): Motorcycle
    {
        $motorcycle = Motorcycle::updateOrCreate(
            [
                'merk' => $data['merk'] ?? 'Yamaha',
                'nama_model' => $data['nama_model'],
                'varian' => $data['varian'] ?? 'Standard',
            ],
            [
                'harga_otr' => (int) $data['harga_otr'],
                'status_aktif' => true,
            ]
        );

        // Update Colors
        if (!empty($data['warna'])) {
            $motorcycle->colors()->delete();
            $colors = array_filter(array_map('trim', explode(',', $data['warna'])));
            foreach ($colors as $col) {
                if (!empty($col)) {
                    $motorcycle->colors()->create(['nama_warna' => $col]);
                }
            }
        }

        // Update Installments
        if (!empty($data['installments']) && is_array($data['installments'])) {
            $motorcycle->installments()->delete();
            foreach ($data['installments'] as $tenor => $nominal) {
                if ((int)$nominal > 0) {
                    $motorcycle->installments()->create([
                        'tenor_bulan' => (int) $tenor,
                        'nominal_angsuran' => (int) $nominal,
                    ]);
                }
            }
        }

        return $motorcycle;
    }

    /**
     * Helper to clean numeric values from formatted currency strings
     */
    private function parseNumber($value): int
    {
        if (is_numeric($value)) {
            return (int) $value;
        }
        $cleaned = preg_replace('/[^0-9]/', '', (string)$value);
        return (int) $cleaned;
    }

    /**
     * Process mapped CSV row into Motorcycle record
     */
    private function processMotorCsvRow(array $row, array $headers): void
    {
        // Find Brand
        $brand = 'Yamaha';
        foreach (['brand', 'merk', 'merek'] as $k) {
            if (!empty($row[$k])) { $brand = $row[$k]; break; }
        }

        // Find Model Name
        $namaModel = '';
        foreach (['nama model', 'nama_model', 'model', 'tipe', 'nama motor', 'nama_motor', 'tipe motor', 'tipe_motor'] as $k) {
            if (!empty($row[$k])) { $namaModel = $row[$k]; break; }
        }

        if (empty($namaModel)) {
            return;
        }

        // Find Variant
        $varian = 'Standard';
        foreach (['varian', 'variant', 'tipe varian', 'tipe_varian'] as $k) {
            if (!empty($row[$k])) { $varian = $row[$k]; break; }
        }

        // Find OTR Price
        $hargaOtr = 0;
        foreach (['harga otr', 'harga_otr', 'otr', 'harga', 'price'] as $k) {
            if (!empty($row[$k])) { $hargaOtr = $this->parseNumber($row[$k]); break; }
        }

        // Find Colors
        $warnaString = '';
        foreach (['warna (pisahkan koma)', 'warna', 'colors', 'pilihan warna', 'pilihan_warna'] as $k) {
            if (!empty($row[$k])) { $warnaString = $row[$k]; break; }
        }

        $motorcycle = Motorcycle::updateOrCreate(
            [
                'merk' => $brand,
                'nama_model' => $namaModel,
                'varian' => $varian,
            ],
            [
                'harga_otr' => $hargaOtr,
                'status_aktif' => true,
            ]
        );

        // Update Colors
        if (!empty($warnaString)) {
            $motorcycle->colors()->delete();
            $colors = array_filter(array_map('trim', explode(',', $warnaString)));
            foreach ($colors as $col) {
                if (!empty($col)) {
                    $motorcycle->colors()->create(['nama_warna' => $col]);
                }
            }
        }

        // Update Tenor & Installments from dynamic columns (e.g. 11, 23, 35, 47, angsuran 11 bulan, etc.)
        $tenorsFound = [];
        foreach ($headers as $headerCol) {
            $nominal = $this->parseNumber($row[$headerCol] ?? 0);
            if ($nominal > 0) {
                // Check if column matches tenor pattern (e.g. "11", "tenor 11", "angsuran 11 bulan", "11 bulan")
                if (preg_match('/(?:tenor|angsuran|bulan)?\s*(\d{1,2})\s*(?:bln|bulan)?/i', $headerCol, $matches)) {
                    $tenorNumber = (int) $matches[1];
                    if ($tenorNumber >= 1 && $tenorNumber <= 60) {
                        $tenorsFound[$tenorNumber] = $nominal;
                    }
                }
            }
        }

        if (!empty($tenorsFound)) {
            $motorcycle->installments()->delete();
            foreach ($tenorsFound as $tenor => $nominal) {
                $motorcycle->installments()->create([
                    'tenor_bulan' => $tenor,
                    'nominal_angsuran' => $nominal,
                ]);
            }
        }
    }

    /**
     * Process JSON row
     */
    private function processMotorDataRow(array $item): void
    {
        $brand = $item['merk'] ?? $item['brand'] ?? 'Yamaha';
        $namaModel = $item['nama_model'] ?? $item['model'] ?? $item['nama_motor'] ?? '';
        $varian = $item['varian'] ?? $item['variant'] ?? 'Standard';
        $hargaOtr = $this->parseNumber($item['harga_otr'] ?? $item['otr'] ?? 0);

        if (empty($namaModel)) {
            return;
        }

        $motorcycle = Motorcycle::updateOrCreate(
            [
                'merk' => $brand,
                'nama_model' => $namaModel,
                'varian' => $varian,
            ],
            [
                'harga_otr' => $hargaOtr,
                'status_aktif' => true,
            ]
        );

        // Process colors
        if (isset($item['warna'])) {
            $motorcycle->colors()->delete();
            $colors = is_array($item['warna']) ? $item['warna'] : explode(',', $item['warna']);
            foreach ($colors as $col) {
                $colTrim = trim($col);
                if (!empty($colTrim)) {
                    $motorcycle->colors()->create(['nama_warna' => $colTrim]);
                }
            }
        }

        // Process installments
        if (isset($item['angsuran']) && is_array($item['angsuran'])) {
            $motorcycle->installments()->delete();
            foreach ($item['angsuran'] as $tenor => $nominal) {
                $motorcycle->installments()->create([
                    'tenor_bulan' => (int) $tenor,
                    'nominal_angsuran' => $this->parseNumber($nominal),
                ]);
            }
        }
    }
}
