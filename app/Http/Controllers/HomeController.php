<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Category;
use App\Models\Group;
use App\Models\Phrase;
use App\Models\PhraseCategory;
use App\Models\UserGroup;
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
            if(!isset($category_exists) && isset($posts['new_category']) ){
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
        ->get();
        if(!isset($phrase_checked)){
            return view('quiz_checked');
        }else{
            return redirect('quiz_all');
        }
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

}
