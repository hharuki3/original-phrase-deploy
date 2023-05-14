<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Memo;
use App\Models\Category;
use App\Models\Group;
use App\Models\Phrase;
use App\Models\UserGroup;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //https通信を強制
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        //
        view()->composer('*', function($view){

            $query_category = \Request::query('category');
            // dd($query_category);
            if(!empty($query_category)){
                $phrases = Phrase::select('phrases.*')
                    ->leftJoin('phrase_categories', 'phrase_categories.phrase_id', '=', 'phrases.id')
                    ->where('phrase_categories.category_id', '=', $query_category)
                    ->whereNull('deleted_at')
                    ->where('user_id', '=', \Auth::id())
                    ->orderBy('updated_at', 'DESC')
                    ->get();
            }else{
                $phrases = Phrase::select('phrases.*')
                    ->whereNull('deleted_at')
                    // ->whereNull('checklist')
                    ->where('user_id', '=', \Auth::id())
                    ->orderBy('updated_at', 'DESC')
                    ->get();
            }
            $randoms = range(0,count($phrases)-1);
            shuffle($randoms);
            
            $categories = Category::where('user_id', '=', \Auth::id())
                ->whereNull('deleted_at')
                ->orderBy('id', 'DESC')
                ->get();
            


            $phrase_exists = Phrase::where('user_id', '=', \Auth::id())
                ->whereNull('deleted_at')
                ->exists();
            
            $phrase_checked = Phrase::select('phrases.*')
            ->whereNull('deleted_at')
            ->whereNotNull('checklist')
            ->where('user_id', '=', \Auth::id())
            ->orderBy('updated_at', 'DESC')
            ->get();

            $randoms_checked =range(0,count($phrase_checked)-1);
            shuffle($randoms_checked);

            //ログインユーザーが1つ以上グループに所属しているかどうか確認
            $group_exists = UserGroup::where('user_id', '=', \Auth::id())
                ->exists();

            // ログインユーザーが所属しているグループ（1以上）の値を取得。
            $groups = Group::select('groups.*')
                ->leftJoin('user_groups', 'user_groups.group_id', '=', 'groups.id')
                ->where('user_groups.user_id', '=', \Auth::id())
                ->whereNull('deleted_at')
                ->get();
            
            
            $query_group = \Request::query('group');

            // 選択したグループに所属しているユーザーを全て取得
            if(!empty($query_group)){
                //$usersにログインユーザーが含まれていない場合の処理を記述（URLから変更できてしまうため）

                $users = User::select('users.*')
                    ->leftJoin('user_groups', 'user_groups.user_id', '=', 'users.id')
                    ->where('user_groups.group_id', '=', $query_group)
                    ->where('id', '!=', \Auth::id())
                    ->orderBy('updated_at', 'ASC')
                    ->get();
                

            }else{
                $users = User::select('users.*')
                    // ->whereNull('checklist')
                    ->where('id', '=', \Auth::id())
                    ->orderBy('updated_at', 'DESC')
                    ->get();
            }
            $login_users = User::select('users.*')
                ->where('id', '=', \Auth::id())
                ->get();




                
                
            $query_user = \Request::query('user');


            $group_phrase_exists = Phrase::where('user_id', '=', $query_user)
            ->whereNull('deleted_at')
            ->exists();

            // 選択したグループに所属している特定のユーザーのフレーズを取得
            //ユーザーが投稿していない場合の処理が必要
            if(!empty($query_user)){
                if($group_phrase_exists){
                    $group_user_phrases = Phrase::select('phrases.*')
                        ->where('user_id', '=', $query_user)
                        ->whereNull('deleted_at')
                        ->orderBy('updated_at', 'ASC')
                        ->get();
                }else{
                    // 「投稿しているフレーズなし」の処理
                    $group_user_phrases = [];
                }
                
            }else{
                // 所属グループページにいるが、参加ユーザーを選択していない場合の処理
                $group_user_phrases = [];
            }

            $query_invite = \Request::query('token');

            
            $view->with('phrases', $phrases)
                ->with('categories', $categories)
                ->with('phrase_exists', $phrase_exists)
                ->with('randoms', $randoms)
                ->with('phrase_checked', $phrase_checked)
                ->with('randoms_checked', $randoms_checked)
                ->with('group_exists', $group_exists)
                ->with('groups', $groups)
                ->with('query_group', $query_group)
                ->with('users', $users)
                ->with('login_users', $login_users)
                ->with('query_user', $query_user)
                ->with('group_user_phrases', $group_user_phrases)
                ->with('query_invite', $query_invite);

        });
    }
}
