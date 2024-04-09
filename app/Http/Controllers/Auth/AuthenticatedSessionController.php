<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserSubscribed;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('frontend.dashboard.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        if ($request->has('newsletter') && auth()->check()) {
            $validate=$request->validate([
                'email'=>'unique:newsletter,email'
            ]);
            if ($validate) {
                event(new UserSubscribed($request->email));
            }
        }

        $request->session()->regenerate();
        $notification = array(
            'message' => 'Login Successfully',
            'alert-type' => 'success'
        );
        $url = '';
        if ($request->user()->role==='admin') {
            $url = route('admin.dashboard');
        }elseif ($request->user()->role==='instructor') {
            $url= route('instructor.dashboard');
        }elseif ($request->user()->role==='user') {
            $url= route('dashboard');
        }

        return redirect()->intended($url)->with($notification);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
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
}
