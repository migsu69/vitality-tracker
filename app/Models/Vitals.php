<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vitals extends Model
{
    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'pulse_rate',
        'body_temperature',
        'respiratory_rate',
        'bp',
        'bmi',
    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }
}
