<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Models\UserGroup;
use App\Models\Invite;

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

    public function username(){
        return 'name';
    }


    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        // ユーザーIDをセッションに格納
        //inviteテーブルのtokenの値と$urlを参照
        //tokenが一致するinviteテーブルのgroup_idを追加

        $url = $request->header('referer');
        $parts = parse_url($url);
        $query = [];
        if (isset($parts['query'])) {
            parse_str($parts['query'], $query);
        }
        $token = isset($query['token']) ? $query['token'] : null;
        

        if(!empty($token)){
            $group = Invite::where('token', $token)->first();
            if ($group) {
                $group_id = $group->group_id;
                UserGroup::insert(['user_id' => $user['id'], 'group_id' => $group_id]);
            }
        }

        session(['user_id' => $user->id]);
        
        // ホーム画面にリダイレクト
        return redirect('/home');
    }
    
}
