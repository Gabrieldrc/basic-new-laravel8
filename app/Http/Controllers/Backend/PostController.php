<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::latest()->get();
        /**
         * compact es una funcion de php que retorna un aray
         * Si te fijas en el pageController, alli le pasamos
         * array con key post y valor los posts, en este caso
         * funciona igual porque el key se llamara igual que
         * la variable.
         */
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        // dd($request->all());
        //salvar
        $post = new Post();
        $post->fill([
            'user_id' => auth()->user()->id,
        ] + $request->validated());
        //image
        if ($request->file('file')) {
            /**
             * Si recibimos un archivo, lo guardamos en el proyecto
             * para crear una ruta que es la que guardaremos en la
             * base de datos. (nunca guardamos en la db un archivo
             * directamente)
             * esto se guarda en la carpeta storage/app/public/posts
             * NOTA:
             *  Nunca es buena idea guardar archivos subidos por los
             *  usuarios dentro del servidor que ejecuta PHP ya que
             *  esto puede prestarse para fallas de seguridad que un
             *  atacante puede explotar, siempre es mejor almacenar
             *  dichos archivos en sistemas como S3, Azure Storage,
             *  entre otros
             */
            $post->image = $request->file('file')->store('posts', 'public');
        }
        $post->save();
        //retornar
        /**
         * Le mandamos un status porque si te fijas en
         * el create.blade.php hay un if que plantea
         * si hay un status, imprimelo
         */
        return back()->with('status', 'Creado con exito');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        // dd($request->all());
        $image = [];
        if ($request->file('file')) {
            //eliminar imagen
            Storage::disk('public')->delete($post->image);
            $imageSrc = $request->file('file')->store('posts', 'public');
            $image['image'] = $imageSrc;
        }
        $post->update($request->all()+$image);


        return back()->with('status', 'Actualizado con exito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //eliminar imagen
        Storage::disk('public')->delete($post->image);
        //Eliminando el post
        $post->delete();

        return back()->with('status', 'Eliminado con exito');
    }
}
