<?php

use App\Http\Controllers\ChatsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/news', function () {
    return Inertia::render('News');
})->middleware(['auth', 'verified'])->name('news');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/settings', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/settings', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/chats', [ChatsController::class, 'index'])->name('chats.index');
    Route::get('/chats/{chatId}/messages', [ChatsController::class, 'getMessages']);
    Route::post('/chats/{chatId}/messages', [ChatsController::class, 'sendMessage']);
    Route::post('/chats', [ChatsController::class, 'createChat']);
});


require __DIR__.'/auth.php';
