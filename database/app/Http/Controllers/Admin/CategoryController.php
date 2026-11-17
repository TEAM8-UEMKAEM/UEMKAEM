<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends MasterDataController
{
    protected string $modelClass = Category::class;
    protected string $routeBase = 'admin.categories';
    protected string $viewDir = 'admin.categories';
    protected string $label = 'Kategori';

    // Daftar admin tidak memuat kolom gambar (blob) agar ringan.
    protected function listQuery(): Builder
    {
        return Category::query()
            ->select(['id', 'name', 'slug', 'group', 'artisans_count', 'is_active'])
            ->selectRaw('(image is not null) as has_image')
            ->withCount('products');
    }

    protected function rules(?Model $record): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($record?->id)],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'badge' => ['nullable', 'string', 'max:255'],
            'group' => ['nullable', 'string', 'max:255'],
            'regions' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'string', 'max:1000'],   // dipisah koma
            'artisans_count' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // KB
        ];
    }

    // Gambar disimpan sebagai data biner di kolom `image` (sama seperti data yang sudah ada).
    protected function prepare(array $data, ?Model $record, Request $request): array
    {
        unset($data['image']);

        if ($request->hasFile('image')) {
            $data['image'] = file_get_contents($request->file('image')->getRealPath());
            $this->imageChanged = true;
        }

        return $data;
    }
}
