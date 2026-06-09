<?php

namespace App\Models;

use App\Traits\HasAuditColumns;
use App\Traits\HasAutoCode;
use Illuminate\Database\Eloquent\Model;

class InstrumentSet extends Model
{
    use HasAuditColumns, HasAutoCode;

    protected $fillable = [
        'name',
        'room_id',
        'status',
        'note',
        'created_by',
        'updated_by',
    ];

    protected static function generateUniqueCode($model): string
    {
        $maxCode = static::withoutGlobalScopes()
            ->where('code', 'like', 'SET-%')
            ->max('code');

        $sequence = 1;
        if ($maxCode && preg_match('/-(\d+)$/', $maxCode, $matches)) {
            $sequence = (int) $matches[1] + 1;
        }

        return 'SET-'.str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function items()
    {
        return $this->hasMany(InstrumentSetItem::class);
    }
}
