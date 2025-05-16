<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{

    public function products():HasMany
    {
        return $this->hasMany(Product::class,'category_id');
    }


    //misal nama table beda
    //protected $table = "ProdukKategori"

    //misal nama primaryKey bukan ID
    //protected $primaryKey = "prod_kat_id"

    
}
