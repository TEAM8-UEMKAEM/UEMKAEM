<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Material;
use App\Services\PriceCheckService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * GET /api/price-check  (publik, hanya-baca, dibatasi laju permintaan)
 * Tidak mengubah data apa pun. Dokumentasi: docs/PRICE_API.md
 */
class PriceCheckController extends Controller
{
    public function __invoke(Request $request, PriceCheckService $service)
    {
        $validator = Validator::make($request->query(), [
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')->where('is_active', true)],
            'material_id' => ['required', 'integer', Rule::exists('materials', 'id')->where('is_active', true)],
            'price' => ['required', 'integer', 'min:0', 'max:4000000000'],
        ], [
            'required' => ':attribute wajib diisi.',
            'integer' => ':attribute harus berupa angka bulat.',
            'min' => ':attribute tidak boleh negatif.',
            'max' => ':attribute terlalu besar.',
            'category_id.exists' => 'Kategori tidak tersedia (tidak aktif atau tidak ditemukan).',
            'material_id.exists' => 'Material tidak tersedia (tidak aktif atau tidak ditemukan).',
        ], ['category_id' => 'category_id', 'material_id' => 'material_id', 'price' => 'price']);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Data yang dikirim tidak valid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $categoryId = (int) $request->query('category_id');
        $materialId = (int) $request->query('material_id');

        $result = $service->check($categoryId, $materialId, (int) $request->query('price'));

        return response()->json(['data' => $result + [
            'category' => Category::select('id', 'name')->find($categoryId),
            'material' => Material::select('id', 'name')->find($materialId),
        ]]);
    }
}
