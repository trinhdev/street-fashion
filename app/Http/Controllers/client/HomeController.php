<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\favorite_product;


class HomeController extends Controller
{
    /**
     * Get products with different filters
     *
     */
    public function getProducts()
    {
        $user = auth()->user();

        // Get all products
        $result_product = Product::with(['product_meta', 'color', 'size'])
            ->whereDoesntHave('product_meta', function($query) {
                $query->where('product_sale', 'sale')
                    ->orWhere('default', 'default');
            })
            ->limit(12)
            ->get();

        // Get default products (excluding sale products)
        $result_product_default = Product::with(['product_meta', 'color', 'size'])
            ->whereHas('product_meta', function ($query) {
                $query->where('default', 'default');
            })
            ->whereDoesntHave('product_meta', function ($query) {
                $query->where('product_sale', 'sale');
            })
            ->get();

        // Get sale products only
        $result_product_sale = Product::with(['product_meta', 'color', 'size'])
            ->whereHas('product_meta', function ($query) {
                $query->where('product_sale', 'sale')
                    ->where('default', '!=', 'default');
            })
            ->get();

        // Remove duplicates by product ID
        $result_product = $result_product->unique('id');
        $result_product_default = $result_product_default->unique('id');
        $result_product_sale = $result_product_sale->unique('id');

        return compact('result_product', 'result_product_default', 'result_product_sale', 'user');
    }

    /**
     * Display homepage with products
     *
     */
    public function index()
    {
        $products = $this->getProducts();

        return view('client.home', $products);
    }

    /**
     * Toggle favorite status for a product
     */
    public function addFavorite($id)
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $product = Product::findOrFail($id);
        $favorite = $user->favorite_product()->where('id_product', $product->id)->first();

        if ($favorite) {
            $favorite->delete();
            $isFavorite = false;
        } else {
            $user->favorite_product()->create(['id_product' => $product->id]);
            $isFavorite = true;
        }

        return redirect()->back()->with('success', 'Cập nhật sản phẩm yêu thích thành công');
    }

    public function favoriteProduct()
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $favorite_products = $user->favorite_product()->with('product')->get();
        
        return view('client.favorite_product', compact('favorite_products'));
    }
}