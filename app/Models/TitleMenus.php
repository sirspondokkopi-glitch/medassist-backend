<?php

namespace App\Models;

use App\Traits\HasAuditColumns;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TitleMenus extends Model
{
    use HasAuditColumns;

    protected $fillable = ['title', 'icon', 'sort_order', 'is_active', 'created_by', 'updated_by'];

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class, 'title_menu_id')->orderBy('sort_order');
    }
}
