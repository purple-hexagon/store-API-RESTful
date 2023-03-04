<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShopProduct extends Model
{
    use HasFactory;

    const QUANTITY_HIGH = 10;
    const QUANTITY_MEDIUM = 7;
    const QUANTITY_LOW = 3;

    protected $table = "shops_products";

    public function updateDB(): void
    {
        DB::statement(
            "UPDATE $this->table SET quantity = $this->quantity, updated_at = CURRENT_TIMESTAMP
                    WHERE shop_id = $this->shop_id AND product_id = $this->product_id"
        );
    }
}
