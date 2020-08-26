<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Tag;
use App\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function blog(Request $request){
        $buscar = $request->buscar;
        if ($buscar == '') {
            $posts = Post::join('categories','posts.category_id','=','categories.id')
            ->orderBy('posts.name', 'ASC')
            ->select('categories.name as catname', 'posts.id', 'posts.file', 'posts.name as postname',
            'posts.body','posts.excerpt', 'posts.category_id', 'categories.slug')        
            ->where('status', 'PUBLISHED')->paginate(18);
            return view('blog', compact('posts'));
        }
        else {
            $posts = Post::join('categories','posts.category_id','=','categories.id')
            ->orderBy('posts.name', 'ASC')
            ->select('categories.name as catname', 'posts.id', 'posts.file', 'posts.name as postname',
            'posts.body','posts.excerpt', 'posts.category_id', 'categories.slug')  
            ->where([['status', 'PUBLISHED'], ['postname','like', '%' . $buscar . '%']])->paginate(18);
            return view('blog', compact('posts'));
        }

        
    }

    public function category($slug){
        $category = Category::where('slug', $slug)->pluck('id')->first();
        $posts = Post::where('category_id', $category)
            ->orderBy('id', 'DESC')->where('status', 'PUBLISHED')->paginate(3);
        return view('blog', compact('posts'));
    }

    public function tag($slug){ 
        $posts = Post::whereHas('tags', function($query) use ($slug) {
            $query->where('slug', $slug);
        })
        ->orderBy('id', 'DESC')->where('status', 'PUBLISHED')->paginate(3);
        return view('blog', compact('posts'));
    }

    public function post($slug){
    	$post = Post::where('slug', $slug)->first();
    	return view('blog', compact('post'));
    }
}
