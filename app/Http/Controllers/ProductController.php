<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = DB::table('products')->when($request->input('name'), function ($query, $name) {
            return $query->where('name', 'like', '%' . $name . '%');
        })->orderBy('id', 'desc')->paginate(10);
        return view('pages.product.index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'stock' => 'required',
            'price' => 'required',
            'description' => 'required|max:255',
            'category' => 'required|in:Drink,Snack,Food',
        ]);

        Product::create($validate);
        return redirect()->route('product.index')->with('success', 'User Successfully Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('pages.product.edit', [
            'products' => $product,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validate = $request->validate([
            'name' => 'required',
            'stock' => 'required',
            'price' => 'required',
            'description' => 'required|max:255',
            'category' => 'required|in:Drink,Snack,Food',
        ]);
        $product->update($validate);
        return redirect()->route('product.index')->with('success', 'Product Successfuly Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('product.index')->with('success', 'Product Successfuly Delete');
    }
}
