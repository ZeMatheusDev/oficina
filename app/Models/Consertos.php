<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Consertos extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;


    protected $table = 'consertos';

    public $timestamps = false;

    protected $fillable = [
        'problema',
        'valor_cobrado',
        'veiculo',
        'placa',
        'usuario_id',
        'data_finalizacao',
    ];

}
