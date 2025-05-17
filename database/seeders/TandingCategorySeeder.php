<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriLomba;
use App\Models\SubkategoriLomba;
use App\Models\KelompokUsia;
use App\Models\KelasTanding;
use Illuminate\Support\Facades\DB;

class TandingCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear tables to avoid duplicate entries on re-run
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        KelasTanding::truncate();
        KelompokUsia::truncate();
        SubkategoriLomba::truncate();
        KategoriLomba::truncate();
        DB::table('subkategori_usia')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create main categories
        $kategoriSeni = KategoriLomba::create([
            'nama' => 'Seni'
        ]);
        
        $kategoriTanding = KategoriLomba::create([
            'nama' => 'Tanding'
        ]);

        // Create age groups
        $usiaAnak = KelompokUsia::create([
            'nama' => 'Anak-Anak',
            'rentang_usia_min' => 6,
            'rentang_usia_max' => 12
        ]);
        
        $usiaPraRemaja = KelompokUsia::create([
            'nama' => 'Pra Remaja',
            'rentang_usia_min' => 12,
            'rentang_usia_max' => 14
        ]);
        
        $usiaRemaja = KelompokUsia::create([
            'nama' => 'Remaja',
            'rentang_usia_min' => 14,
            'rentang_usia_max' => 17
        ]);
        
        $usiaDewasa = KelompokUsia::create([
            'nama' => 'Dewasa',
            'rentang_usia_min' => 17,
            'rentang_usia_max' => 35
        ]);
        
        $usiaMaster = KelompokUsia::create([
            'nama' => 'Master',
            'rentang_usia_min' => 35,
            'rentang_usia_max' => 45
        ]);

        // Create subcategories for Seni
        $seniTunggalPutra = SubkategoriLomba::create([
            'kategori_id' => $kategoriSeni->id,
            'nama' => 'Tunggal Putra',
            'jenis' => 'tunggal',
            'jumlah_peserta' => 1,
            'harga_pendaftaran' => 100000
        ]);
        
        $seniTunggalPutri = SubkategoriLomba::create([
            'kategori_id' => $kategoriSeni->id,
            'nama' => 'Tunggal Putri',
            'jenis' => 'tunggal',
            'jumlah_peserta' => 1,
            'harga_pendaftaran' => 100000
        ]);
        
        $seniGandaPutra = SubkategoriLomba::create([
            'kategori_id' => $kategoriSeni->id,
            'nama' => 'Ganda Putra',
            'jenis' => 'ganda',
            'jumlah_peserta' => 2,
            'harga_pendaftaran' => 150000
        ]);
        
        $seniGandaPutri = SubkategoriLomba::create([
            'kategori_id' => $kategoriSeni->id,
            'nama' => 'Ganda Putri',
            'jenis' => 'ganda',
            'jumlah_peserta' => 2,
            'harga_pendaftaran' => 150000
        ]);
        
        $seniGandaCampuran = SubkategoriLomba::create([
            'kategori_id' => $kategoriSeni->id,
            'nama' => 'Ganda Campuran',
            'jenis' => 'ganda',
            'jumlah_peserta' => 2,
            'harga_pendaftaran' => 150000
        ]);
        
        $seniReguPutra = SubkategoriLomba::create([
            'kategori_id' => $kategoriSeni->id,
            'nama' => 'Regu Putra',
            'jenis' => 'regu',
            'jumlah_peserta' => 3,
            'harga_pendaftaran' => 200000
        ]);
        
        $seniReguPutri = SubkategoriLomba::create([
            'kategori_id' => $kategoriSeni->id,
            'nama' => 'Regu Putri',
            'jenis' => 'regu',
            'jumlah_peserta' => 3,
            'harga_pendaftaran' => 200000
        ]);

        // Create subcategories for Tanding
        $tandingPutra = SubkategoriLomba::create([
            'kategori_id' => $kategoriTanding->id,
            'nama' => 'Tanding Putra',
            'jenis' => 'tanding',
            'jumlah_peserta' => 1,
            'harga_pendaftaran' => 150000
        ]);
        
        $tandingPutri = SubkategoriLomba::create([
            'kategori_id' => $kategoriTanding->id,
            'nama' => 'Tanding Putri',
            'jenis' => 'tanding',
            'jumlah_peserta' => 1,
            'harga_pendaftaran' => 150000
        ]);

        // Attach subcategories to age groups
        // Seni categories for all age groups
        $seniSubcategories = [$seniTunggalPutra->id, $seniTunggalPutri->id, $seniGandaPutra->id, $seniGandaPutri->id, $seniGandaCampuran->id, $seniReguPutra->id, $seniReguPutri->id];
        $allAgeGroups = [$usiaAnak->id, $usiaPraRemaja->id, $usiaRemaja->id, $usiaDewasa->id, $usiaMaster->id];
        
        foreach ($seniSubcategories as $subcategory) {
            foreach ($allAgeGroups as $ageGroup) {
                DB::table('subkategori_usia')->insert([
                    'subkategori_id' => $subcategory,
                    'kelompok_usia_id' => $ageGroup
                ]);
            }
        }
        
        // Tanding categories for specific age groups
        $tandingAgeGroups = [$usiaPraRemaja->id, $usiaRemaja->id, $usiaDewasa->id];
        
        foreach ([$tandingPutra->id, $tandingPutri->id] as $subcategory) {
            foreach ($tandingAgeGroups as $ageGroup) {
                DB::table('subkategori_usia')->insert([
                    'subkategori_id' => $subcategory,
                    'kelompok_usia_id' => $ageGroup
                ]);
            }
        }

        // Create weight classes for Tanding category
        
        // Pra Remaja Putra - Kelas Tanding
        $this->createWeightClass($usiaPraRemaja->id, 'putra', 'A', 30, 33, 'Kelas A Putra (30-33 kg)');
        $this->createWeightClass($usiaPraRemaja->id, 'putra', 'B', 33, 36, 'Kelas B Putra (33-36 kg)');
        $this->createWeightClass($usiaPraRemaja->id, 'putra', 'C', 36, 39, 'Kelas C Putra (36-39 kg)');
        $this->createWeightClass($usiaPraRemaja->id, 'putra', 'D', 39, 42, 'Kelas D Putra (39-42 kg)');
        $this->createWeightClass($usiaPraRemaja->id, 'putra', 'E', 42, 45, 'Kelas E Putra (42-45 kg)');
        $this->createWeightClass($usiaPraRemaja->id, 'putra', 'F', 45, 48, 'Kelas F Putra (45-48 kg)');
        $this->createWeightClass($usiaPraRemaja->id, 'putra', 'G', 48, 51, 'Kelas G Putra (48-51 kg)');
        $this->createWeightClass($usiaPraRemaja->id, 'putra', 'OPEN', 51, 999, 'Kelas Open Putra (>51 kg)', true);
        
        // Pra Remaja Putri - Kelas Tanding
        $this->createWeightClass($usiaPraRemaja->id, 'putri', 'A', 30, 33, 'Kelas A Putri (30-33 kg)');
        $this->createWeightClass($usiaPraRemaja->id, 'putri', 'B', 33, 36, 'Kelas B Putri (33-36 kg)');
        $this->createWeightClass($usiaPraRemaja->id, 'putri', 'C', 36, 39, 'Kelas C Putri (36-39 kg)');
        $this->createWeightClass($usiaPraRemaja->id, 'putri', 'D', 39, 42, 'Kelas D Putri (39-42 kg)');
        $this->createWeightClass($usiaPraRemaja->id, 'putri', 'E', 42, 45, 'Kelas E Putri (42-45 kg)');
        $this->createWeightClass($usiaPraRemaja->id, 'putri', 'F', 45, 48, 'Kelas F Putri (45-48 kg)');
        $this->createWeightClass($usiaPraRemaja->id, 'putri', 'OPEN', 48, 999, 'Kelas Open Putri (>48 kg)', true);
        
        // Remaja Putra - Kelas Tanding
        $this->createWeightClass($usiaRemaja->id, 'putra', 'A', 39, 43, 'Kelas A Putra (39-43 kg)');
        $this->createWeightClass($usiaRemaja->id, 'putra', 'B', 43, 47, 'Kelas B Putra (43-47 kg)');
        $this->createWeightClass($usiaRemaja->id, 'putra', 'C', 47, 51, 'Kelas C Putra (47-51 kg)');
        $this->createWeightClass($usiaRemaja->id, 'putra', 'D', 51, 55, 'Kelas D Putra (51-55 kg)');
        $this->createWeightClass($usiaRemaja->id, 'putra', 'E', 55, 59, 'Kelas E Putra (55-59 kg)');
        $this->createWeightClass($usiaRemaja->id, 'putra', 'F', 59, 63, 'Kelas F Putra (59-63 kg)');
        $this->createWeightClass($usiaRemaja->id, 'putra', 'G', 63, 67, 'Kelas G Putra (63-67 kg)');
        $this->createWeightClass($usiaRemaja->id, 'putra', 'H', 67, 71, 'Kelas H Putra (67-71 kg)');
        $this->createWeightClass($usiaRemaja->id, 'putra', 'I', 71, 75, 'Kelas I Putra (71-75 kg)');
        $this->createWeightClass($usiaRemaja->id, 'putra', 'J', 75, 79, 'Kelas J Putra (75-79 kg)');
        $this->createWeightClass($usiaRemaja->id, 'putra', 'OPEN', 79, 999, 'Kelas Open Putra (>79 kg)', true);
        
        // Remaja Putri - Kelas Tanding
        $this->createWeightClass($usiaRemaja->id, 'putri', 'A', 39, 43, 'Kelas A Putri (39-43 kg)');
        $this->createWeightClass($usiaRemaja->id, 'putri', 'B', 43, 47, 'Kelas B Putri (43-47 kg)');
        $this->createWeightClass($usiaRemaja->id, 'putri', 'C', 47, 51, 'Kelas C Putri (47-51 kg)');
        $this->createWeightClass($usiaRemaja->id, 'putri', 'D', 51, 55, 'Kelas D Putri (51-55 kg)');
        $this->createWeightClass($usiaRemaja->id, 'putri', 'E', 55, 59, 'Kelas E Putri (55-59 kg)');
        $this->createWeightClass($usiaRemaja->id, 'putri', 'F', 59, 63, 'Kelas F Putri (59-63 kg)');
        $this->createWeightClass($usiaRemaja->id, 'putri', 'G', 63, 67, 'Kelas G Putri (63-67 kg)');
        $this->createWeightClass($usiaRemaja->id, 'putri', 'OPEN', 67, 999, 'Kelas Open Putri (>67 kg)', true);
        
        // Dewasa Putra - Kelas Tanding
        $this->createWeightClass($usiaDewasa->id, 'putra', 'A', 45, 50, 'Kelas A Putra (45-50 kg)');
        $this->createWeightClass($usiaDewasa->id, 'putra', 'B', 50, 55, 'Kelas B Putra (50-55 kg)');
        $this->createWeightClass($usiaDewasa->id, 'putra', 'C', 55, 60, 'Kelas C Putra (55-60 kg)');
        $this->createWeightClass($usiaDewasa->id, 'putra', 'D', 60, 65, 'Kelas D Putra (60-65 kg)');
        $this->createWeightClass($usiaDewasa->id, 'putra', 'E', 65, 70, 'Kelas E Putra (65-70 kg)');
        $this->createWeightClass($usiaDewasa->id, 'putra', 'F', 70, 75, 'Kelas F Putra (70-75 kg)');
        $this->createWeightClass($usiaDewasa->id, 'putra', 'G', 75, 80, 'Kelas G Putra (75-80 kg)');
        $this->createWeightClass($usiaDewasa->id, 'putra', 'H', 80, 85, 'Kelas H Putra (80-85 kg)');
        $this->createWeightClass($usiaDewasa->id, 'putra', 'I', 85, 90, 'Kelas I Putra (85-90 kg)');
        $this->createWeightClass($usiaDewasa->id, 'putra', 'J', 90, 95, 'Kelas J Putra (90-95 kg)');
        $this->createWeightClass($usiaDewasa->id, 'putra', 'OPEN', 95, 999, 'Kelas Open Putra (>95 kg)', true);
        
        // Dewasa Putri - Kelas Tanding
        $this->createWeightClass($usiaDewasa->id, 'putri', 'A', 45, 50, 'Kelas A Putri (45-50 kg)');
        $this->createWeightClass($usiaDewasa->id, 'putri', 'B', 50, 55, 'Kelas B Putri (50-55 kg)');
        $this->createWeightClass($usiaDewasa->id, 'putri', 'C', 55, 60, 'Kelas C Putri (55-60 kg)');
        $this->createWeightClass($usiaDewasa->id, 'putri', 'D', 60, 65, 'Kelas D Putri (60-65 kg)');
        $this->createWeightClass($usiaDewasa->id, 'putri', 'E', 65, 70, 'Kelas E Putri (65-70 kg)');
        $this->createWeightClass($usiaDewasa->id, 'putri', 'F', 70, 75, 'Kelas F Putri (70-75 kg)');
        $this->createWeightClass($usiaDewasa->id, 'putri', 'OPEN', 75, 999, 'Kelas Open Putri (>75 kg)', true);
    }

    /**
     * Helper method to create weight classes
     */
    private function createWeightClass($kelompokUsiaId, $jenisKelamin, $kodeKelas, $beratMin, $beratMax, $labelKeterangan, $isOpenClass = false)
    {
        return KelasTanding::create([
            'kelompok_usia_id' => $kelompokUsiaId,
            'jenis_kelamin' => $jenisKelamin,
            'kode_kelas' => $kodeKelas,
            'berat_min' => $beratMin,
            'berat_max' => $beratMax,
            'label_keterangan' => $labelKeterangan,
            'is_open_class' => $isOpenClass
        ]);
    }
}