<?php

namespace App\Http\Controllers\Auth;
use \Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    // function tambahan 
    public function authenticated(Request $request, $user)
    {
        if ($user->hasRole('admin')) {
            return redirect('halamanutama');
            // return redirect()->route('admin.page');
        }elseif($user->hasRole('kepaladivisi')){
            // return redirect('halamanutama');
            return redirect()->route('kepaladivisi.page');
        }elseif($user->hasRole('kepalabpfk')){
            // return redirect('halamanutama');
            return redirect()->route('kepalabpfk.page');
        }
        return redirect()->route('user.page');
    }
    //end function tambahan 
}
