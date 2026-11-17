<?php

namespace App\Http\Controllers\Admin;

use App\Models\Region;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class RegionController extends MasterDataController
{
    protected string $modelClass = Region::class;
    protected string $routeBase = 'admin.regions';
    protected string $viewDir = 'admin.regions';
    protected string $label = 'Wilayah';

    protected function rules(?Model $record): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('regions', 'name')->ignore($record?->id)],
            'province' => ['nullable', 'string', 'max:255'],
        ];
    }
}
