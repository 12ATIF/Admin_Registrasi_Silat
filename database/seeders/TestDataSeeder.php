<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelatih;
use App\Models\Kontingen;
use App\Models\Peserta;
use App\Models\Pembayaran;
use App\Models\DokumenPeserta;
use App\Models\SubkategoriLomba;
use App\Models\KelompokUsia;
use App\Models\KelasTanding;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates comprehensive test data for all features
     */
    public function run(): void
    {
        $this->command->info('Creating test data...');

        // Create Pelatih (with active and inactive)
        $pelatihData = [
            [
                'nama' => 'Ahmad Wijaya',
                'email' => 'ahmad@test.com',
                'password' => Hash::make('password'),
                'no_hp' => '081234567890',
                'perguruan' => 'Perguruan Macan Putih',
                'is_active' => true,
            ],
            [
                'nama' => 'Budi Santoso',
                'email' => 'budi@test.com',
                'password' => Hash::make('password'),
                'no_hp' => '081234567891',
                'perguruan' => 'Perguruan Harimau Sakti',
                'is_active' => true,
            ],
            [
                'nama' => 'Citra Dewi',
                'email' => 'citra@test.com',
                'password' => Hash::make('password'),
                'no_hp' => '081234567892',
                'perguruan' => 'Perguruan Elang Emas',
                'is_active' => false, // Inactive pelatih
            ],
            [
                'nama' => 'Doni Pratama',
                'email' => 'doni@test.com',
                'password' => Hash::make('password'),
                'no_hp' => '081234567893',
                'perguruan' => 'Perguruan Naga Hitam',
                'is_active' => false, // Inactive pelatih
            ],
        ];

        $pelatihList = [];
        foreach ($pelatihData as $data) {
            $pelatihList[] = Pelatih::create($data);
        }
        $this->command->info('Created ' . count($pelatihList) . ' pelatih');

        // Get first subkategori for peserta
        $subkategoriTandingPutra = SubkategoriLomba::where('nama', 'Tanding Putra')->first();
        $subkategoriTandingPutri = SubkategoriLomba::where('nama', 'Tanding Putri')->first();
        $subkategoriSeniTunggal = SubkategoriLomba::where('nama', 'Tunggal Putra')->first();
        
        // Get kelompok usia
        $usiaRemaja = KelompokUsia::where('nama', 'Remaja')->first();
        $usiaDewasa = KelompokUsia::where('nama', 'Dewasa')->first();
        $usiaPraRemaja = KelompokUsia::where('nama', 'Pra Remaja')->first();

        // Create Kontingen for each active pelatih
        $kontingenData = [
            [
                'pelatih_id' => $pelatihList[0]->id,
                'nama' => 'Kontingen Jakarta Pusat',
                'asal_daerah' => 'Jakarta Pusat',
                'is_active' => true,
            ],
            [
                'pelatih_id' => $pelatihList[0]->id,
                'nama' => 'Kontingen Jakarta Selatan',
                'asal_daerah' => 'Jakarta Selatan',
                'is_active' => true,
            ],
            [
                'pelatih_id' => $pelatihList[1]->id,
                'nama' => 'Kontingen Bandung',
                'asal_daerah' => 'Bandung',
                'is_active' => true,
            ],
            [
                'pelatih_id' => $pelatihList[1]->id,
                'nama' => 'Kontingen Surabaya',
                'asal_daerah' => 'Surabaya',
                'is_active' => false, // Inactive kontingen
            ],
        ];

        $kontingenList = [];
        foreach ($kontingenData as $data) {
            $kontingenList[] = Kontingen::create($data);
        }
        $this->command->info('Created ' . count($kontingenList) . ' kontingen');

        // Create Peserta with various statuses
        $pesertaData = [
            // Kontingen 1 - Jakarta Pusat
            [
                'kontingen_id' => $kontingenList[0]->id,
                'subkategori_id' => $subkategoriTandingPutra?->id,
                'kelompok_usia_id' => $usiaRemaja?->id,
                'nama' => 'Andi Pratama',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => Carbon::now()->subYears(16),
                'berat_badan' => 55,
                'status_verifikasi' => 'valid',
            ],
            [
                'kontingen_id' => $kontingenList[0]->id,
                'subkategori_id' => $subkategoriTandingPutra?->id,
                'kelompok_usia_id' => $usiaRemaja?->id,
                'nama' => 'Bima Sakti',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => Carbon::now()->subYears(15),
                'berat_badan' => 50,
                'status_verifikasi' => 'pending',
            ],
            [
                'kontingen_id' => $kontingenList[0]->id,
                'subkategori_id' => $subkategoriTandingPutri?->id,
                'kelompok_usia_id' => $usiaRemaja?->id,
                'nama' => 'Cantika Dewi',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => Carbon::now()->subYears(16),
                'berat_badan' => 48,
                'status_verifikasi' => 'tidak_valid',
            ],
            // Kontingen 2 - Jakarta Selatan
            [
                'kontingen_id' => $kontingenList[1]->id,
                'subkategori_id' => $subkategoriTandingPutra?->id,
                'kelompok_usia_id' => $usiaDewasa?->id,
                'nama' => 'Dimas Putra',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => Carbon::now()->subYears(22),
                'berat_badan' => 65,
                'status_verifikasi' => 'valid',
            ],
            [
                'kontingen_id' => $kontingenList[1]->id,
                'subkategori_id' => $subkategoriSeniTunggal?->id,
                'kelompok_usia_id' => $usiaDewasa?->id,
                'nama' => 'Eka Wijaya',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => Carbon::now()->subYears(20),
                'berat_badan' => 60,
                'status_verifikasi' => 'pending',
            ],
            // Kontingen 3 - Bandung
            [
                'kontingen_id' => $kontingenList[2]->id,
                'subkategori_id' => $subkategoriTandingPutri?->id,
                'kelompok_usia_id' => $usiaPraRemaja?->id,
                'nama' => 'Fitri Ayu',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => Carbon::now()->subYears(13),
                'berat_badan' => 40,
                'status_verifikasi' => 'valid',
            ],
            [
                'kontingen_id' => $kontingenList[2]->id,
                'subkategori_id' => $subkategoriTandingPutra?->id,
                'kelompok_usia_id' => $usiaPraRemaja?->id,
                'nama' => 'Galih Pratama',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => Carbon::now()->subYears(13),
                'berat_badan' => 38,
                'status_verifikasi' => 'pending',
            ],
            [
                'kontingen_id' => $kontingenList[2]->id,
                'subkategori_id' => $subkategoriTandingPutra?->id,
                'kelompok_usia_id' => $usiaRemaja?->id,
                'nama' => 'Hendra Gunawan',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => Carbon::now()->subYears(16),
                'berat_badan' => 58,
                'status_verifikasi' => 'tidak_valid',
            ],
        ];

        $pesertaList = [];
        foreach ($pesertaData as $data) {
            // Assign kelas tanding based on kelompok usia, jenis kelamin, and berat
            if ($data['subkategori_id'] && $data['kelompok_usia_id']) {
                $jenisKelamin = $data['jenis_kelamin'] === 'L' ? 'putra' : 'putri';
                $kelasTanding = KelasTanding::where('kelompok_usia_id', $data['kelompok_usia_id'])
                    ->where('jenis_kelamin', $jenisKelamin)
                    ->where('berat_min', '<=', $data['berat_badan'])
                    ->where('berat_max', '>=', $data['berat_badan'])
                    ->first();
                
                if ($kelasTanding) {
                    $data['kelas_tanding_id'] = $kelasTanding->id;
                }
            }
            
            $pesertaList[] = Peserta::create($data);
        }
        $this->command->info('Created ' . count($pesertaList) . ' peserta');

        // Create Pembayaran with various statuses
        $pembayaranData = [
            [
                'kontingen_id' => $kontingenList[0]->id,
                'total_tagihan' => 450000, // 3 peserta x 150000
                'status' => 'lunas',
                'bukti_transfer' => null,
                'verified_at' => Carbon::now()->subDays(5),
            ],
            [
                'kontingen_id' => $kontingenList[1]->id,
                'total_tagihan' => 300000, // 2 peserta
                'status' => 'menunggu_verifikasi',
                'bukti_transfer' => null,
                'verified_at' => null,
            ],
            [
                'kontingen_id' => $kontingenList[2]->id,
                'total_tagihan' => 450000, // 3 peserta
                'status' => 'belum_bayar',
                'bukti_transfer' => null,
                'verified_at' => null,
            ],
        ];

        $pembayaranList = [];
        foreach ($pembayaranData as $data) {
            $pembayaranList[] = Pembayaran::create($data);
        }
        $this->command->info('Created ' . count($pembayaranList) . ' pembayaran');

        // Create some Dokumen Peserta
        $dokumenData = [
            [
                'peserta_id' => $pesertaList[0]->id,
                'jenis_dokumen' => 'ktp',
                'file_path' => 'dokumen/dummy_ktp_1.pdf',
                'verified_at' => Carbon::now()->subDays(3),
            ],
            [
                'peserta_id' => $pesertaList[0]->id,
                'jenis_dokumen' => 'ijazah',
                'file_path' => 'dokumen/dummy_ijazah_1.pdf',
                'verified_at' => null,
            ],
            [
                'peserta_id' => $pesertaList[1]->id,
                'jenis_dokumen' => 'ktp',
                'file_path' => 'dokumen/dummy_ktp_2.pdf',
                'verified_at' => null,
            ],
            [
                'peserta_id' => $pesertaList[3]->id,
                'jenis_dokumen' => 'ktp',
                'file_path' => 'dokumen/dummy_ktp_3.pdf',
                'verified_at' => Carbon::now()->subDays(1),
            ],
        ];

        foreach ($dokumenData as $data) {
            DokumenPeserta::create($data);
        }
        $this->command->info('Created ' . count($dokumenData) . ' dokumen peserta');

        $this->command->info('Test data creation completed!');
        $this->command->info('');
        $this->command->info('Summary:');
        $this->command->info('- Pelatih: 2 aktif, 2 nonaktif');
        $this->command->info('- Kontingen: 3 aktif, 1 nonaktif');
        $this->command->info('- Peserta: 3 valid, 3 pending, 2 tidak_valid');
        $this->command->info('- Pembayaran: 1 lunas, 1 menunggu_verifikasi, 1 belum_bayar');
        $this->command->info('- Dokumen: 2 terverifikasi, 2 belum terverifikasi');
    }
}
