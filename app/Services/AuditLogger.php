<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

/**
 * Satu-satunya tempat yang menulis riwayat perubahan.
 * Gambar (blob) dan kolom waktu sengaja tidak ikut disimpan.
 */
class AuditLogger
{
    public static function snapshot(Model $model): array
    {
        return Arr::except($model->attributesToArray(), ['id', 'image', 'created_at', 'updated_at']);
    }

    /** Ambil hanya kolom yang benar-benar berubah. @return array{0: array, 1: array} */
    public static function diff(array $old, array $new): array
    {
        $o = [];
        $n = [];
        foreach ($new as $key => $value) {
            if (self::norm($old[$key] ?? null) !== self::norm($value)) {
                $o[$key] = $old[$key] ?? null;
                $n[$key] = $value;
            }
        }
        return [$o, $n];
    }

    public static function record(Model $model, string $action, ?array $old = null, ?array $new = null): void
    {
        $user = Auth::user();

        // sertakan nama record agar riwayat mudah dibaca
        if ($new !== null && ! array_key_exists('name', $new) && isset($model->name)) {
            $new['name'] = $model->name;
        }

        $key = $model->getKey();

        AuditLog::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'system',
            'auditable_type' => class_basename($model),
            'auditable_id' => is_numeric($key) ? (int) $key : null, // Setting memakai kunci teks
            'action' => $action,
            'old_values' => $old,
            'new_values' => $new,
        ]);
    }

    private static function norm(mixed $v): ?string
    {
        if ($v === null) {
            return null;
        }
        return is_bool($v) ? (string) (int) $v : (string) $v;
    }
}
