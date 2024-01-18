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
use App\Models\Lecture;
use App\Models\Topic;
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
    public function edit(Course $course, Category $category, SubCategory $subCategory, CourseMeta $courseMeta, string $id)
    {
        $categories = $category::all();
        $subcategories = $subCategory::all();
        $metas = $courseMeta::where('course_id',$id)->get();
        return view('admin.backend.courses.edit_course',['course'=>$course::find($id),'categories'=>$categories,'subcategories'=>$subcategories,'metas'=>$metas]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $course_id = $request->course_id;

        $course::find($course_id)->update([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'instructor_id' => Auth::user()->id,
            'course_title' => $request->course_title,
            'course_name' => $request->course_name,
            'slug' => strtolower(str_replace(' ', '-', $request->course_name)),
            'description' => $request->description,

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

        ]);

        $notification = array(
            'message' => 'Course Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.course')->with($notification);
    }

    public function UpdateFeatureImage(Request $request) {
        $course_id = $request->id;
        $oldImage = $request->old_img;

        $image = $request->file('feature_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(370,246)->save('upload/course/thumbnail/'.$name_gen);
        $save_url = 'upload/course/thumbnail/'.$name_gen;

        if (file_exists($oldImage)) {
            unlink($oldImage);
        }

        Course::find($course_id)->update([
            'feature_image' => $save_url,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Course Image Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function UpdateCourseVideo(Request $request){

        $course_id = $request->vid;
        $oldVideo = $request->old_vid;

        $video = $request->file('video');
        $videoName = time().'.'.$video->getClientOriginalExtension();
        $video->move(public_path('upload/course/video/'),$videoName);
        $save_video = 'upload/course/video/'.$videoName;

        if (file_exists($oldVideo)) {
            unlink($oldVideo);
        }

        Course::find($course_id)->update([
            'video' => $save_video,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Course Video Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    }

    public function UpdateCourseMeta(Request $request){

        $cid = $request->id;//dd($cid);

        if ($request->course_metas == NULL) {
            return redirect()->back();
        } else{

            CourseMeta::where('course_id',$cid)->delete();

            $metas = Count($request->course_metas);

                for ($i=0; $i < $metas; $i++) {
                    $gcount = new CourseMeta();
                    $gcount->course_id = $cid;
                    $gcount->meta_name = $request->course_metas[$i];
                    $gcount->save();
                }  // end for
        } // end else

        $notification = array(
            'message' => 'Course Metas Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, CourseMeta $courseMeta, string $id)
    {
        $get_course = $course::find($id);
        unlink($get_course->feature_image);
        unlink($get_course->video);

        $course::find($id)->delete();

        $metaData = $courseMeta::where('course_id',$id)->get();
        foreach ($metaData as $item) {
            $item->meta_name;
            $courseMeta::where('course_id',$id)->delete();
        }

        $notification = array(
            'message' => 'Course Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function add_lecture(string $id){

        $course = Course::find($id);
        $topics = Topic::where('course_id',$id)->get();

        return view('admin.backend.courses.add_lecture',['course'=>$course,'topics'=>$topics]);

    }

    public function add_topic(Request $request){

        $cid = $request->id;

        Topic::insert([
            'course_id' => $cid,
            'topic_title' => $request->topic_title,
            'topic_summary' => $request->topic_summary,
        ]);

        $notification = array(
            'message' => 'Topic Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    }

    public function save_lecture(Request $request, Lecture $lecture) {
        // $lecture = new Lecture();
        $lecture->course_id = $request->course_id;
        $lecture->topic_id = $request->topic_id;
        $lecture->lecture_title = $request->lecture_title;
        $lecture->url = $request->lecture_url;
        $lecture->content = $request->content;
        $lecture->save();

        return response()->json(['success' => 'Lecture Saved Successfully']);

    }

    public function get_lecture($id) {
        $lecture = Lecture::find($id);

        return response()->json($lecture);
    }

    public function update_lecture(Request $request) {
        $lectureId = $request->lecture_id;
        Lecture::find($lectureId)->update([
            'lecture_title' => $request->lecture_title,
            'url' => $request->url,
            'content' => $request->content,
        ]);
        $notification = array(
            'message' => 'Lecture Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
}
