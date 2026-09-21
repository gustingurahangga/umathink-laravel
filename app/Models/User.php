<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'namalengkap',
        'email',
        'google_id',
        'avatar',
        'photo',
        'role',
        'status',
        'password',
        'total_poin',
        'highest_poin',
        'highest_peringkat',
        'highest_liga',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi nilai terbaik setiap level.
     */
    public function levelScores()
    {
        return $this->hasMany(UserLevelScore::class, 'user_id');
    }

    /**
     * Total bintang user.
     *
     * Total bintang dihitung dari seluruh
     * nilai terbaik setiap level.
     */
    public function getTotalBintangAttribute()
    {
        /*
         * Kalau query sudah menggunakan:
         *
         * withSum('levelScores as total_bintang', 'stars')
         *
         * gunakan nilai tersebut agar tidak
         * melakukan query tambahan.
         */
        if (array_key_exists('total_bintang', $this->attributes)) {
            return (int) ($this->attributes['total_bintang'] ?? 0);
        }

        return (int) $this->levelScores()->sum('stars');
    }

    /**
     * Menentukan klaster berdasarkan jumlah bintang.
     */
    public static function getKlasterBintang(int $bintang): array
    {
        if ($bintang <= 50) {
            return [
                'nama' => 'Klaster Amatir',
                'range' => '0 – 50 Bintang',
                'deskripsi' => 'Tahap awal adaptasi',
                'logo' => 'assets/img/klaster/amatir.png',
            ];
        }

        if ($bintang <= 100) {
            return [
                'nama' => 'Klaster Perintis',
                'range' => '51 – 100 Bintang',
                'deskripsi' => 'Membangun fondasi',
                'logo' => 'assets/img/klaster/perintis.png',
            ];
        }

        if ($bintang <= 150) {
            return [
                'nama' => 'Klaster Pengamat',
                'range' => '101 – 150 Bintang',
                'deskripsi' => 'Mulai mengenali pola soal',
                'logo' => 'assets/img/klaster/pengamat.png',
            ];
        }

        if ($bintang <= 200) {
            return [
                'nama' => 'Klaster Analis',
                'range' => '151 – 200 Bintang',
                'deskripsi' => 'Mulai memecah logika kompleks',
                'logo' => 'assets/img/klaster/analis.png',
            ];
        }

        if ($bintang <= 250) {
            return [
                'nama' => 'Klaster Strategis',
                'range' => '201 – 250 Bintang',
                'deskripsi' => 'Menemukan efisiensi pengerjaan',
                'logo' => 'assets/img/klaster/strategis.png',
            ];
        }

        if ($bintang <= 300) {
            return [
                'nama' => 'Klaster Spesialis',
                'range' => '251 – 300 Bintang',
                'deskripsi' => 'Konsistensi ketajaman meningkat',
                'logo' => 'assets/img/klaster/spesialis.png',
            ];
        }

        if ($bintang <= 350) {
            return [
                'nama' => 'Klaster Mahir',
                'range' => '301 – 350 Bintang',
                'deskripsi' => 'Tingkat kesalahan sangat minim',
                'logo' => 'assets/img/klaster/mahir.png',
            ];
        }

        if ($bintang <= 400) {
            return [
                'nama' => 'Klaster Inovator',
                'range' => '351 – 400 Bintang',
                'deskripsi' => 'Berpikir fleksibel & out-of-the-box',
                'logo' => 'assets/img/klaster/inovator.png',
            ];
        }

        if ($bintang <= 450) {
            return [
                'nama' => 'Klaster Pakar',
                'range' => '401 – 450 Bintang',
                'deskripsi' => 'Penguasaan materi tingkat tinggi',
                'logo' => 'assets/img/klaster/pakar.png',
            ];
        }

        return [
            'nama' => 'Klaster Maestro',
            'range' => '451+ Bintang',
            'deskripsi' => 'Pencapaian puncak/paripurna',
            'logo' => 'assets/img/klaster/maestro.png',
        ];
    }

    /**
     * Klaster user saat ini.
     */
    public function getKlasterAttribute()
    {
        return self::getKlasterBintang($this->total_bintang);
    }

    /**
     * Ranking berdasarkan total bintang.
     *
     * Leaderboard tidak menggunakan season.
     */
    public function getPeringkatAttribute()
    {
        if ($this->role !== 'customer') {
            return '-';
        }

        $bintang = $this->total_bintang;

        /*
         * Hitung user lain yang mempunyai
         * total bintang lebih tinggi.
         */
        $higherUsersCount = static::query()
            ->where('role', 'customer')
            ->where('users.id', '!=', $this->id)
            ->whereRaw(
                '(SELECT COALESCE(SUM(stars), 0)
                  FROM user_level_scores
                  WHERE user_level_scores.user_id = users.id) > ?',
                [$bintang]
            )
            ->count();

        return $higherUsersCount + 1;
    }

    /**
     * Liga lama tidak lagi digunakan
     * untuk Leaderboard.
     *
     * Tetap disediakan supaya bagian lain
     * dari aplikasi yang masih memanggil
     * $user->liga tidak langsung rusak.
     */
    public function getLigaAttribute()
    {
        return $this->klaster['nama'];
    }
}