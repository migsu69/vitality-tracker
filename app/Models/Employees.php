<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    protected $fillable = [
        'employee_id',
        'last_name',
        'first_name',
        'middle_name',
    ];

    public function vitals()
    {
        return $this->hasMany(Vitals::class, 'employee_id');
    }
}
