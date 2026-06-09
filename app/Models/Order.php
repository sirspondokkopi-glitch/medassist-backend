<?php

namespace App\Models;

use App\Traits\HasAuditColumns;
use App\Traits\HasAutoCode;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasAuditColumns, HasAutoCode;

    // "order" adalah reserved keyword SQL — wajib di-set eksplisit.
    protected $table = 'order';

    // Status order/peminjaman (PRD §4.6)
    public const STATUS_DIAJUKAN = 'diajukan';

    public const STATUS_DISETUJUI = 'disetujui';

    public const STATUS_DIPINJAM = 'dipinjam';

    public const STATUS_DIKEMBALIKAN = 'dikembalikan';

    public const STATUS_DIBATALKAN = 'dibatalkan';

    public const STATUSES = [
        self::STATUS_DIAJUKAN,
        self::STATUS_DISETUJUI,
        self::STATUS_DIPINJAM,
        self::STATUS_DIKEMBALIKAN,
        self::STATUS_DIBATALKAN,
    ];

    protected $fillable = [
        'room_id',
        'user_id',
        'order_date',
        'return_plan_date',
        'return_actual_date',
        'status',
        'note',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'order_date' => 'date',
        'return_plan_date' => 'date',
        'return_actual_date' => 'date',
    ];

    protected static function generateUniqueCode($model): string
    {
        $maxCode = static::withoutGlobalScopes()
            ->where('code', 'like', 'ORD-%')
            ->max('code');

        $sequence = 1;
        if ($maxCode && preg_match('/-(\d+)$/', $maxCode, $matches)) {
            $sequence = (int) $matches[1] + 1;
        }

        return 'ORD-'.str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
