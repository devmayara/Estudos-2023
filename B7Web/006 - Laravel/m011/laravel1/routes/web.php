<?php

use App\Http\Controllers\TarefasController;
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

Route::prefix('/tarefas')->group(function(){
    Route::get('/', [TarefasController::class, 'list']); // Listagem de tarefas

    Route::get('add', [TarefasController::class, 'add']); // Tela de adição
    Route::post('add', [TarefasController::class, 'addAction']); // Ação de adição

    Route::get('edit/{id}', [TarefasController::class, 'edit']); // Tela de edição
    Route::post('edit/{id}', [TarefasController::class, 'editAction']); // Ação de edição

    Route::get('delete/{id}', [TarefasController::class, 'del']); // Ação de deletar

    Route::get('marcar/{id}', [TarefasController::class, 'done']); // Marcar resolvido/nao.
});

// Route::fallback(function () {
//     return view('404');
// });
