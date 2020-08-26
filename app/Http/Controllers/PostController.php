<?php

namespace App\Http\Controllers;

use App\Post;
use App\Category;
use App\Tag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $posts = Post::join('categories', 'posts.category_id', '=', 'categories.id')
                ->select(
                    'categories.name as catname',
                    'posts.id',
                    'posts.file',
                    'posts.name as postname',
                    'posts.body',
                    'posts.excerpt',
                    'posts.category_id',
                    'categories.slug'
                )
                ->where([['status', 'PUBLISHED'], ['user_id', auth()->user()->id]])
                ->paginate(18);
            return view('posts.index', compact('posts'));
        } else {
            $posts = Post::join('categories', 'posts.category_id', '=', 'categories.id')
                ->select(
                    'categories.name as catname',
                    'posts.id',
                    'posts.file',
                    'posts.name as postname',
                    'posts.body',
                    'posts.excerpt',
                    'posts.category_id',
                    'categories.slug'
                )
                ->where([['status', 'PUBLISHED'], ['posts.name', 'like', '%' . $buscar . '%'], ['user_id', auth()->user()->id]])
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
    { //category+tag
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
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:720',
            'status' => 'required',
            'name' => 'required|max:128',
            'body' => 'required',
        ]);

        $tags = $request->tags;
        try {
            DB::beginTransaction();
            $imageName = time() . '.' . $request->file->extension();
            $request->file->move(public_path('images'), $imageName);
            $post = new Post();
            $post->user_id = $request->user_id;
            $post->category_id = $request->category_id;
            $post->name = $request->name;
            $post->slug = $request->slug;
            $post->status = $request->status;
            $post->excerpt = $request->excerpt;
            $post->body = $request->body;
            $post->file =   $imageName;
            $post->save();
            $post->tags()->attach($tags);
            DB::commit();
            return redirect()->route('posts.edit', $post->id)->with('info', 'Entrada creada con éxito');
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::join('categories', 'posts.category_id', '=', 'categories.id')
            ->orderBy('posts.name', 'ASC')
            ->select(
                'categories.name as catname',
                'posts.id',
                'posts.file',
                'posts.name',
                'posts.body',
                'posts.excerpt',
                'posts.category_id',
                'categories.slug'
            )
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
        $request->validate([
            'status' => 'required',
            'name' => 'required|max:128',
            'body' => 'required',
        ]);
        try{
            DB::beginTransaction();

            $post = Post::FindOrFail($id);
            $img = $post->file;
            $imageName = $img;
            $directoryName = 'images';
            if ($img != null && $request->file){
                $image_path = public_path($directoryName).'/'.$img;
                if(file_exists($image_path)){
                    unlink($image_path);
                }
                $imageName = time().'.'.$request->file->extension();
                $request->file->move(public_path('images'), $imageName);
            }
            $post->user_id = $request->user_id;
            $post->category_id = $request->category_id;
            $post->name = $request->name;
            $post->slug = $request->slug;
            $post->status = $request->status;
            $post->excerpt = $request->excerpt;
            $post->body = $request->body;
            $post->file =   $imageName;
            $post->save();

            //TAGS
            $post->tags()->sync($request->get('tags'));

            DB::commit();
            return redirect()->route('posts.edit', $post->id)->with('info', 'Entrada actualizada con éxito');
        }catch(Exception $e){
            DB::rollBack();
        }
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
