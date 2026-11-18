<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Material;
use App\Models\Setting;
use App\Services\PriceCheckService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PriceGuideController extends Controller
{
    public function index(Request $request, PriceCheckService $service)
    {
        // Hanya data master aktif yang boleh dipilih
        $categories = Category::active()->select('id', 'name')->orderBy('name')->get();
        $materials = Material::active()->orderBy('name')->get();

        $result = null;
        $guideErrors = null;
        $category = null;
        $material = null;

        if ($request->hasAny(['category_id', 'material_id', 'material_cost', 'labor_hours'])) {
            $v = Validator::make($request->all(), [
                'category_id' => ['required', 'integer', Rule::exists('categories', 'id')->where('is_active', true)],
                'material_id' => ['required', 'integer', Rule::exists('materials', 'id')->where('is_active', true)],
                'material_cost' => ['required', 'integer', 'min:0', 'max:2000000000'],
                'labor_hours' => ['required', 'numeric', 'min:0.1', 'max:1000'],
            ], [], [
                'category_id' => 'Jenis kriya',
                'material_id' => 'Bahan',
                'material_cost' => 'Biaya bahan',
                'labor_hours' => 'Jam kerja',
            ]);

            if ($v->fails()) {
                $guideErrors = $v->errors();
            } else {
                $category = $categories->firstWhere('id', (int) $request->category_id);
                $material = $materials->firstWhere('id', (int) $request->material_id);
                $result = $service->estimate(
                    (int) $request->category_id,
                    (int) $request->material_id,
                    (int) $request->material_cost,
                    (float) $request->labor_hours,
                );
            }
        }

        $laborRate = (int) Setting::getValue(PriceCheckService::LABOR_RATE_KEY, '0');

        return view('price-guide.index', compact('categories', 'materials', 'result', 'guideErrors', 'category', 'material', 'laborRate'));
    }
}
