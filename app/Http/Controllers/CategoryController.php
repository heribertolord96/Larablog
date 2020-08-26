<?php

namespace App\Http\Controllers;
use App\Post;
use App\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
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
        $categories = Category::orderBy('id', 'DESC')->paginate();
        return view('categories.index', compact('categories'));
    }

    public function category(Request $request ,$id){
        $buscar = $request->buscar;
        if ($buscar == '') {
        $posts = Post::join('categories','posts.category_id','=','categories.id')
        ->orderBy('posts.name', 'ASC')
        ->select('categories.name as catname', 'posts.id', 'posts.file', 'posts.name as postname',
        'posts.body','posts.excerpt', 'posts.category_id', 'categories.slug')       
        ->where([['status', 'PUBLISHED'],['category_id', $id]])->paginate(18);
        return view('blog', compact('posts'));
        }else{
            $posts = Post::join('categories','posts.category_id','=','categories.id')
        ->orderBy('posts.name', 'ASC')
        ->select('categories.name as catname', 'posts.id', 'posts.file', 'posts.name as postname',
        'posts.body','posts.excerpt', 'posts.category_id', 'categories.slug')        
        ->where([['status', 'PUBLISHED'],['category_id', $id],['posts.name','like', '%' . $buscar . '%']])
        ->paginate(18);
        return view('blog', compact('posts'));
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
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
            'name' => 'required|max:128',
            'body' => 'required',
        ]);
        $category = Category::create($request->all());
        return redirect()->route('categories.edit', $category->id)
        ->with('info', 'Categoría creada con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([           
            'name' => 'required|max:128',
            'body' => 'required',
        ]);
        try{
            DB::beginTransaction();
        $category = Category::FindOrFail($id);
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->body =   $request->body;
        $category->save();
           DB::commit();
        return redirect()->route('categories.edit', $category->id)
        ->with('info', 'Categoría actualizada con éxito');
    }catch(Exception $e){
        DB::rollBack();
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id)->delete();
        return back()
        ->with('info', 'Eliminado correctamente');
    }
}
