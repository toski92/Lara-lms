<?php

namespace App\Http\Controllers;

use App\Mail\MyMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('frontend.index');
    }
    public function register_teacher()
    {
        $user = Auth::user();//dd($user);
        if ($user->role !=='instructor') {
            Mail::to('otochukwu2@gmail.com')->send(new MyMail());

            $notification = array(
                'message' => 'Your Application is successfull',
                'alert-type' => 'success'
            );
            // return redirect()->route('all.subcategory')->with($notification);
            return redirect()->back()->with($notification);
        }

        $notification = array(
            'message' => 'You are an instructor already',
            'alert-type' => 'error'
        );
        // return redirect()->route('all.subcategory')->with($notification);
        return redirect()->back()->with($notification);
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
