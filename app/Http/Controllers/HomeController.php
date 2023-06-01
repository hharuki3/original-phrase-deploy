<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Models\Category;
use App\Models\Group;
use App\Models\UserGroup;
use App\Models\Phrase;
use App\Models\PhraseCategory;
use App\Models\Invite;
use App\Mail\Invitation;

//Inviteを追加

use DB;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function create()
    {
        return view('create');
    }


    public function store(Request $request)
    {
        $posts = $request->all();
        // dd($posts);

        //バリデーション
        $request->validate([
            'japanese' => 'required',
            'phrase' => 'required',
            'memo' => 'required',
            'new_category' => 'unique:categories,name',
        ]);

        DB::transaction(function() use($posts){
            $phrase_id = Phrase::insertGetId(['japanese' => $posts['japanese'], 'phrase' => $posts['phrase'], 
            'memo' => $posts['memo'], 'user_id' => \Auth::id()]);
            $category_exists = Category::where('user_id', '=', \Auth::id())
                ->where('name', '=', $posts['new_category'])
                ->whereNull('deleted_at')
                ->exists();
            //emptyは0も空扱いになるため、厳密に書くのであれば「|| $posts['new_category']===0」も追加
            if(!empty($posts['new_category'] && !$category_exists)){
                $category_id = Category::insertGetId(['name' => $posts['new_category'], 'user_id' => \Auth::id()]);
                PhraseCategory::insert(['phrase_id' => $phrase_id, 'category_id' => $category_id]);
            }
            if(!empty($posts['categories'][0])){
                foreach($posts['categories'] as $category){
                    PhraseCategory::insert(['phrase_id' => $phrase_id, 'category_id' => $category]);
                }
            }
        });

        return redirect(route('home'));
    }


    public function edit($id)
    {
        // dd($id);

        $edit_phrase = Phrase::select('phrases.*', 'categories.id as category_id')
            ->leftJoin('phrase_categories', 'phrase_categories.phrase_id', '=', 'phrases.id')
            ->leftJoin('categories', 'phrase_categories.category_id', '=', 'categories.id')
            ->where('phrases.user_id', '=', \Auth::id())
            ->where('phrases.id', '=', $id)
            ->whereNull('phrases.deleted_at')
            ->get(); 
            // dd($edit_phrase);
        $include_categories = [];
        foreach($edit_phrase as $phrase){
            array_push($include_categories, $phrase['category_id']);
        }
        
        return view('edit', compact('edit_phrase', 'include_categories'));
    }


    public function update(Request $request)
    {
        $posts = $request->all();
        // dd($posts);
        $request->validate([
            'japanese' => 'required',
            'phrase' => 'required',
            'memo' => 'required',
            'new_category' => 'unique:categories,name',
        ]);

        DB::transaction(function() use($posts){
            Phrase::where('id', '=', $posts['phrase_id'])
                ->update(['japanese' => $posts['japanese'], 'phrase' => $posts['phrase'], 'memo' => $posts['memo']]);
            PhraseCategory::where('phrase_id', '=', $posts['phrase_id'])->delete();

            if(isset($posts['categories'])){
                foreach($posts['categories'] as $category){
                    PhraseCategory::insert(['phrase_id' => $posts['phrase_id'], 'category_id' => $category]);
                }
            }

            if(isset($posts['new_category'])){
                $category_exists = Category::where('user_id', '=', \Auth::id())
                    ->where('name', '=', $posts['new_category'])
                    ->whereNull('deleted_at')
                    ->exists();
            }
                // dd($category_exists);
            //emptyは0も空扱いになるため、厳密に書くのであれば「|| $posts['new_category']===0」も追加
            if(isset($category_exists) && isset($posts['new_category']) ){
                $category_id = Category::insertGetId(['name' => $posts['new_category'], 'user_id' => \Auth::id()]);
                PhraseCategory::insert(['phrase_id' => $posts['phrase_id'], 'category_id' => $category_id]);
            }



        });
        return redirect(route('home'));
    }

    public function update_checklist(Request $request)
    {
        $posts = $request->all();
        // dd($posts);
        DB::transaction(function() use($posts){
            Phrase::where('id', '=', $posts['phrase_id'])
                ->update(['japanese' => $posts['japanese'], 'phrase' => $posts['phrase'], 
                'memo' => $posts['memo'], 'checklist' => $posts['checklist']]);
            PhraseCategory::where('phrase_id', '=', $posts['phrase_id'])->delete();

        });


        $redirectUrl = session()->get('redirect_url',route('quiz_all'));
        return redirect($redirectUrl);



    }


    public function detail($id)
    {
        $edit_phrase = Phrase::select('phrases.*', 'categories.id as category_id')
            ->leftJoin('phrase_categories', 'phrase_categories.phrase_id', '=', 'phrases.id')
            ->leftJoin('categories', 'phrase_categories.category_id', '=', 'categories.id')
            ->where('phrases.user_id', '=', \Auth::id())
            ->where('phrases.id', '=', $id)
            ->whereNull('phrases.deleted_at')
            ->get();

            $include_categories = [];
            foreach($edit_phrase as $phrase){
                array_push($include_categories, $phrase['category_id']);
            }
            
        return view('detail', compact('edit_phrase', 'include_categories'));
    }

    public function destroy(Request $request)
    {

        $posts = $request->all();
        // dd($posts);
        Phrase::where('id', $posts['phrase_id'])->update(['deleted_at' => date("Y-m-d H:i:s", time())]);
        return redirect(route('home'));
    }
    
    public function category_destroy(Request $request)
    {
        $posts = $request->all();
        // dd($posts);
        // Category::where('id', '=', $posts['category_id'])->update(['deleted_at' => date("Y-m-d H:i:s", time())]);
        Category::where('id', $posts['category_id'])->update(['deleted_at' => date("Y-m-d H:i:s", time())]);
        return back();
    }

    public function group_destroy(Request $request)
    {
        $posts = $request->all();
        UserGroup::where('user_id', $posts['login_user_id'])
            ->where('group_id', $posts['query_group'])
            ->delete();
        return view('group');
    }

    public function quiz_all()
    {
            // dd($edit_phrase);
        return view('quiz_all');
    }

    public function quiz_checked()
    {
        $phrase_checked = Phrase::select('phrases.*')
        ->whereNull('deleted_at')
        ->whereNotNull('checklist')
        ->where('user_id', '=', \Auth::id())
        ->orderBy('updated_at', 'DESC')
        ->exists();
        if($phrase_checked){
            return view('quiz_checked');
        }else{
            return redirect('quiz_all');
        }

    }


    public function quiz_category()
    {
        return view('quiz_category');
    }

    public function result(Request $request)
    {
        $posts = $request->all();
        // dd($posts);
        return redirect(route('quiz_all'));
    }

    public function group()
    {
        return view('group');
    }
    public function category()
    {
        return view('category');
    }



    public function invite($id)
    {
        //$idはクエリパラメータのIDとgroupsテーブルのIDが一致したgroup_idを指す。
        $group_id = UserGroup::select('user_groups.group_id')
            ->where('group_id', '=', $id)
            ->where('user_id', '=', \Auth::id())
            ->get();

        return view('invite', compact('group_id'));
    }

    public function new_invite(Request $request)
    {
        $posts = $request->all();

        $request->validate([
            'new_group' => 'required'
        ]);
        $group_id = [];
        $new_group_id = Group::insertGetId(['name' => $posts['new_group']]);
        UserGroup::insert(['user_id' => \Auth::id(), 'group_id' => $new_group_id]);

        return view('invite', compact('group_id'));

    }

    public function invitation_confirm(Request $request)
    {
        //??get通信になっているらしい。どこで？
        $posts = $request->all();
        dd($posts);
        $request->validate([
            'email' => 'required|email:filter,dns'
        ]);

        // $recipientName メール受信者の名前 / $recipientEmail メール受信者のメールアドレス / fromName メール送信者の名前
        $recipientName = User::where('email', $posts['email'])->first()->name;
        $recipientEmail = User::where('email', $posts['email'])->first()->email;
        $fromName = auth()->user()->name;

        if(isset($posts['group_id'])){
            //既存グループ招待処理
            $group_id = $posts['group_id'];
        }else{
            //新規グループへの招待処理
            $group_id = Group::select('groups.id')
                ->orderBy('updated_at', 'DESC')
                ->first();

            $latest_group = Group::orderBy('updated_at', 'DESC')->first();
            if ($latest_group) {
                $group_id = $latest_group->id;
            }
        }

        $recipientEmail_exist = User::where('email', '=', $posts['email'])
        ->exists();
        $token = bin2hex(random_bytes(32)); // ランダムなトークンの生成
        // $url = 'http://localhost:8888/login?token=' . $token; // 招待URLの作成

        //heroku用URL
        $url = 'https://original-phrase-heroku4.herokuapp.com/login?token=' . $token; // 招待URLの作成

        // inviteテーブルに追加
        Invite::insert(['group_id' => $group_id, 'token' => $token]);
        Mail::to($recipientEmail)->send(new Invitation($recipientName, $url));

        // コマンドプロンプトでmailhogを実行しないとエラーになる。
        //invitation.php経由のinvitation_confirm.phpへのアクセスとreturn view経由でのアクセスでどちらも変数を指定する必要がある。
        // return view('invitation_confirm', compact('recipientName', 'url', 'fromName'));
        return redirect('/group')->with('success', 'メールを送信しました。');
        

    }



    public function InvitedForm(string $token)
    {
        $invite = Invite::where('token', $token)->first();

        if (!isset($invite)) {
            abort(401);
        }

        return view('invite_register', [
            'token' => $invite->token,
            'family_id' => $invite->family_id,
            'email' => $invite->email,
        ]);
    }

    public function join(Request $request){
        $posts = $request->all();
        dd($posts);
        
    }












}
