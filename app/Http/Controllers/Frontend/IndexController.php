<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseMeta;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id,$slug)
    {
        $course = Course::find($id);
        $metas = CourseMeta::where('course_id',$id)->orderBy('id','DESC')->get();
        $instructor_id = $course->instructor_id;
        $instructor_courses = Course::where('instructor_id',$instructor_id)->orderBy('id','DESC')->get();
        $categories = Category::all();

        $cat_id = $course->category_id;
        $related_courses = Course::where('category_id',$cat_id)->where('id','!=',$id)->orderBy('id','DESC')->limit(3)->get();

        return view('frontend.courses.course',['course'=>$course,'metas'=>$metas,'instructor_courses'=>$instructor_courses,'categories'=>$categories,'related_courses'=>$related_courses]);
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
