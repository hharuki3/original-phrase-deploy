<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Memo;
use App\Models\Category;
use App\Models\Group;
use App\Models\Phrase;

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
            // dd($randoms);


            
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

            
            

            $view->with('phrases', $phrases)
                ->with('categories', $categories)
                ->with('phrase_exists', $phrase_exists)
                ->with('randoms', $randoms)
                ->with('phrase_checked', $phrase_checked)
                ->with('randoms_checked', $randoms_checked);

        });
    }
}
