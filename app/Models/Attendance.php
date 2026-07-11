<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'scan_by_user_id',
        'date',
        'check_in',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function scanner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scan_by_user_id');
    }
}
