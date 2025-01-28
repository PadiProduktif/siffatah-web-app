<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'is_users';
    protected $primaryKey = 'id_user';

    // Kolom yang dapat diisi
    protected $fillable = [
        'username',
        'fullname',
        'password',
        'role',
        'status',
        'verify_key',
        'active',
        'list_karyawan',
        'created_by',
        'updated_by'
    ];

    // Kolom yang disembunyikan dari array atau JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Tipe data yang harus di-cast
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}