<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use App\Models\CourseMeta;
use Carbon\Carbon;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instructor_id = Auth::user()->id;
        $courses = Course::where('instructor_id',$instructor_id)->orderBy('id','desc')->get();
        return view('admin.backend.courses.course',['courses'=>$courses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.backend.courses.add_course',['categories'=>$categories]);
    }

    public function GetSubCategory($category_id) {
        $subcategory = SubCategory::where('category_id',$category_id)->orderBy('subcategory_name','ASC')->get();
        return json_encode($subcategory);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request);
        $request->validate([
            'video' => 'required|mimes:mp4|max:10000',
        ]);//dd($request->all());

        $image = $request->file('feature_image');//dd($image);
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(370,246)->save('upload/course/thumbnail/'.$name_gen);
        $save_url = 'upload/course/thumbnail/'.$name_gen;

        $video = $request->file('video');
        $videoName = time().'.'.$video->getClientOriginalExtension();
        $video->move(public_path('upload/course/video/'),$videoName);
        $save_video = 'upload/course/video/'.$videoName;//dd($save_video);

        $course_id = Course::insertGetId([

            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'instructor_id' => Auth::user()->id,
            'course_title' => $request->course_title,
            'course_name' => $request->course_name,
            'slug' => strtolower(str_replace(' ', '-', $request->course_name)),
            'description' => $request->description,
            'video' => $save_video,

            'level' => $request->level,
            'duration' => $request->duration,
            'resources' => $request->resources,
            'certificate' => $request->certificate,
            'selling_price' => $request->selling_price,
            'discount_price' => $request->discount_price,
            'excerpt' => $request->excerpt,

            'bestseller' => $request->bestseller,
            'featured' => $request->featured,
            'highestrated' => $request->highestrated,
            'status' => 1,
            'feature_image' => $save_url,
            'created_at' => Carbon::now(),

        ]);//dd($course_id);

        /// Course Metas Add Form

        $metas = Count($request->course_metas);
        if ($metas != NULL) {
            for ($i=0; $i < $metas; $i++) {
                $gcount = new CourseMeta();
                $gcount->course_id = $course_id;
                $gcount->meta_name = $request->course_metas[$i];
                $gcount->save();
            }
        }
        /// End Course Metas Add Form

        $notification = array(
            'message' => 'Course Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.course')->with($notification);

    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        // get prem/supp
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        //
    }
}
