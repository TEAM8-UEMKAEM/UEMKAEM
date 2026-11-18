<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

// Hanya membaca. Tidak ada route untuk mengubah atau menghapus riwayat.
class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLog::query()
            ->when($request->filled('type'), fn ($q) => $q->where('auditable_type', $request->type))
            ->when($request->filled('action'), fn ($q) => $q->where('action', $request->action))
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        return view('admin.audit.index', compact('logs'));
    }
}
