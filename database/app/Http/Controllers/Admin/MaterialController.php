<?php

namespace App\Http\Controllers\Admin;

use App\Models\Material;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class MaterialController extends MasterDataController
{
    protected string $modelClass = Material::class;
    protected string $routeBase = 'admin.materials';
    protected string $viewDir = 'admin.materials';
    protected string $label = 'Material';

    protected function rules(?Model $record): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('materials', 'name')->ignore($record?->id)],
            'description' => ['nullable', 'string', 'max:2000'],
            'unit' => ['nullable', 'string', 'max:30'],
        ];
    }
}
