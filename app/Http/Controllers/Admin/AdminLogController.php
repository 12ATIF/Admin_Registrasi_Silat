<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use Illuminate\Http\Request;

class AdminLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = AdminLog::with('admin')
            ->orderBy('waktu_aksi', 'desc')
            ->paginate(20);
            
        return view('admin.logs.index', compact('logs'));
    }
    
    public function show(AdminLog $adminLog)
    {
        $adminLog->load('admin');
        
        return view('admin.logs.show', compact('adminLog'));
    }
}