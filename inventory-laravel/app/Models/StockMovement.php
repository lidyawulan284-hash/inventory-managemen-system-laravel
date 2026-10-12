<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * Product associated with movement
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * User who executed the transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Filter by type (IN or OUT)
     */
    public function scopeFilterByType(Builder $query, ?string $type)
    {
        if ($type && in_array(strtoupper($type), ['IN', 'OUT'])) {
            $query->where('type', strtoupper($type));
        }
        return $query;
    }

    /**
     * Filter by product ID
     */
    public function scopeFilterByProduct(Builder $query, $productId)
    {
        if (!empty($productId)) {
            $query->where('product_id', $productId);
        }
        return $query;
    }
}
