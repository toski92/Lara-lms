<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        if (Auth::user()->role==='user') {
            return view('frontend.dashboard.edit_profile', [
                'user' => $request->user(),
            ]);
        }
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $id = Auth()->user()->id;
        $data = User::find($id);//dd($data);
        $data->name = $request->name;
        $data->username = $request->username;
        // $data->email = $request->email;
        $data->photo = $request->photo;
        $data->phone = $request->phone;
        $data->address = $request->address;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            $filename = date('YmdHi').$file->getClientOriginalName();//dd($filename);
            $file->move(public_path('upload'),$filename);
            $data['photo'] = $filename;
        }

        $data->save();

        $notification = [
            'message' => 'Profile updated successfully',
            'alert-type' => 'success'
        ];

        return redirect('/profile')->with($notification);
    }

    public function update_password(Request $request) {
        // Validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);
        $check_old_password_match = Hash::check($request->old_password, auth::user()->password);
        if (!$check_old_password_match) {
            $notification = [
                'message' => 'Old password do not match',
                'alert-type' => 'error'
            ];

            return back()->with($notification);
        }

        // update new password
        User::whereId(auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);
        $notification = [
            'message' => 'Password changed successfully',
            'alert-type' => 'success'
        ];

        return back()->with($notification);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
