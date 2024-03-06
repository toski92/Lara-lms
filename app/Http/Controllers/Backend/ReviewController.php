<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReviewController extends Controller
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
        // dd($request->all());
        $course = $request->course_id;
        $instructor = $request->instructor_id;

        $request->validate([
            'comment' => 'required',
            'rate' => 'required'
        ]);

        Review::insert([
            'course_id' => $course,
            'user_id' => auth()->user()->id,
            'comment' => $request->comment,
            'rating' => $request->rate,
            'instructor_id' => $instructor,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Review Will Approve By Admin',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function AdminPendingReview(){
        $review = Review::where('status',0)->orderBy('id','DESC')->get();
        return view('admin.backend.review.pending_review',compact('review'));
    }

    public function UpdateReviewStatus(Request $request){

        $reviewId = $request->input('review_id');
        $isChecked = $request->input('is_checked',0);

        $review = Review::find($reviewId);
        if ($review) {
            $review->status = $isChecked;
            $review->save();
        }

        return response()->json(['message' => 'Review Status Updated Successfully']);

    }

    public function AdminActiveReview(){

        $review = Review::where('status',1)->orderBy('id','DESC')->get();
        return view('admin.backend.review.active_review',compact('review'));

    }

    public function InstructorAllReview(){
        $id = auth()->user()->id;
        $review = Review::where('instructor_id',$id)->where('status',1)->orderBy('id','DESC')->get();
        return view('instructor.review.active_review',compact('review'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        //
    }
}
