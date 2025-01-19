<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menstruation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_date',
        'start_time',
        'prayer_start',
        'end_date',
        'end_time',
        'prayer_end',
        'status', // Tambahkan kolom ini
    ];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
