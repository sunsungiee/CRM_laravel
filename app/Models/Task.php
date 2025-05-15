<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function getFormattedDateAttribute()
    {
        return $this->date ? \Carbon\Carbon::parse($this->end_date)->format('d.m.Y') : '';
    }

    public function getFormattedTimeAttribute()
    {
        return $this->time ? \Carbon\Carbon::parse($this->end_time)->format('H:i') : '';
    }

    public function status()
    {
        return $this->belongsTo(Status::class, "status_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class, 'priority_id');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }
}
