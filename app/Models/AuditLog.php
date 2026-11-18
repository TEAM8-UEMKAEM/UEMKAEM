<?php

namespace App\Models;

use LogicException;
use Illuminate\Database\Eloquent\Model;

/**
 * Riwayat perubahan bersifat append-only: hanya boleh dibuat, tidak boleh diubah/dihapus.
 */
class AuditLog extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id', 'user_name', 'auditable_type', 'auditable_id',
        'action', 'old_values', 'new_values',
    ];

    protected $casts = ['old_values' => 'array', 'new_values' => 'array'];

    protected static function booted(): void
    {
        static::updating(fn () => throw new LogicException('Audit log tidak boleh diubah.'));
        static::deleting(fn () => throw new LogicException('Audit log tidak boleh dihapus.'));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
