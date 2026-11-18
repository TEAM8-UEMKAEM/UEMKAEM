<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PriceCheckService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * GET /api/v1/price-check?category_id=&material_id=&price=
 * Hanya membaca: tidak mengubah data apa pun.
 */
class PriceCheckController extends Controller
{
    public function __invoke(Request $request, PriceCheckService $service)
    {
        $v = Validator::make($request->all(), [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'material_id' => ['required', 'integer', 'exists:materials,id'],
            'price' => ['required', 'integer', 'min:0', 'max:100000000000'],
        ]);

        if ($v->fails()) {
            return response()->json([
                'status' => 'validation_error',
                'valid' => null,
                'message' => 'Parameter tidak valid.',
                'errors' => $v->errors(),
            ], 422);
        }

        $result = $service->check((int) $request->category_id, (int) $request->material_id, (int) $request->price);

        // 404: tidak ada aturan aktif yang berlaku. 200: harga berhasil dievaluasi (lihat field "valid").
        $http = in_array($result['status'], ['no_rule', 'rule_inactive'], true) ? 404 : 200;

        return response()->json($result, $http);
    }
}
