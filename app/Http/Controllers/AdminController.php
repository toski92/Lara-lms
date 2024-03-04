<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function admin_dashboard()
    {
        return view('admin.index');
    }

    public function user() {
        $users = User::all();
        return view('admin.backend.users.user',['users'=>$users]);
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
    public function edit(User $user, string $id)
    {
        $user_obj = $user::find($id);
        $all_user = $user::all();
        return view('admin.backend.users.edit-user',['user'=>$user_obj,'all_user'=>$all_user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;
        User::find($id)->update([
            'role'=>$request->role
        ]);

        $notification = array(
            'message' => 'User Role Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.users')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Logout Successfully',
            'alert-type' => 'info'
        );

        return redirect('/login')->with($notification);

    }
    public function all_courses() {
        $course = Course::all();
        return view('admin.backend.courses.all_course',compact('course'));
    }
    public function update_course_status(Request $request) {
        $courseId = $request->input('course_id');
        $isChecked = $request->input('is_checked',0);

        $course = Course::find($courseId);
        if ($course) {
            $course->status = $isChecked;
            $course->save();
        }

        return response()->json(['message' => 'Course Status Updated Successfully']);
    }
    public function admin_course_details($id) {
        $course = Course::find($id);
        return view('admin.backend.courses.course_details',compact('course'));
    }
}
