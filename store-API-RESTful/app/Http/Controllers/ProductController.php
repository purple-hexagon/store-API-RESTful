<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopProduct;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Buy product from a shop.
     *
     * @param \Illuminate\Http\Request $request
     * @param Integer $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function buy(Request $request, int $productId): JsonResponse
    {
        try {
            $product = Product::find($productId);
            if(!$product) return $this->responseProductNotFound();

            $shopId = $request->get('shop');
            $shop = Shop::find($shopId);
            if(!$shop) return $this->responseShopNotFound();

            $shopProduct = ShopProduct::where([
                'shop_id' => $shop->id,
                'product_id' => $product->id
            ])->first();

            $productInfo = "$product->id ($product->name)";
            $shopInfo = "$shop->id ($shop->name)";

            // ShopProduct does not exist
            if(!$shopProduct) {
                return response()->json([
                    'message' => "Product $productInfo not available for shop $shopInfo"
                ]);
            }

            $quantityPurchased = $request->get('quantity_purchased');
            $canBuy = $shopProduct->quantity >= $quantityPurchased;

            // Quantity purchased is greater than quantity available
            if(!$canBuy) {
                return response()->json([
                    'message' => "Purchase cannot be made due to lack of stock (Quantity ordered: $quantityPurchased, current quantity: $shopProduct->quantity)"
                ]);
            }

            // Buy
            $shopProduct->quantity -= $quantityPurchased;
            //$shopProduct->save(); // Multiple primary key error.
            $shopProduct->updateDB();

            // Message stock
            $stockInfo = $this->getStockInfo($shopProduct->quantity);

            return response()->json([
                'message' => "You have purchased $quantityPurchased units of product $productInfo in shop $shopInfo",
                'stock_info' => $stockInfo,
                'remaining_quantity' => $shopProduct->quantity
            ]);
        } catch (Exception $e) {
            return $this->responseGenericError($e);
        }
    }

    /**
     * Calculate stock message.
     *
     * @param String $quantity
     * @return String
     */
    private function getStockInfo(String $quantity): String
    {
        $stockInfo = "Very high stock left";
        if($quantity == 0) {
            $stockInfo = "Zero stock left";
        } elseif($quantity <= ShopProduct::QUANTITY_LOW) {
            $stockInfo = "Low stock left";
        } elseif($quantity <= ShopProduct::QUANTITY_MEDIUM) {
            $stockInfo = "Medium stock left";
        } elseif($quantity <= ShopProduct::QUANTITY_HIGH) {
            $stockInfo = "High stock left";
        }
        return $stockInfo;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
