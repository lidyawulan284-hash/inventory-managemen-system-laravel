<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'description',
        'price',
        'stock',
        'minimum_stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'minimum_stock' => 'integer',
    ];

    /**
     * Category relation (One-to-Many inverse)
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Suppliers relation (Many-to-Many)
     */
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'product_suppliers')
            ->withPivot('supplier_price')
            ->withTimestamps();
    }

    /**
     * Stock movements relation (One-to-Many)
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Check if product is in low stock condition
     */
    public function isLowStock(): bool
    {
        return $this->stock <= $this->minimum_stock;
    }

    /**
     * Scope to search by SKU or Name
     */
    public function scopeSearch(Builder $query, ?string $search)
    {
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Scope to filter by Category
     */
    public function scopeFilterByCategory(Builder $query, $categoryId)
    {
        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }
        return $query;
    }

    /**
     * Scope for low stock products
     */
    public function scopeLowStock(Builder $query)
    {
        return $query->whereColumn('stock', '<=', 'minimum_stock');
    }

    /**
     * Safe sorting using whitelist from slide 16 & 33
     */
    public function scopeApplySorting(Builder $query, ?string $sortBy = 'name', ?string $direction = 'asc')
    {
        $allowedColumns = ['name', 'sku', 'price', 'stock', 'created_at'];
        $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';

        if (in_array($sortBy, $allowedColumns)) {
            $query->orderBy($sortBy, $direction);
        } else {
            $query->orderBy('name', 'asc');
        }

        return $query;
    }
}
