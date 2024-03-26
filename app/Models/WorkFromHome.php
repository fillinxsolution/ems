<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkFromHome extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'check_in',
        'check_out',
        'minutes',
        'date',
        'salary',
        'details',
        'salary_month_id'
    ];

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('date', 'LIKE', '%' . $search . '%')
                ->orWhere('details', 'LIKE', '%' . $search . '%')
                ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('name', 'LIKE', '%' . $search . '%')
                        ->orWhere('email', 'LIKE', '%' . $search . '%');
                });
            });
        }
        return $query;
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
