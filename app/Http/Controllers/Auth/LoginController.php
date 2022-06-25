<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

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

    public function adminLogin()
    {
        return view('backend.admin.login');
    }

    /**
     * This loginPRocess belongs to admin login
     */
    public function loginProcess(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        $remember_me = $request->has('remember_me') ? true : false;

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember_me))
        {
            if(Auth::check() AND (Auth::user()->status == 'active'))
            {
                return redirect(route('user.dashboard'));
            }
            else
            {
                Auth::logout();
                $data['message'] = 'Record Not Found!, Please Try Again!';
                return redirect()->back()->with($data);
            }
        }
        else
        {
            $data['message'] = 'Invalid Credentials, Please Try Again!';
            return redirect()->back()->with($data);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        $remember_me = $request->has('remember_me') ? true : false;

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember_me))
        {
            if(Auth::check() AND (Auth::user()->status == 'active'))
            {
                /**
                 * the following query is used to
                 * check that user has subscribed the package or not
                 */
                /*
                    $subscriptions = User::with(['subscriptions' => function($q){
                        $q->where('status', 'active');
                    }])->where('role', 'customer')->where('id', Auth::user()->id)->get()->toArray();

                    if(!(count($subscriptions[0]['subscriptions']) > 0))
                    {
                        $data['message'] = "Please select your package plan!";
                        return redirect(route('front.membership'))->with($data);
                    }
                */

                return redirect(route('front.home'));
            }
            else
            {
                Auth::logout();
                $data['message'] = 'Record Not Found!, Please Try Again!';
                return redirect()->back()->with($data);
            }
        }
        else
        {
            $data['message'] = 'Invalid Credentials, Please Try Again!';
            return redirect()->back()->with($data);
        }
    }
}
