<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;

    protected $table = 'table_roles';
    protected $primaryKey = 'id_roles';

    protected $fillable = ['nama_roles','id_fitur'];
}
