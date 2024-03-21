<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLoan extends Model
{
    use HasFactory;

    protected $fillable = [
      'user_id',
      'amount',
      'purpose',
      'installments',
      'transferred_at',
      'status',
    ];

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('purpose', 'LIKE', '%' . $search . '%')
                ->orWhere('amount', 'LIKE', '%' . $search . '%')
                ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('name', 'LIKE', '%' . $search . '%')
                        ->orWhere('email', 'LIKE', '%' . $search . '%');
                });
            });
        }
        return $query;
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
