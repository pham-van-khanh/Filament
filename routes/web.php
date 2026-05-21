<?php

use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\ArchiveController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\CommentController;
use App\Http\Controllers\Frontend\FeaturedController;
use App\Http\Controllers\Frontend\GalleryController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\MemoryController;
use App\Http\Controllers\Frontend\MemoryPasswordController;
use App\Http\Controllers\Frontend\PrivateMessageController;
use App\Http\Controllers\Frontend\PreviewPostController;
use App\Http\Controllers\Frontend\ReactionController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\TagController;
use App\Http\Controllers\Frontend\TimelineController;
use App\Http\Controllers\Frontend\UnlistedMemoryController;
use App\Http\Controllers\Admin\MemoryEditorController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::redirect('/login', '/admin/login')->name('login');

Route::get('/memories', [MemoryController::class, 'index'])->name('memories.index');
Route::get('/memories/{post:slug}', [MemoryController::class, 'show'])->name('memories.show');
Route::post('/memories/{post:slug}/password', [MemoryPasswordController::class, 'store'])->name('memories.password');
Route::post('/memories/{post:slug}/comments', [CommentController::class, 'store'])->name('memories.comments.store');
Route::post('/memories/{post:slug}/reactions', [ReactionController::class, 'store'])->name('memories.reactions.store');
Route::post('/memories/{post:slug}/messages', [PrivateMessageController::class, 'store'])->name('memories.messages.store');

Route::get('/u/{token}', UnlistedMemoryController::class)->name('memories.unlisted');
Route::get('/preview/memories/{post}', PreviewPostController::class)
    ->middleware(['auth', 'signed'])
    ->name('memories.preview');

Route::get('/gallery', GalleryController::class)->name('gallery.index');
Route::get('/timeline', TimelineController::class)->name('timeline.index');
Route::view('/map', 'frontend.pages.map')->name('map.index');
Route::get('/categories/{category:slug}', CategoryController::class)->name('categories.show');
Route::get('/tags/{tag:slug}', TagController::class)->name('tags.show');
Route::get('/search', SearchController::class)->name('search');
Route::get('/about', AboutController::class)->name('about');
Route::get('/archive', ArchiveController::class)->name('archive');
Route::get('/featured', FeaturedController::class)->name('featured');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/memories/{post}/editor', [MemoryEditorController::class, 'edit'])->name('memories.editor');
    Route::put('/memories/{post}/editor', [MemoryEditorController::class, 'update'])->name('memories.editor.update');
});
