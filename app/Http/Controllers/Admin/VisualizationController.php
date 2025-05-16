<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\KategoriLomba;
use App\Models\KelompokUsia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisualizationController extends Controller
{
    public function index()
    {
        return view('admin.visualization.index');
    }
    
    public function getData(Request $request)
    {
        $type = $request->input('type', 'gender');
        $kategoriId = $request->input('kategori_id');
        $kelompokUsiaId = $request->input('kelompok_usia_id');
        $jenisKelamin = $request->input('jenis_kelamin');
        
        // Base query with filters
        $query = Peserta::query();
        
        if ($kategoriId) {
            $query->whereHas('subkategoriLomba', function($q) use ($kategoriId) {
                $q->where('kategori_id', $kategoriId);
            });
        }
        
        if ($kelompokUsiaId) {
            $query->where('kelompok_usia_id', $kelompokUsiaId);
        }
        
        if ($jenisKelamin) {
            $query->where('jenis_kelamin', $jenisKelamin);
        }
        
        // Only include validated participants
        $query->where('status_verifikasi', 'valid');
        
        // Return different data based on visualization type
        switch ($type) {
            case 'gender':
                return $this->getGenderDistribution($query);
            
            case 'category':
                return $this->getCategoryDistribution($query);
            
            case 'age_group':
                return $this->getAgeGroupDistribution($query);
            
            case 'weight':
                return $this->getWeightDistribution($query);
            
            default:
                return response()->json(['error' => 'Visualization type not supported'], 400);
        }
    }
    
    private function getGenderDistribution($query)
    {
        $data = [
            'putra_count' => (clone $query)->where('jenis_kelamin', 'L')->count(),
            'putri_count' => (clone $query)->where('jenis_kelamin', 'P')->count(),
        ];
        
        return response()->json($data);
    }
    
    private function getCategoryDistribution($query)
    {
        // Join and group by category
        $categories = DB::table('peserta')
            ->join('subkategori_lomba', 'peserta.subkategori_id', '=', 'subkategori_lomba.id')
            ->join('kategori_lomba', 'subkategori_lomba.kategori_id', '=', 'kategori_lomba.id')
            ->select('kategori_lomba.id', 'kategori_lomba.nama', DB::raw('count(peserta.id) as count'))
            ->where('peserta.status_verifikasi', 'valid')
            ->groupBy('kategori_lomba.id', 'kategori_lomba.nama')
            ->get();
        
        return response()->json(['categories' => $categories]);
    }
    
    private function getAgeGroupDistribution($query)
    {
        // Join and group by age group
        $ageGroups = DB::table('peserta')
            ->join('kelompok_usia', 'peserta.kelompok_usia_id', '=', 'kelompok_usia.id')
            ->select('kelompok_usia.id', 'kelompok_usia.nama', DB::raw('count(peserta.id) as count'))
            ->where('peserta.status_verifikasi', 'valid')
            ->groupBy('kelompok_usia.id', 'kelompok_usia.nama')
            ->get();
        
        return response()->json(['age_groups' => $ageGroups]);
    }
    
    private function getWeightDistribution($query)
    {
        // Calculate min and max weight to determine ranges
        $minWeight = (clone $query)->min('berat_badan') ?: 0;
        $maxWeight = (clone $query)->max('berat_badan') ?: 100;
        
        // Create ranges of 5kg
        $ranges = [];
        $rangeSize = 5; // 5kg ranges
        
        for ($i = floor($minWeight / $rangeSize) * $rangeSize; $i <= ceil($maxWeight / $rangeSize) * $rangeSize; $i += $rangeSize) {
            $min = $i;
            $max = $i + $rangeSize;
            
            $count = (clone $query)
                ->where('berat_badan', '>=', $min)
                ->where('berat_badan', '<', $max)
                ->count();
            
            $ranges[] = [
                'min' => $min,
                'max' => $max,
                'count' => $count
            ];
        }
        
        return response()->json(['weight_ranges' => $ranges]);
    }
}