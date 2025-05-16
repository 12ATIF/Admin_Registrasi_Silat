<?php

namespace App\Traits;

use App\Models\AdminLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        // Log saat model dibuat
        static::created(function ($model) {
            static::logModelEvent('created', $model);
        });

        // Log saat model diupdate
        static::updated(function ($model) {
            static::logModelEvent('updated', $model);
        });

        // Log saat model dihapus
        static::deleted(function ($model) {
            static::logModelEvent('deleted', $model);
        });
    }

    /**
     * Log aktivitas model
     */
    protected static function logModelEvent($action, $model)
    {
        if (Auth::guard('admin')->check()) {
            AdminLog::create([
                'admin_id' => Auth::guard('admin')->id(),
                'aksi' => $action,
                'model' => get_class($model),
                'model_id' => $model->id,
                'waktu_aksi' => now(),
            ]);
        }
    }

    /**
     * Log aktivitas khusus (non-model)
     */
    public function logActivity($action, $model = null)
    {
        if (Auth::guard('admin')->check()) {
            AdminLog::create([
                'admin_id' => Auth::guard('admin')->id(),
                'aksi' => $action,
                'model' => $model ? get_class($model) : null,
                'model_id' => $model ? $model->id : null,
                'waktu_aksi' => now(),
            ]);
        }
    }
}