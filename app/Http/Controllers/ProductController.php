<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    public function index(Request $request)
    {

        // get data product
        $products = DB::table('products')->when($request->input('name'), function ($query, $name) {
            return $query->where('name', 'like', '%' . $name . '%');
        })->orderBy('id', 'desc')->paginate(10);
        return view('pages.product.index', compact('products'));
    }


    public function create()
    {
        return view('pages.product.create');
    }

    // create
    public function store(Request $request)
    {
        $data = $request->all();
        \App\Models\Product::create($data);
        return redirect()->route('product.index')->with('success', 'User Successfully Created');
    }


    public function edit($id)
    {
        $products = \App\Models\Product::findOrFail($id);
        return view('pages.product.edit', compact('products'));
    }

    // update
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $products = \App\Models\Product::findOrFail($id);
        $products->update($data);
        return redirect()->route('product.index')->with('success', 'Product Successfuly Update');
    }

    // hapus
    public function destroy($id)
    {
        $products = \App\Models\Product::findOrFail($id);
        $products->delete();
        return redirect()->route('product.index')->with('success', 'Product Successfuly Delete');
    }
}
