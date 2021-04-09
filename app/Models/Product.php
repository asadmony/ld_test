<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'sku', 'description'
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function variantTypes()
    {
        return $this->hasManyThrough(Variant::class, ProductVariant::class, 'product_id', 'id', 'id', 'variant_id')->groupBy('id', 'title', 'description', 'created_at', 'updated_at', 'product_variants.product_id');
    }

    public function variantPrices()
    {
        return $this->hasMany(ProductVariantPrice::class, 'product_id')->with('productVariantOne', 'productVariantTwo', 'productVariantThree');
    }


    public function getAllProducts($pagination)
    {
        return $this->latest()->paginate($pagination);
    }

    public function filter($title = null, $variant = null, $priceFrom = null, $priceTo = null, $date = null)
    {
        return $this->where(function ($query) use ($title,$variant,$priceFrom,$priceTo,$date){
            if ($title != "") {
                $query->where('title', 'like',"%{$title}%");
            }
            if ($date != "") {
                $query->whereDate('created_at', $date);
            }
            // if ($variant != "") {
            //     $query->variants()->where('variant', 'like' , "%{$variant}%");
            // }
            // if ($priceFrom != "") {
            //     $query->variantPrices()->where('price', '>=', $priceFrom);
            // }
            // if ($priceTo != "") {
            //     $query->variantPrices()->where('price', '<=', $priceTo);
            // }
          })
        //   ->groupBy('id')
            ->latest()
          ->paginate(2);
    }

}
