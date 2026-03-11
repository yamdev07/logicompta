<?php

namespace App\Models\GeneralAccounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Journal extends Model
{
    protected $fillable = ['code', 'name', 'description'];

    public function entries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }
}
