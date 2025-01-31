<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    use HasFactory;
    protected $table = 'table_cost_center';
    protected $primaryKey = 'id_cost_center';
    protected $fillable = [
        'id_cost_center',
        'cost_center',
        'nama_bagian',
    ];
}
