<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\Backend\PostController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/**
 * - Esta ruta base y blog/{post}
 * se les creo su controlador ademas de su modelo post
 * con migracion, factory y controlador.
 * - Ademas se realizo en el dataseed que se creara solo un usuario
 * con id 1 y 30 posts de ese usuario.
 * - Si notas que el usuario no se hizo con el factory es
 * para nosotros poder saber cual es el usuario que utilizaremos
 * para las pruebas. Y los post a todos le indicamos en el
 * factory que fuera con user_id 1.
 * - Se creo tambien las views respectivas con blade (posts y post)
 */
Route::get('/', [PageController::class, 'posts']);
Route::get('/blog/{post}', [PageController::class, 'post'])->name('post');

/**
 * - Tambien se crearon utilizando el ui bootstrap --auth
 * las paginas de register, login, etc.
 * Eso hizo que se crearan automaticamente
 * las siguientes dos lineas (auth y get /home)
 */

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::resource('posts', PostController::class)
    ->middleware('auth')
    ->except('show');
