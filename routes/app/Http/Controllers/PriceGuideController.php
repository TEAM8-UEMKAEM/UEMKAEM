<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\SettingController;
use App\Models\Category;
use App\Models\Material;
use App\Models\Setting;
use App\Services\PriceGuideService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PriceGuideController extends Controller
{
    public function show(Request $request, PriceGuideService $guide)
    {
        $categories = Category::active()->orderBy('name')->get(['id', 'name']);
        $materials = Material::active()->orderBy('name')->get(['id', 'name']);

        $raw = Setting::getValue(SettingController::LABOR_RATE_KEY);
        $hourlyRate = ($raw !== null && ctype_digit($raw) && (int) $raw > 0) ? (int) $raw : null;

        $result = null;

        if ($request->hasAny(['category_id', 'material_id', 'material_cost', 'labor_hours'])) {
            $data = $request->validate([
                'category_id' => ['required', 'integer', Rule::exists('categories', 'id')->where('is_active', true)],
                'material_id' => ['required', 'integer', Rule::exists('materials', 'id')->where('is_active', true)],
                'material_cost' => ['required', 'integer', 'min:0', 'max:2000000000'],
                'labor_hours' => ['required', 'numeric', 'min:0', 'max:100000'],
            ], [
                'required' => ':attribute wajib diisi.',
                'integer' => ':attribute harus berupa angka bulat.',
                'numeric' => ':attribute harus berupa angka.',
                'min' => ':attribute tidak boleh kurang dari :min.',
                'max' => ':attribute terlalu besar.',
                'category_id.exists' => 'Kategori tidak tersedia.',
                'material_id.exists' => 'Material tidak tersedia.',
            ], [
                'category_id' => 'Kategori', 'material_id' => 'Material',
                'material_cost' => 'Biaya bahan', 'labor_hours' => 'Jam kerja',
            ]);

            // Perhitungan dilakukan di server, bukan di JavaScript
            if ($hourlyRate !== null) {
                $result = $guide->estimate(
                    (int) $data['category_id'], (int) $data['material_id'],
                    (int) $data['material_cost'], (float) $data['labor_hours'], $hourlyRate
                );
            }
        }

        return view('price-guide.show', compact('categories', 'materials', 'hourlyRate', 'result'));
    }
}
