<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Material;
use App\Models\PriceRule;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Aturan harga adil: satu aturan per kombinasi (kategori + material).
 * Kombinasi tidak bisa diubah setelah dibuat; yang diubah hanya batas harga.
 * Tidak ada hapus permanen, hanya aktif/nonaktif.
 */
class PriceRuleController extends Controller
{
    private const MAX = 4000000000; // batas kolom unsigned integer

    private array $messages = [
        'category_id.exists' => 'Kategori tidak tersedia (tidak aktif atau tidak ditemukan).',
        'material_id.exists' => 'Material tidak tersedia (tidak aktif atau tidak ditemukan).',
        'category_id.unique' => 'Aturan untuk kombinasi kategori dan material ini sudah ada. Ubah atau aktifkan kembali aturan yang sudah ada.',
        'min_price.min' => 'Harga minimum tidak boleh negatif.',
        'max_price.min' => 'Harga maksimum tidak boleh negatif.',
        'max_price.gte' => 'Harga maksimum tidak boleh lebih kecil dari harga minimum.',
        'required' => ':attribute wajib diisi.',
        'integer' => ':attribute harus berupa angka bulat.',
        'max' => ':attribute terlalu besar.',
    ];

    private array $attributes = [
        'category_id' => 'Kategori', 'material_id' => 'Material',
        'min_price' => 'Harga minimum', 'max_price' => 'Harga maksimum',
    ];

    public function index()
    {
        $items = PriceRule::with(['category:id,name', 'material:id,name'])->latest('id')->get();

        return view('admin.price-rules.index', compact('items'));
    }

    public function create()
    {
        return view('admin.price-rules.form', [
            'item' => new PriceRule, 'isEdit' => false,
            'categories' => Category::active()->orderBy('name')->get(['id', 'name']),
            'materials' => Material::active()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'integer',
                Rule::exists('categories', 'id')->where('is_active', true),
                Rule::unique('price_rules', 'category_id')->where('material_id', $request->input('material_id'))],
            'material_id' => ['required', 'integer', Rule::exists('materials', 'id')->where('is_active', true)],
            'min_price' => ['required', 'integer', 'min:0', 'max:' . self::MAX],
            'max_price' => ['required', 'integer', 'min:0', 'gte:min_price', 'max:' . self::MAX],
        ], $this->messages, $this->attributes);

        $data['is_active'] = true;

        $rule = DB::transaction(function () use ($data) {
            $rule = PriceRule::create($data)->load(['category:id,name', 'material:id,name']);
            AuditLogger::record($rule, 'created', null, AuditLogger::snapshot($rule));
            return $rule;
        });

        return redirect()->route('admin.price-rules.show', $rule->id)->with('success', 'Aturan harga berhasil dibuat.');
    }

    public function show($id)
    {
        $item = PriceRule::with(['category:id,name', 'material:id,name'])->findOrFail($id);

        return view('admin.price-rules.show', compact('item'));
    }

    public function edit($id)
    {
        $item = PriceRule::with(['category:id,name', 'material:id,name'])->findOrFail($id);

        return view('admin.price-rules.form', ['item' => $item, 'isEdit' => true, 'categories' => [], 'materials' => []]);
    }

    public function update(Request $request, $id)
    {
        $rule = PriceRule::with(['category:id,name', 'material:id,name'])->findOrFail($id);

        $data = $request->validate([
            'min_price' => ['required', 'integer', 'min:0', 'max:' . self::MAX],
            'max_price' => ['required', 'integer', 'min:0', 'gte:min_price', 'max:' . self::MAX],
        ], $this->messages, $this->attributes);

        DB::transaction(function () use ($rule, $data) {
            $old = AuditLogger::snapshot($rule);
            $rule->fill($data)->save();
            [$o, $n] = AuditLogger::diff($old, AuditLogger::snapshot($rule));
            if ($n) {
                AuditLogger::record($rule, 'updated', $o, $n);
            }
        });

        return redirect()->route('admin.price-rules.show', $rule->id)->with('success', 'Aturan harga berhasil diperbarui.');
    }

    public function toggle($id)
    {
        $rule = PriceRule::with(['category:id,name', 'material:id,name'])->findOrFail($id);

        $active = DB::transaction(function () use ($rule) {
            $was = (bool) $rule->is_active;
            $rule->is_active = ! $was;
            $rule->save();
            AuditLogger::record($rule, $was ? 'deactivated' : 'activated', ['is_active' => $was], ['is_active' => ! $was]);
            return ! $was;
        });

        return back()->with('success', 'Aturan harga berhasil ' . ($active ? 'diaktifkan.' : 'dinonaktifkan.'));
    }
}
