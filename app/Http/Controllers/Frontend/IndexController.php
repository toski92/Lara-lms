<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use App\Models\CourseMeta;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Jobs\SendContactFormEmail;
use App\Http\Controllers\Controller;

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

    public function category($id, $slug){

        $courses = Course::where('category_id',$id)->where('status','1')->get();
        $category = Category::where('id',$id)->first();
        $categories = Category::all();
        return view('frontend.categories.category',compact('courses','category','categories'));
    }
    public function subcategory($id, $slug){

        $courses = Course::where('subcategory_id',$id)->where('status','1')->get();
        $subcategory = SubCategory::where('id',$id)->first();
        $categories = Category::all();
        return view('frontend.categories.subcategory',compact('courses','subcategory','categories'));
    }
    public function instructor($id){
        $instructor = User::find($id);
        $courses = Course::where('instructor_id',$id)->paginate(2);
        return view('frontend.instructor.instructor_details',compact('instructor','courses'));
    }

    public function contact(){
        return view('frontend.contact');
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
        $request->validate([
            'name'=>'required',
            'email'=>['required','email'],
            'message'=>'required',
        ]);

        // Dispatch the job to send email
        SendContactFormEmail::dispatch()->onQueue('emails')->delay(5);
        $notification = array(
            'message' => 'Your message has been sent!',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
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
