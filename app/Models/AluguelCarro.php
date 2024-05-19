<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class AluguelCarro extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;


    protected $table = 'aluguel_carros';

    protected $fillable = [
        'carro_id',
        'user_id',
        'inicio_aluguel',
        'fim_aluguel',
        'valor_total',
        'created_at',
        'updated_at',
    ];

}
