<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Transaction extends Model
{
    use HasFactory, BelongsToTenant;

    // If you use soft deletes, uncomment:
    // use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $table = 'transactions';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tenant_id',
        'reference',
        'date',
        'type',      // e.g. 'revenue' | 'expense' | 'payroll'
        'category',  // e.g. 'subscriptions', 'rent', 'salaries'
        'amount',
        'notes',
        'meta',      // json column for extra data (optional)
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'date'   => 'date',
        'amount' => 'decimal:2',
        'meta'   => 'array',
    ];

    /**
     * Scopes
     */
    public function scopeBetweenDates($query, $from = null, $to = null)
    {
        if ($from) {
            $query->whereDate('date', '>=', $from);
        }
        if ($to) {
            $query->whereDate('date', '<=', $to);
        }
        return $query;
    }

    public function scopeOfType($query, $type = null)
    {
        return $type ? $query->where('type', $type) : $query;
    }

    public function scopeOfCategory($query, $category = null)
    {
        return $category ? $query->where('category', $category) : $query;
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('date', 'desc')->limit($limit);
    }

    /**
     * Accessors / Mutators
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2);
    }

    // Example relationship if transactions belong to a company or user
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}
