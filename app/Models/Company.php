<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;


    protected $table = 'companies';

    protected $fillable = [
        'name',
        'logo_path',
        'cnpj',
        'quality_responsable',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime: d/m/Y h:i:s'
    ];

    public function getLogoPathAttribute()
    {
        return route('get.files', str_replace('/', '-', $this->attributes['logo_path']));
    }
}
