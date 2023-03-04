<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopProduct;
use InvalidArgumentException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Ramsey\Uuid\Type\Integer;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $shops = Shop::all();
            $shopsWithProducts = [];
            foreach ($shops as $shop) {
                $shopsWithProducts[] = array_merge(
                    $shop->toArray(),
                    ['products_count' => $shop->products->count()]
                );
            }

            return response()->json($shopsWithProducts);
        } catch (Exception $e) {
            return $this->responseGenericError($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Create the quantity of a ShopProduct given a shop and a product.
     *
     * @param Int $shopId
     * @param Int $productId
     * @param Int $quantity
     * @return \App\Models\ShopProduct
     */
    private function createShopProductQuantity(int $shopId, int $productId, int $quantity): ShopProduct
    {
        if($shopId <= 0) throw new InvalidArgumentException('Shop id must be greater than 0');
        if($productId <= 0) throw new InvalidArgumentException('Product id must be greater than 0');
        if($quantity < 0) throw new InvalidArgumentException('Quantity must be positive');

        $shopProduct = new ShopProduct();
        $shopProduct->shop_id = $shopId;
        $shopProduct->product_id = $productId;
        $shopProduct->quantity = $quantity;
        $shopProduct->save();

        return $shopProduct;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Shop
            $shopNameRequest = $request->get('name');
            if (!$shopNameRequest) return response()->json(['message' => 'Shop name not found']);

            $currentShop = Shop::where('name', $shopNameRequest)->first();
            $responseShopMessage = 'Shop created successfully';
            $shop = null;
            if ($currentShop) {
                return response()->json([
                    'message' => "The shop '$currentShop->name' already exists",
                    'shop' => $currentShop
                ]);
            } else {
                $shop = new Shop();
                $shop->name = $shopNameRequest;
                $shop->save();
            }

            // Products
            $productsRequest = $request->get('products');
            $responseProducts = 'No products to add';
            if ($productsRequest) {
                $responseProducts = [];

                foreach ($productsRequest as $productRequest) {
                    $productNameRequest = $productRequest['name'];
                    $currentProduct = Product::where('name', $productNameRequest)->first();
                    if ($currentProduct) {
                        $responseProductMessageBase = 'Existing product';
                    } else {
                        // Create Product
                        $currentProduct = new Product();
                        $currentProduct->name = $productNameRequest;
                        $currentProduct->save();
                        $responseProductMessageBase = 'Product created';
                    }

                    $responseProductMessage = $responseProductMessageBase . ', quantities updated';
                    $responseProductQuantity = '';
                    try {
                        // Create ShopProduct
                        $currentShopProduct = $this->createShopProductQuantity($shop->id, $currentProduct->id, $productRequest['quantity']);
                        $responseProductQuantity = $currentShopProduct->quantity;
                    } catch (InvalidArgumentException $e) {
                        $responseProductMessage = $responseProductMessageBase . ', error updating quantity: ' . $e->getMessage();
                    } catch (Exception $e) {
                        $responseProductMessage = 'Error creating quantities';
                    }
                    $responseProducts[] = [
                        'message' => $responseProductMessage,
                        'product' => $currentProduct,
                        'quantity' => $responseProductQuantity
                    ];
                }
            }

            return response()->json([
                'message' => $responseShopMessage,
                'shop' => $shop,
                'products' => $responseProducts
            ]);
        } catch (Exception $e) {
            return $this->responseGenericError($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Integer $shopId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $shopId): JsonResponse
    {
        try {
            $shop = Shop::find($shopId);
            if(!$shop) return $this->responseShopNotFound();

            return response()->json(array_merge(
                $shop->toArray(),
                ['products' => $shop->products]
            ));
        } catch (Exception $e) {
            return $this->responseGenericError($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Integer $shopId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $shopId): JsonResponse
    {
        try {
            $shop = Shop::find($shopId);
            if(!$shop) return $this->responseShopNotFound();
            $shop->name = $request->get('name');
            $shop->save();
            $data = [
                'message' => 'Shop updated successfully',
                'shop' => $shop
            ];

            return response()->json($data);
        } catch (Exception $e) {
            return $this->responseGenericError($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Integer $shopId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $shopId): JsonResponse
    {
        try {
            $shop = Shop::find($shopId);
            if(!$shop) return $this->responseShopNotFound();
            // $shop->products()->detach(); // In ShopObserver.
            $shop->delete();
            $data = [
                'message' => 'Shop deleted successfully',
                'shop' => $shop
            ];

            return response()->json($data);
        } catch (Exception $e) {
            return $this->responseGenericError($e);
        }
    }
}
