<?php

namespace App\Models;

use App\Casts\Json;
use App\Scopes\DisabledColumnsScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisabledColumns extends Model
{
    use HasFactory;

    protected $table = 'disabled_columns';

    protected $fillable = [
        'user_id',
        'route_of_list',
        'columns'
    ];

    protected $casts = [
        'columns' => Json::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new DisabledColumnsScope);
    }
}
