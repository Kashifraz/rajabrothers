<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::query();
        $search = request('search');
        if (request('search')) {
            $categories->where(function ($q) use ($search) {
               $q->where('name', 'LIKE', '%' . $search . '%');
            });
         }
        // $categories->paginate(3);
        return view('category', [
            'categories' => $categories->paginate(3),
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:3|max:50',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:5000'
        ]);

        $imageName = null;
        if ($request->image) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            Category::create([
                'name' => $request->name,
                'image' => $imageName,
            ]);
        }

        return redirect()->back()->with('success', 'Category added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|min:3|max:50',
            'image' => 'image|mimes:png,jpg,jpeg|max:5000'
        ]);
        // Update category name
        $category->name = $request->name;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete the old image if necessary
            if ($category->image && $category->image != "NA") {
                $oldImagePath = public_path('images/' . $category->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Upload new image
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
            $category->image = $imageName;
        }

        $category->save();

        return redirect()->back()->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }
}
