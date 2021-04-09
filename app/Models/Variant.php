<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $fillable = [
        'title', 'description'
    ];


    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class, 'variant_id');
    }

    public function selectedProductVariants(Product $product)
    {
        $this->productVariants()->where('product_id', $product->id);
    }

}
