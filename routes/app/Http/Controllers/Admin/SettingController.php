<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** Tarif upah per jam: ditetapkan admin, tidak ada nilai bawaan di kode. */
class SettingController extends Controller
{
    public const LABOR_RATE_KEY = 'labor_hourly_rate';

    public function edit()
    {
        return view('admin.settings.edit', ['rate' => Setting::getValue(self::LABOR_RATE_KEY)]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'labor_hourly_rate' => ['required', 'integer', 'min:1', 'max:100000000'],
        ], [
            'required' => 'Tarif upah per jam wajib diisi.',
            'integer' => 'Tarif upah per jam harus berupa angka bulat (rupiah).',
            'min' => 'Tarif upah per jam harus lebih dari 0.',
            'max' => 'Tarif upah per jam terlalu besar.',
        ]);

        $new = (string) $data['labor_hourly_rate'];

        DB::transaction(function () use ($new) {
            $old = Setting::getValue(self::LABOR_RATE_KEY);
            $setting = Setting::setValue(self::LABOR_RATE_KEY, $new);

            if ($old !== $new) {
                AuditLogger::record($setting, $old === null ? 'created' : 'updated',
                    $old === null ? null : [self::LABOR_RATE_KEY => $old],
                    [self::LABOR_RATE_KEY => $new]);
            }
        });

        return redirect()->route('admin.settings.edit')->with('success', 'Tarif upah per jam disimpan.');
    }
}
