<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Payment extends Model
{
    use HasFactory, BelongsToTenant;

    /**
     * Employee/Worker associated with this payment (if any)
     */
    public function employee()
    {
        return $this->belongsTo(\App\Models\Worker::class, 'employee_id');
    }

    protected $fillable = [
        'tenant_id',
        'user_id',
        'amount',
        'payment_date',
        'category',
        'method',
        'reference',
        'status',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    /**
     * Category constants for company payments
     */
    public const CATEGORIES = [
        'utilities' => 'Utilities (Electric, Water, Internet)',
        'rent' => 'Rent',
        'insurance' => 'Insurance',
        'office_supplies' => 'Office Supplies',
        'software' => 'Software & Subscriptions',
        'maintenance' => 'Maintenance & Repairs',
        'taxes' => 'Taxes & Licenses',
        'travel' => 'Travel & Transport',
        'marketing' => 'Marketing & Advertising',
        'professional_services' => 'Professional Services',
        'other' => 'Other',
    ];

    /**
     * User who created this payment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
