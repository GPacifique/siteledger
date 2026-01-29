<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaborExpense extends Model
{
    protected $fillable = [
        'laborer_id',
        'date',
        'units',
        'rate',
        'amount',
    ];

    public function laborer(): BelongsTo
    {
        return $this->belongsTo(Laborer::class);
    }
}
