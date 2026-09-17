<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\GameCategory;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class QuestionController extends Controller
{
    /**
     * Tampilkan halaman kelola soal
     */
    public function index(Request $request)
    {
        $games = GameCategory::all();
        
        $query = Question::select('questions.*')
            ->join('game_categories', 'questions.game_category_id', '=', 'game_categories.id')
            ->with(['gameCategory', 'answers']);
        
        // Filter berdasarkan kategori game jika dipilih
        if ($request->filled('game_category_id')) {
            $query->where('questions.game_category_id', $request->game_category_id);
        }
        
        // Filter berdasarkan level jika dipilih
        if ($request->filled('level')) {
            $query->where('questions.level', $request->level);
        }

        // Pencarian soal
        if ($request->filled('search')) {
            $query->where('questions.teks_soal', 'like', '%' . $request->search . '%');
        }
        
        $questions = $query->orderBy('game_categories.nama_game', 'asc')
            ->orderBy('questions.level', 'asc')
            ->orderBy('questions.id', 'desc')
            ->paginate(10)->withQueryString();
        
        // Menghitung statistik sederhana
        $totalSoal = Question::count();
        $totalGame = $games->count();
        $totalLevelMax = Question::max('level') ?? 0;

        return view('admin.manage_questions', compact('questions', 'games', 'totalSoal', 'totalGame', 'totalLevelMax'));
    }

    /**
     * Hapus soal beserta pilihan jawabannya
     */
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        
        // Eloquent cascade or manual delete of answers
        $question->answers()->delete();
        $question->delete();

        return redirect()->back()->with('success', 'Soal berhasil dihapus!');
    }

    /**
     * Kosongkan semua soal di database
     */
    public function destroyAll()
    {
        // Delete all answers first, then all questions to maintain referential integrity
        \DB::table('answers')->delete();
        \DB::table('questions')->delete();

        return redirect()->back()->with('success', 'Berhasil mengosongkan seluruh bank soal!');
    }

    /**
     * Perbarui data soal dan jawabannya
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'game_category_id' => 'required|exists:game_categories,id',
            'level' => 'required|integer|min:1',
            'teks_soal' => 'required',
            'pembahasan' => 'nullable|string',
            'waktu' => 'required|integer|min:1',
            'jawaban_a' => 'nullable|string',
            'jawaban_b' => 'nullable|string',
            'jawaban_c' => 'nullable|string',
            'jawaban_d' => 'nullable|string',
            'kunci_jawaban' => 'required|in:A,B,C,D',
        ]);

        $question = Question::findOrFail($id);
        $question->update([
            'game_category_id' => $request->game_category_id,
            'level' => $request->level,
            'teks_soal' => $request->teks_soal,
            'waktu' => $request->waktu,
            'pembahasan' => $request->pembahasan,
        ]);

        $options = [
            'A' => $request->jawaban_a,
            'B' => $request->jawaban_b,
            'C' => $request->jawaban_c,
            'D' => $request->jawaban_d,
        ];

        // Delete old answers and create new ones
        $question->answers()->delete();
        foreach ($options as $key => $value) {
            if (!empty($value)) {
                Answer::create([
                    'question_id' => $question->id,
                    'teks_jawaban' => $value,
                    'is_correct' => ($key === $request->kunci_jawaban),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Soal berhasil diperbarui!');
    }

    /**
     * Unduh file Excel template yang sudah disiapkan
     */
    public function downloadTemplate()
    {
        $filePath = storage_path('app/templates/Template Soal Umathink NEW.xlsx');

        if (!file_exists($filePath)) {
            return redirect()->back()->withErrors(['File template tidak ditemukan di server.']);
        }

        return response()->download($filePath, 'Template Soal Umathink NEW.xlsx');
    }

    /**
     * Proses impor soal dari Excel atau CSV
     */
    public function import(Request $request)
{
    ini_set('memory_limit', '512M');
    set_time_limit(180);

    $request->validate([
        'file_soal' => 'required|file|mimes:xlsx,xls,csv,txt|max:4096',
    ], [
        'file_soal.required' => 'Pilih file terlebih dahulu!',
        'file_soal.mimes' => 'Format file harus berupa .xlsx, .xls, atau .csv',
        'file_soal.max' => 'Ukuran file maksimal adalah 4MB',
    ]);

    $file = $request->file('file_soal');
    $extension = strtolower($file->getClientOriginalExtension());
    $rows = [];

    // Baca CSV / TXT
    if ($extension === 'csv' || $extension === 'txt') {
        $delimiter = ',';

        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            $firstLine = fgets($handle);

            if (
                strpos($firstLine, ';') !== false &&
                strpos($firstLine, ',') === false
            ) {
                $delimiter = ';';
            }

            rewind($handle);

            while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
                $rows[] = $data;
            }

            fclose($handle);
        }
    } else {
        // Baca Excel
        try {
            $reader = IOFactory::createReaderForFile($file->getRealPath());
            $reader->setReadDataOnly(true);

            $spreadsheet = $reader->load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors([
                'Gagal membaca file Excel: ' . $e->getMessage()
            ]);
        }
    }

    if (count($rows) <= 1) {
        return redirect()->back()->withErrors([
            'File kosong atau hanya berisi header!'
        ]);
    }

    /*
     * Template baru:
     *
     * Nama Game
     * Level
     * Tipe Soal
     * Soal
     * A
     * B
     * C
     * D
     * BENAR
     * DATA MATCHING
     * WAKTU
     * PEMBAHASAN
     */

    $headerIndex = null;
    $headerMap = [];

    foreach ($rows as $key => $row) {
        $normalized = array_map(function ($value) {
            return strtolower(trim((string) $value));
        }, $row);

        if (
            in_array('nama game', $normalized) ||
            in_array('nama_game', $normalized) ||
            in_array('kategori', $normalized)
        ) {
            $headerIndex = $key;

            foreach ($normalized as $index => $column) {
                $headerMap[$column] = $index;
            }

            break;
        }
    }

    if ($headerIndex === null) {
        return redirect()->back()->withErrors([
            'Header template tidak ditemukan. Gunakan Template Soal Umathink NEW.xlsx.'
        ]);
    }

    // Buang semua baris sebelum header
    $rows = array_slice($rows, $headerIndex + 1);

    // Helper mengambil nilai berdasarkan nama kolom
    $getValue = function ($row, array $names) use ($headerMap) {
        foreach ($names as $name) {
            $name = strtolower(trim($name));

            if (isset($headerMap[$name])) {
                $index = $headerMap[$name];

                return isset($row[$index])
                    ? trim((string) $row[$index])
                    : '';
            }
        }

        return '';
    };

    // Helper untuk membaca DATA MATCHING
    $parseMatching = function (string $value): array {
        $value = trim($value);

        if ($value === '') {
            return [];
        }

        /*
         * Format Excel:
         * Terang=Cahaya;Cerdas=Pandai;Luas=Lebar
         */

        $parts = preg_split('/\s*;\s*/', $value);
        $result = [];

        foreach ($parts as $part) {
            $part = trim($part);

            if ($part === '') {
                continue;
            }

            if (!str_contains($part, '=')) {
                continue;
            }

            [$left, $right] = array_map(
                'trim',
                explode('=', $part, 2)
            );

            if ($left !== '' && $right !== '') {
                $result[] = [
                    'kiri' => $left,
                    'kanan' => $right,
                ];
            }
        }

        return $result;
    };

    $importedCount = 0;
    $skippedCount = 0;

    \DB::beginTransaction();

    try {
        foreach ($rows as $row) {

            // Lewati baris kosong
            $hasData = count(array_filter($row, function ($value) {
                return trim((string) $value) !== '';
            })) > 0;

            if (!$hasData) {
                continue;
            }

            $kategoriName = $getValue($row, [
                'nama game',
                'nama_game',
                'kategori',
                'game'
            ]);

            $levelStr = $getValue($row, ['level']);
            $level = $levelStr !== '' ? intval($levelStr) : 0;

            $teksSoal = $getValue($row, [
                'soal',
                'teks soal',
                'teks_soal'
            ]);
            $tipeSoal = strtolower($getValue($row, [
                'tipe soal',
                'tipe_soal'
            ]));

            $tipeSoal = str_replace(
                [' ', '-'],
                '_',
                $tipeSoal
            );

            $jawabanA = $getValue($row, ['a']);
            $jawabanB = $getValue($row, ['b']);
            $jawabanC = $getValue($row, ['c']);
            $jawabanD = $getValue($row, ['d']);

            $kunci = strtoupper($getValue($row, [
                'benar',
                'kunci',
                'kunci jawaban',
                'kunci_jawaban'
            ]));

            $dataMatchingText = $getValue($row, [
                'data matching',
                'data_matching'
            ]);

            $waktuText = $getValue($row, ['waktu']);
            $waktuDetik = $waktuText !== ''
                ? intval($waktuText)
                : 60;

            $pembahasan = $getValue($row, ['pembahasan']);

            if (!in_array($tipeSoal, ['pilihan_ganda', 'pernyataan', 'matching'], true)) {
                $skippedCount++;
                continue;
            }

            if (
                $kategoriName === '' ||
                $level <= 0 ||
                $teksSoal === ''
            ) {
                $skippedCount++;
                continue;
            }

            // Cari / buat game
            $gameCategory = GameCategory::firstOrCreate(
                ['nama_game' => $kategoriName],
                ['jumlah_level' => $level]
            );

            // Sinkronisasi jumlah level
            if ($level > $gameCategory->jumlah_level) {
                $gameCategory->update([
                    'jumlah_level' => $level
                ]);
            }

            /*
             * Hitung nomor soal dalam level.
             *
             * 1, 2, 3, 4, 5,
             * 6, 7, 8, 9, 10,
             * dst.
             */
            $nomorSoal = Question::where(
                'game_category_id',
                $gameCategory->id
            )
                ->where('level', $level)
                ->count() + 1;

            /*
             * Soal 5, 10, 15, 20, dst.
             * otomatis menjadi MATCHING.
             */
            $seharusnyaMatching = ($nomorSoal % 5 === 0);
            $isMatching = ($tipeSoal === 'matching');

            if ($seharusnyaMatching !== $isMatching) {
                $skippedCount++;
                continue;
            }

            if ($isMatching) {

                if ($dataMatchingText === '') {
                    $skippedCount++;
                    continue;
                }

                $matchingPairs = $parseMatching($dataMatchingText);

                // Minimal 2 pasangan untuk matching.
                if (count($matchingPairs) < 2) {
                    $skippedCount++;
                    continue;
                }

                Question::create([
                    'game_category_id' => $gameCategory->id,
                    'level' => $level,
                    'teks_soal' => $teksSoal,
                    'tipe_soal' => 'matching',
                    'data_matching' => $matchingPairs,
                    'poin' => 10,
                    'kurang_poin' => 5,
                    'waktu' => $waktuDetik > 0 ? $waktuDetik : 60,
                    'pembahasan' => $pembahasan,
                ]);

            } elseif ($tipeSoal === 'pernyataan') {

                // Tipe pernyataan hanya menggunakan kunci YA atau SALAH.
                $kunciPernyataan = strtoupper(trim($kunci));

                if (!in_array($kunciPernyataan, ['YA', 'SALAH'], true)) {
                    $skippedCount++;
                    continue;
                }

                $question = Question::create([
                    'game_category_id' => $gameCategory->id,
                    'level' => $level,
                    'teks_soal' => $teksSoal,
                    'tipe_soal' => 'pernyataan',
                    'data_matching' => null,
                    'poin' => 10,
                    'kurang_poin' => 5,
                    'waktu' => $waktuDetik > 0 ? $waktuDetik : 60,
                    'pembahasan' => $pembahasan,
                ]);

                Answer::create([
                    'question_id' => $question->id,
                    'teks_jawaban' => 'YA',
                    'is_correct' => ($kunciPernyataan === 'YA'),
                ]);

                Answer::create([
                    'question_id' => $question->id,
                    'teks_jawaban' => 'SALAH',
                    'is_correct' => ($kunciPernyataan === 'SALAH'),
                ]);

            } else {

                // Tipe pilihan_ganda menggunakan A, B, C, D.
                $options = [
                    'A' => $jawabanA,
                    'B' => $jawabanB,
                    'C' => $jawabanC,
                    'D' => $jawabanD,
                ];

                if (
                    !in_array($kunci, ['A', 'B', 'C', 'D'], true) ||
                    empty($options[$kunci])
                ) {
                    $skippedCount++;
                    continue;
                }

                $question = Question::create([
                    'game_category_id' => $gameCategory->id,
                    'level' => $level,
                    'teks_soal' => $teksSoal,
                    'tipe_soal' => 'pilihan_ganda',
                    'data_matching' => null,
                    'poin' => 10,
                    'kurang_poin' => 5,
                    'waktu' => $waktuDetik > 0 ? $waktuDetik : 60,
                    'pembahasan' => $pembahasan,
                ]);

                foreach ($options as $key => $value) {
                    if ($value !== '') {
                        Answer::create([
                            'question_id' => $question->id,
                            'teks_jawaban' => $value,
                            'is_correct' => ($key === $kunci),
                        ]);
                    }
                }
            }

            $importedCount++;
        }

        \DB::commit();

    } catch (\Throwable $e) {

        \DB::rollBack();

        return redirect()->back()->withErrors([
            'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage()
        ]);
    }

    $message = "Berhasil mengimpor {$importedCount} soal!";

    if ($skippedCount > 0) {
        $message .= " ({$skippedCount} baris tidak valid dilewati)";
    }

    return redirect()->back()->with('success', $message);
}
}

