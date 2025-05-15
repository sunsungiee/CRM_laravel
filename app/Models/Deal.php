<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'contact_id',
        'subject',
        'sum',
        'end_date',
        'end_time',
        'phase_id',
        'user_id',
    ];

    public function getFormattedDateAttribute()
    {
        return $this->end_date ? \Carbon\Carbon::parse($this->end_date)->format('d.m.Y') : '';
    }

    public function getFormattedTimeAttribute()
    {
        return $this->end_time ? \Carbon\Carbon::parse($this->end_time)->format('H:i') : '';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }
}
