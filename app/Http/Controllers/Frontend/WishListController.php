<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('frontend.wishlist.wishlist');
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
    public function store(Request $request, $course_id)
    {
        if (Auth::check()) {
            $exists = Wishlist::where('user_id',Auth::id())->where('course_id',$course_id)->first();

            if (!$exists) {
             Wishlist::insert([
                 'user_id' => Auth::id(),
                 'course_id' => $course_id,
                 'created_at' => Carbon::now(),
             ]);
             return response()->json(['success' => 'Successfully Added on your Wishlist']);
            }else {
             return response()->json(['error' => 'This Course Has Already on your withlist']);
            }

         }else{
             return response()->json(['error' => 'At First Login Your Account']);
         }
    }

    /**
     * Display the specified resource.
     */
    public function show(Wishlist $wishlist)
    {
        $wishQty = Wishlist::with('course')->where('user_id',Auth::id())->count();
        return response()->json(['wishlist' => $wishlist::with('course')->where('user_id',Auth::id())->latest()->get(), 'wishQty' => $wishQty]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wishlist $wishlist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wishlist $wishlist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wishlist $wishlist, $id)
    {
        $wishlist::where('user_id',Auth::id())->where('id',$id)->delete();
        return response()->json(['success' => 'Course Successfully Remove from Wishlist']);

    }
}
