<?php

namespace App\Models;

use App\Traits\HasAuditColumns;
use App\Traits\HasAutoCode;
use Illuminate\Database\Eloquent\Model;

class InstrumentStock extends Model
{
    use HasAuditColumns, HasAutoCode;

    // Status unit instrumen (PRD F6 - monitoring & tracking)
    public const STATUS_TERSEDIA     = 'tersedia';
    public const STATUS_DIPINJAM     = 'dipinjam';
    public const STATUS_STERILISASI  = 'sterilisasi';
    public const STATUS_DIKEMBALIKAN = 'dikembalikan';

    public const STATUSES = [
        self::STATUS_TERSEDIA,
        self::STATUS_DIPINJAM,
        self::STATUS_STERILISASI,
        self::STATUS_DIKEMBALIKAN,
    ];

    protected $fillable = [
        'instrument_id',
        'condition_id',
        'status',
        'created_by',
        'updated_by',
    ];

    protected static function generateUniqueCode($model): string
    {
        $instrument = Instrument::withoutGlobalScopes()->find($model->instrument_id);
        $prefix = $instrument?->code ?? 'UNKN';

        $maxCode = static::withoutGlobalScopes()
            ->where('instrument_id', $model->instrument_id)
            ->where('code', 'like', $prefix . '-%')
            ->max('code');

        $sequence = 1;
        if ($maxCode && preg_match('/-(\d+)$/', $maxCode, $matches)) {
            $sequence = (int) $matches[1] + 1;
        }

        return $prefix . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }
}
