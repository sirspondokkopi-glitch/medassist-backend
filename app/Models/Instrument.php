<?php

namespace App\Models;

use App\Traits\HasAuditColumns;
use App\Traits\HasAutoCode;
use Illuminate\Database\Eloquent\Model;

class Instrument extends Model
{
    use HasAuditColumns, HasAutoCode;

    protected $fillable = ['name', 'created_by', 'updated_by'];
}


