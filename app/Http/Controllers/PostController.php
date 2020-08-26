<?php

namespace App\Http\Controllers;

use App\Post;
use App\Category;
use App\Tag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        if ($buscar == '') {
         $posts = Post::join('categories','posts.category_id','=','categories.id')
        ->select('categories.name as catname', 'posts.id', 'posts.file', 'posts.name as postname',
        'posts.body','posts.excerpt', 'posts.category_id', 'categories.slug')   
        ->where([['status', 'PUBLISHED'],['user_id', auth()->user()->id]])
        ->paginate(18);
        return view('posts.index', compact('posts'));
        }else{
             $posts = Post::join('categories','posts.category_id','=','categories.id')
            ->select('categories.name as catname', 'posts.id', 'posts.file', 'posts.name as postname',
            'posts.body','posts.excerpt', 'posts.category_id', 'categories.slug')   
            ->where([['status', 'PUBLISHED'], ['posts.name','like', '%' . $buscar . '%'],['user_id', auth()->user()->id]])
            ->paginate(18);
            return view('posts.index', compact('posts'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {//category+tag
        $categories = Category::orderBy('name', 'ASC')->pluck('name', 'id');
        $tags       = Tag::orderBy('name', 'ASC')->get();
        return view('posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = Post::create($request->all());
        //
        $fileName = "";

        if($request->file != ""){

            $exploded = explode(',', $request->file);

            $decoded = base64_decode($exploded[1]);

            if(str_contains($exploded[0],'jpeg'))
                $extension = 'jpg';
            else
                $extension = 'png';

            $fileName = time().'.'.$extension;
            //The name of the directory that we need to create.
            $directoryName = 'images';

            //Check if the directory already exists.
            if(!is_dir($directoryName)){
                //Directory does not exist, so lets create it.
                mkdir($directoryName, 0777);
            }

            $path = public_path($directoryName).'/'.$fileName;

            file_put_contents($path,$decoded);
        }

        //IMAGE 
       /* if($request->file('image')){
            $path = Storage::disk('public')->put('image',  $request->file('file'));
            $post->fill(['file' => asset($path)])->save();
        }
        //TAGS
        $post->tags()->attach($request->get('tags'));
        return redirect()->route('posts.edit', $post->id)->with('info', 'Entrada creada con éxito');*/
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::join('categories','posts.category_id','=','categories.id')
        ->orderBy('posts.name', 'ASC')
        ->select('categories.name as catname', 'posts.id', 'posts.file', 'posts.name',
        'posts.body','posts.excerpt', 'posts.category_id', 'categories.slug')
        ->where('posts.id', $id)->first();
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::orderBy('name', 'ASC')->pluck('name', 'id');
        $tags       = Tag::orderBy('name', 'ASC')->get();
        $post       = Post::find($id);
        return view('posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        $post->fill($request->all())->save();
        //IMAGE 
        if($request->file('image')){
            $path = Storage::disk('public')->put('image',  $request->file('file'));
            $post->fill(['file' => asset($path)])->save();
        }
        //TAGS
        $post->tags()->sync($request->get('tags'));
        return redirect()->route('posts.edit', $post->id)->with('info', 'Entrada actualizada con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id)->delete();
        return back()->with('info', 'Eliminado correctamente');
    }
}
