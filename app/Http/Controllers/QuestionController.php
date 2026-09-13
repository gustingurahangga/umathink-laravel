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
        $filePath = public_path('templatesoal_umathink.xlsx');

        if (!file_exists($filePath)) {
            return redirect()->back()->withErrors(['File template tidak ditemukan di server.']);
        }

        return response()->download($filePath, 'template_impor_soal.xlsx');
    }

    /**
     * Proses impor soal dari Excel atau CSV
     */
    public function import(Request $request)
    {
        // Tingkatkan memory limit & waktu eksekusi agar tidak terjadi timeout/memory exhausted saat load Excel
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

        // 1. Parsing File CSV atau Excel
        if ($extension === 'csv' || $extension === 'txt') {
            $delimiter = ',';
            if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
                // Deteksi delimiter (koma atau titik koma)
                $firstLine = fgets($handle);
                if (strpos($firstLine, ';') !== false && strpos($firstLine, ',') === false) {
                    $delimiter = ';';
                }
                rewind($handle);

                // Baca baris
                while (($data = fgetcsv($handle, 1000, $delimiter)) !== false) {
                    $rows[] = $data;
                }
                fclose($handle);
            }
        } else {
            // Excel parsing (.xlsx / .xls) menggunakan PhpSpreadsheet
            try {
                // Gunakan reader dengan mode read data only agar menghemat memori dan menghindari crash
                $reader = IOFactory::createReaderForFile($file->getRealPath());
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($file->getRealPath());
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
            } catch (\Throwable $e) {
                return redirect()->back()->withErrors(['Gagal membaca file Excel: ' . $e->getMessage()]);
            }
        }

        if (count($rows) <= 1) {
            return redirect()->back()->withErrors(['File kosong atau hanya berisi baris header!']);
        }

        // Hapus baris pertama hingga baris header (3 baris: judul + 2 baris kosong + header kolom)
        // File Excel template memiliki: baris 1 = judul, baris 2-3 = kosong, baris 4 = header
        $skippedHeaderRows = 0;
        foreach ($rows as $key => $row) {
            // Deteksi baris header berdasarkan kolom pertama yang berisi nama kolom
            $firstCell = strtolower(trim((string)($row[0] ?? '')));
            if (in_array($firstCell, ['kategori', 'nama_game', 'game'])) {
                // Hapus semua baris hingga dan termasuk baris header
                $rows = array_slice($rows, $key + 1);
                $skippedHeaderRows = $key + 1;
                break;
            }
        }

        // Jika tidak menemukan header khusus, hapus hanya baris pertama
        if ($skippedHeaderRows === 0) {
            array_shift($rows);
        }
        
        $importedCount = 0;
        $skippedCount = 0;

        // DB Transaction agar aman jika ada error di tengah jalan
        \DB::beginTransaction();

        try {
            foreach ($rows as $index => $row) {
                // Pastikan kolom esensial terisi (minimal ada 8 kolom, 10 dengan poin)
                if (count($row) < 8) {
                    $skippedCount++;
                    continue;
                }

                // Ambil data dengan konversi type cast (string) untuk menghindari error trim(null) di PHP 8.1+
                $kategoriName = isset($row[0]) ? trim((string)$row[0]) : '';
                $levelStr = isset($row[1]) ? trim((string)$row[1]) : '';
                $level = $levelStr !== '' ? intval($levelStr) : 0;
                $teksSoal = isset($row[2]) ? trim((string)$row[2]) : '';
                $jawabanA = isset($row[3]) ? trim((string)$row[3]) : '';
                $jawabanB = isset($row[4]) ? trim((string)$row[4]) : '';
                $jawabanC = isset($row[5]) ? trim((string)$row[5]) : '';
                $jawabanD = isset($row[6]) ? trim((string)$row[6]) : '';
                $kunci = isset($row[7]) ? strtoupper(trim((string)$row[7])) : '';

                // Kolom poin (opsional — default: poin=10, kurang_poin=5)
                $poinBenar = isset($row[8]) && trim((string)$row[8]) !== '' ? intval($row[8]) : 10;
                $poinSalah = isset($row[9]) && trim((string)$row[9]) !== '' ? intval($row[9]) : 5;
                
                $waktuDetik = isset($row[10]) && trim((string)$row[10]) !== '' ? intval($row[10]) : 60;
                $pembahasan = isset($row[11]) ? trim((string)$row[11]) : '';

                // Jika data wajib kosong, lewati
                if (empty($kategoriName) || $level <= 0 || empty($teksSoal)) {
                    $skippedCount++;
                    continue;
                }

                $options = [
                    'A' => $jawabanA,
                    'B' => $jawabanB,
                    'C' => $jawabanC,
                    'D' => $jawabanD,
                ];

                // Pastikan kunci jawaban valid dan tidak kosong
                if (!in_array($kunci, ['A', 'B', 'C', 'D']) || empty($options[$kunci])) {
                    $skippedCount++;
                    continue;
                }

                // Cari atau buat kategori game
                $gameCategory = GameCategory::firstOrCreate(
                    ['nama_game' => $kategoriName],
                    ['jumlah_level' => $level]
                );

                // Sinkronisasi jumlah level jika level yang diimpor lebih besar
                if ($level > $gameCategory->jumlah_level) {
                    $gameCategory->update(['jumlah_level' => $level]);
                }

                // Simpan Question dengan poin, waktu, dan pembahasan
                $question = Question::create([
                    'game_category_id' => $gameCategory->id,
                    'level' => $level,
                    'teks_soal' => $teksSoal,
                    'poin' => $poinBenar,
                    'kurang_poin' => $poinSalah,
                    'waktu' => $waktuDetik,
                    'pembahasan' => $pembahasan,
                ]);

                // Simpan Answers yang tidak kosong
                foreach ($options as $key => $value) {
                    if (!empty($value)) {
                        Answer::create([
                            'question_id' => $question->id,
                            'teks_jawaban' => $value,
                            'is_correct' => ($key === $kunci),
                        ]);
                    }
                }

                $importedCount++;
            }

            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            return redirect()->back()->withErrors(['Terjadi kesalahan saat mengimpor data: ' . $e->getMessage()]);
        }

        $message = "Berhasil mengimpor {$importedCount} soal!";
        if ($skippedCount > 0) {
            $message .= " ({$skippedCount} baris tidak valid dilewati)";
        }

        return redirect()->back()->with('success', $message);
    }
}
