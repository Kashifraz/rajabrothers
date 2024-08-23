<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::query();
        $stock = request('stock');
        $search = request('search');
        if (request('search')) {
            $products->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            });
        }

        if (request('stock')) {
            if ($stock == 1)
                $products->where('quantity', '>', 1);
            else
                $products->where('quantity', '=', 0);
        }
        $categories = Category::all();
        return view('product', [
            "products" => $products->paginate(4),
            "categories" => $categories,
            "stock" => $stock,
            "search" => $search
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|min:3|max:50',
            'category_id' => 'required',
            'description' => 'required|min:10|max:500',
            'unit' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:5000',
        ]);

        $imageName = null;
        if ($request->image) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            Product::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'unit' => $request->unit,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'image' => $imageName,
            ]);
        }

        return redirect()->back()->with('success', 'Product added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|min:3|max:50',
            'category_id' => 'required',
            'description' => 'required|min:10|max:500',
            'unit' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'image' => 'image|mimes:png,jpg,jpeg|max:5000',
        ]);

        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->unit = $request->unit;
        $product->quantity = $request->quantity;
        $product->price = $request->price;
        $product->description = $request->description;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete the old image if necessary
            if ($product->image && $product->image != "NA") {
                $oldImagePath = public_path('images/' . $product->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Upload new image
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
            $product->image = $imageName;
        }

        $product->save();

        return redirect()->back()->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully.');
    }
}
