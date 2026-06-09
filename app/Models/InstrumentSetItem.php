<?php

namespace App\Models;

use App\Traits\HasAuditColumns;
use Illuminate\Database\Eloquent\Model;

class InstrumentSetItem extends Model
{
    use HasAuditColumns;

    protected $fillable = [
        'instrument_set_id',
        'instrument_stock_id',
        'created_by',
        'updated_by',
    ];

    public function instrumentSet()
    {
        return $this->belongsTo(InstrumentSet::class);
    }

    public function instrumentStock()
    {
        return $this->belongsTo(InstrumentStock::class);
    }
}
