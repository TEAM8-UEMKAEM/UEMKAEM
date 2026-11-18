<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuditLogger;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Logika CRUD yang sama untuk Kategori, Material, dan Wilayah.
 * Tidak ada method hapus: data master hanya dinonaktifkan (toggle).
 */
abstract class MasterDataController extends Controller
{
    protected string $modelClass;
    protected string $routeBase;   // contoh: admin.categories
    protected string $viewDir;     // contoh: admin.categories
    protected string $label;       // contoh: Kategori
    protected bool $imageChanged = false;

    abstract protected function rules(?Model $record): array;

    protected function listQuery(): Builder
    {
        return $this->modelClass::query()->withCount('products');
    }

    protected function findRecord($id): Model
    {
        return $this->modelClass::findOrFail($id);
    }

    /** Hook untuk mengubah data tervalidasi sebelum disimpan. */
    protected function prepare(array $data, ?Model $record, Request $request): array
    {
        return $data;
    }

    public function index()
    {
        return view("{$this->viewDir}.index", ['items' => $this->listQuery()->orderBy('name')->get()]);
    }

    public function create()
    {
        return view("{$this->viewDir}.form", ['item' => new ($this->modelClass), 'isEdit' => false]);
    }

    public function show($id)
    {
        return view("{$this->viewDir}.show", ['item' => $this->findRecord($id)->loadCount('products')]);
    }

    public function edit($id)
    {
        return view("{$this->viewDir}.form", ['item' => $this->findRecord($id), 'isEdit' => true]);
    }

    public function store(Request $request)
    {
        $data = $this->prepare($request->validate($this->rules(null)), null, $request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['is_active'] = true;

        $item = DB::transaction(function () use ($data) {
            $item = $this->modelClass::create($data);
            AuditLogger::record($item, 'created', null, AuditLogger::snapshot($item));
            return $item;
        });

        return redirect()->route("{$this->routeBase}.show", $item->id)
            ->with('success', "{$this->label} berhasil dibuat.");
    }

    public function update(Request $request, $id)
    {
        $item = $this->findRecord($id);
        $data = $this->prepare($request->validate($this->rules($item)), $item, $request);

        DB::transaction(function () use ($item, $data) {
            $old = AuditLogger::snapshot($item);
            $item->fill($data)->save();
            [$o, $n] = AuditLogger::diff($old, AuditLogger::snapshot($item));

            if ($this->imageChanged) {
                $o['image'] = '(gambar lama)';
                $n['image'] = '(gambar baru)';
            }
            if ($n) {
                AuditLogger::record($item, 'updated', $o, $n);
            }
        });

        return redirect()->route("{$this->routeBase}.show", $item->id)
            ->with('success', "{$this->label} berhasil diperbarui.");
    }

    public function toggle($id)
    {
        $item = $this->findRecord($id);

        $active = DB::transaction(function () use ($item) {
            $was = (bool) $item->is_active;
            $item->is_active = ! $was;
            $item->save();
            AuditLogger::record($item, $was ? 'deactivated' : 'activated', ['is_active' => $was], ['is_active' => ! $was]);
            return ! $was;
        });

        return back()->with('success', $this->label . ' berhasil ' . ($active ? 'diaktifkan.' : 'dinonaktifkan.'));
    }

    protected function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'item';
        $slug = $base;
        $i = 2;
        while ($this->modelClass::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
