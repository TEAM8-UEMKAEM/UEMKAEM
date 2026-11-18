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

class PriceRuleController extends Controller
{
    private function find($id): PriceRule
    {
        return PriceRule::with(['category:id,name,is_active', 'material:id,name,is_active'])->findOrFail($id);
    }

    public function index()
    {
        $items = PriceRule::with(['category:id,name,is_active', 'material:id,name,is_active'])->get()
            ->sortBy(fn ($r) => $r->name)->values();

        return view('admin.price-rules.index', compact('items'));
    }

    public function create()
    {
        return view('admin.price-rules.form', [
            'item' => new PriceRule,
            'isEdit' => false,
            // hanya data master aktif yang boleh dipilih untuk aturan baru
            'categories' => Category::active()->select('id', 'name')->orderBy('name')->get(),
            'materials' => Material::active()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')->where('is_active', true)],
            'material_id' => [
                'required', 'integer', Rule::exists('materials', 'id')->where('is_active', true),
                Rule::unique('price_rules', 'material_id')->where('category_id', $request->input('category_id')),
            ],
            'min_price' => ['required', 'integer', 'min:0', 'max:2000000000'],
            'max_price' => ['required', 'integer', 'min:0', 'max:2000000000', 'gte:min_price'],
        ], $this->messages());

        $rule = DB::transaction(function () use ($data) {
            $rule = PriceRule::create($data + ['is_active' => true]);
            $rule->load(['category:id,name', 'material:id,name']);
            AuditLogger::record($rule, 'created', null, AuditLogger::snapshot($rule));
            return $rule;
        });

        return redirect()->route('admin.price-rules.show', $rule->id)->with('success', 'Aturan harga berhasil dibuat.');
    }

    public function show($id)
    {
        return view('admin.price-rules.show', ['item' => $this->find($id)]);
    }

    public function edit($id)
    {
        return view('admin.price-rules.form', ['item' => $this->find($id), 'isEdit' => true, 'categories' => collect(), 'materials' => collect()]);
    }

    // Kombinasi kategori + material tidak dapat diubah; untuk kombinasi lain buat aturan baru.
    public function update(Request $request, $id)
    {
        $rule = $this->find($id);

        $data = $request->validate([
            'min_price' => ['required', 'integer', 'min:0', 'max:2000000000'],
            'max_price' => ['required', 'integer', 'min:0', 'max:2000000000', 'gte:min_price'],
        ], $this->messages());

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
        $rule = $this->find($id);

        $active = DB::transaction(function () use ($rule) {
            $was = (bool) $rule->is_active;
            $rule->is_active = ! $was;
            $rule->save();
            AuditLogger::record($rule, $was ? 'deactivated' : 'activated', ['is_active' => $was], ['is_active' => ! $was]);
            return ! $was;
        });

        return back()->with('success', 'Aturan harga berhasil ' . ($active ? 'diaktifkan.' : 'dinonaktifkan.'));
    }

    private function messages(): array
    {
        return [
            'category_id.exists' => 'Kategori tidak valid atau sudah dinonaktifkan.',
            'material_id.exists' => 'Material tidak valid atau sudah dinonaktifkan.',
            'material_id.unique' => 'Aturan untuk kombinasi kategori dan material ini sudah ada. Ubah atau aktifkan kembali aturan tersebut.',
            'min_price.min' => 'Harga minimum tidak boleh negatif.',
            'max_price.min' => 'Harga maksimum tidak boleh negatif.',
            'max_price.gte' => 'Harga maksimum tidak boleh lebih kecil dari harga minimum.',
        ];
    }
}
