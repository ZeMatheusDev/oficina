<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class ConfigMotoss extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;


    protected $table = 'config_motos';

    protected $fillable = [
        'nome',
        'marca',
        'empresa',
        'cor',
        'alugado',
        'vendido',
        'valor_compra',
        'valor_diaria',
        'status',
        'token',
        'anexo',
        'observacoes',
        'deleted',
        'created_at',
        'updated_at',
    ];

}