<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\taskController;


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

Route::get('/', [taskController::class, 'Index']);
Route::post('/task-add', [TaskController::class, 'taskAdd'])->name('taskAdd');
Route::get('/show-task', [TaskController::class, 'showTask'])->name('showTask');
Route::delete('/remove-tasks/{id}', [TaskController::class, 'taskdestroy'])->name('taskdestroy');
Route::patch('/tasks/{id}/status', [TaskController::class, 'updateTask'])->name('updateTask');


