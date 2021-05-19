<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function posts()
    {
        return view('posts', [
            'posts' => Post::with('user')->latest()->paginate(),
        ]);
        /**
         * La paginacion (paginate()) nos permite paginar los blogs
         * y poder utilizar en el blade la funcion links() que
         * creara los link de paginacion.
         */
    }
    public function post(Post $post)
    {
        return view('post', ['post' => $post]);
    }
}
