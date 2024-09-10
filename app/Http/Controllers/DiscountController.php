<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Ad;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   
     public function index()
     {
         $ads = Ad::all();
         return view('ads',[
             "ads" => $ads,
         ]);
     }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    public function store(Request $request)
    {
       // Validate the request input (title and image with dimensions check)
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:width=368,height=117',
        ], [
            'image.dimensions' => 'The advertisement image must be exactly 368x117 pixels.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        // Store the image in the public/ads directory
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/ads'), $imageName);

        // Create the ad in the database
        Ad::create([
            'title' => $request->title,
            'image' => $imageName,
        ]);

        return redirect()->back()->with('success', 'Ad created successfully.');
    }


    //get all discounts ads -- api for react native app
    public function getAds()
    {
        // Fetch all ads from the database
        $ads = Ad::all();

        // Modify the image path to return full URL
        $ads = $ads->map(function($ad) {
            $ad->image = url('images/ads/' . $ad->image);
            return $ad;
        });

        // Return the ads as JSON
        return response()->json([
            'status' => 'success',
            'ads' => $ads
        ], 200);
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
