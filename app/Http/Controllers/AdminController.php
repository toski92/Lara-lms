<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

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
    public function AllAdmin(){
        $alladmin = User::where('role','admin')->get();
        return view('admin.backend.pages.admin.all_admin',compact('alladmin'));
    }
    public function AddAdmin(){
        $roles = Role::all();
        return view('admin.backend.pages.admin.add_admin',compact('roles'));
    }
    public function StoreAdmin(Request $request){

        $user = new User();
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->password = Hash::make($request->password);
        $user->role = 'admin';
        $user->status = '1';
        $user->save();

        if ($request->roles) {
            $permissions_role = collect($request->roles)->map(fn($val)=>(int)$val)->all();
            $user->assignRole($permissions_role); //insert permission role in model_has_roles table
        }

        $notification = array(
            'message' => 'New Admin Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.admin')->with($notification);

    }
    public function EditAdmin($id){

        $user = User::find($id);
        $roles = Role::all();
        return view('admin.backend.pages.admin.edit_admin',compact('user','roles'));

    }

    public function UpdateAdmin(Request $request,$id){

        $user = User::find($id);
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->role = 'admin';
        $user->status = '1';
        $user->save();

        $user->roles()->detach();
        if ($request->roles) {
            $permissions_role = collect($request->roles)->map(fn($val)=>(int)$val)->all();
            $user->assignRole($permissions_role);
        }

        $notification = array(
            'message' => 'Admin Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.admin')->with($notification);

    }
    public function DeleteAdmin($id){

        $user = User::find($id);
        if (!is_null($user)) {
            $user->delete();
        }

        $notification = array(
            'message' => 'Admin Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    }
}
