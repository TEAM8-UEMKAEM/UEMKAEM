<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\AuditLogger;
use App\Services\PriceCheckService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit', ['rate' => Setting::getValue(PriceCheckService::LABOR_RATE_KEY)]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'labor_hourly_rate' => ['required', 'integer', 'min:1', 'max:10000000'],
        ], [
            'labor_hourly_rate.min' => 'Tarif upah per jam harus lebih dari 0.',
        ]);

        DB::transaction(function () use ($data) {
            $key = PriceCheckService::LABOR_RATE_KEY;
            $old = Setting::getValue($key);
            $new = (string) $data['labor_hourly_rate'];

            if ($old === $new) {
                return;
            }

            $setting = Setting::setValue($key, $new);
            AuditLogger::record($setting, $old === null ? 'created' : 'updated',
                $old === null ? null : [$key => (int) $old], [$key => (int) $new]);
        });

        return redirect()->route('admin.settings.edit')->with('success', 'Tarif upah per jam disimpan.');
    }
}
