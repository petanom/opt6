<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Products extends Model
{
    use HasFactory;

    public function products(): BelongsToMany
    {
        //return $this->belongsToMany(RelatedModel, pivot_table_name, foreign_key_of_current_model_in_pivot_table, foreign_key_of_other_model_in_pivot_table);
        return $this->belongsToMany(
            Orders::class,
            'orders_products',
            'product_id',
            'order_id',
        )->withPivot('qnt');
    }

    protected $fillable = [
        'name',
        'price',
    ];
}
