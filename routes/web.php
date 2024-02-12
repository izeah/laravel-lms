<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\RackController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// PUBLIC ROUTES
Route::name('public.')->middleware(['active', 'check.session'])->group(function () {
    // ROUTE FOR PUBLIC
    Route::get('', [PublicController::class, 'index'])->name('index');
    Route::get('aboutUs', [PublicController::class, 'aboutUs'])->name('aboutUs');
    Route::get('contact', [PublicController::class, 'contact'])->name('contact');
    Route::get('history', [PublicController::class, 'history'])->name('history')->middleware('auth');

    // ROUTE TO SEND FEEDBACK
    Route::post('sendFeedback', [PublicController::class, 'sendFeedback'])->name('sendFeedback')->middleware('auth');

    // ROUTE FOR PUBLIC - BOOKS
    Route::get('books', [PublicController::class, 'books'])->name('books');
    Route::get('books/{id}/detail', [PublicController::class, 'bookDetail'])->name('bookDetail');

    // ROUTE FOR PUBLIC - EBOOKS
    Route::get('ebooks', [PublicController::class, 'ebooks'])->name('ebooks')->middleware('auth');
    Route::get('ebooks/{id}/detail', [PublicController::class, 'ebookDetail'])->name('ebookDetail')->middleware('auth');
    Route::get('ebooks/{id}', [PublicController::class, 'ebookRead'])->name('ebookRead')->middleware('auth');

    // ROUTE FOR SEARCH BOOKS
    Route::get('search', [PublicController::class, 'search'])->name('search');

    // ROUTE FOR PASSWORD
    Route::get('changePassword', [ResetPasswordController::class, 'showChangePasswordForm'])->name('changepassword')->middleware('auth');
    Route::post('updatePassword', [ResetPasswordController::class, 'postChangePassword'])->name('updatepassword')->middleware('auth');
});

// Auth routes
Auth::routes();
Route::get('auth', [LoginController::class, 'adminLogin'])->name('adminLogin');

Route::any('register', function () {
    return redirect()->back();
});

// ROUTE FOR ADMIN ONLY
Route::name('admin.')->prefix('admin')->middleware(['auth', 'admin', 'active', 'check.session'])->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('updateProfile', [AdminController::class, 'updateProfile'])->name('updateProfile');
    Route::get('changePassword', [AdminController::class, 'changePassword'])->name('changePassword');
    Route::post('updatePassword', [AdminController::class, 'updatePassword'])->name('updatePassword');
    Route::get('feedback', [AdminController::class, 'feedback'])->name('feedback');

    // Categories
    Route::resource('categories', CategoryController::class);
    Route::post('categories/deleteAllSelected', [CategoryController::class, 'deleteAllSelected'])->name('categories.deleteAllSelected');

    // Authors
    Route::resource('authors', AuthorController::class);
    Route::post('authors/deleteAllSelected', [AuthorController::class, 'deleteAllSelected'])->name('authors.deleteAllSelected');

    // Publishers
    Route::resource('publishers', PublisherController::class);
    Route::post('publishers/deleteAllSelected', [PublisherController::class, 'deleteAllSelected'])->name('publishers.deleteAllSelected');

    // Racks
    Route::resource('racks', RackController::class);
    Route::post('racks/deleteAllSelected', [RackController::class, 'deleteAllSelected'])->name('racks.deleteAllSelected');

    // Roles
    Route::resource('roles', RoleController::class);
    Route::post('roles/deleteAllSelected', [RoleController::class, 'deleteAllSelected'])->name('roles.deleteAllSelected');

    // Users
    Route::resource('users', UserController::class);
    Route::post('users/deleteAllSelected', [UserController::class, 'deleteAllSelected'])->name('users.deleteAllSelected');
    Route::get('users/{id}/changePassword', [UserController::class, 'changePassword'])->name('users.changePassword');
    Route::post('users/{id}/postChangePassword', [UserController::class, 'postChangePassword'])->name('users.postChangePassword');

    // Items
    Route::get('items/books', [ItemController::class, 'indexBook'])->name('items.books.index');
    Route::get('items/ebooks', [ItemController::class, 'indexEbook'])->name('items.ebooks.index');
    Route::delete('items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');
    Route::post('items/deleteAllSelected', [ItemController::class, 'deleteAllSelected'])->name('items.deleteAllSelected');

    // Item - Lost Books
    Route::get('items/lostBooks', [ItemController::class, 'indexLostBook'])->name('items.lostBooks.index');
    Route::get('items/lostBooks/{id}/edit', [ItemController::class, 'editLostBook'])->name('items.lostBooks.edit');
    Route::put('items/lostBooks', [ItemController::class, 'updateLostBook'])->name('items.lostBooks.update');

    // Items - Books
    Route::get('items/books/create', [ItemController::class, 'bookCreate'])->name('items.books.create');
    Route::post('items/books', [ItemController::class, 'bookStore'])->name('items.books.store');
    Route::get('items/books/{id}/edit', [ItemController::class, 'bookEdit'])->name('items.books.edit');
    Route::put('items/books/{id}', [ItemController::class, 'bookUpdate'])->name('items.books.update');
    Route::get('items/books/{id}', [ItemController::class, 'bookDetail'])->name('items.books.detail');

    // Items - Ebooks
    Route::get('items/ebooks/create', [ItemController::class, 'ebookCreate'])->name('items.ebooks.create');
    Route::post('items/ebooks', [ItemController::class, 'ebookStore'])->name('items.ebooks.store');
    Route::get('items/ebooks/{id}/edit', [ItemController::class, 'ebookEdit'])->name('items.ebooks.edit');
    Route::put('items/ebooks/{id}', [ItemController::class, 'ebookUpdate'])->name('items.ebooks.update');
    Route::get('items/ebooks/{id}', [ItemController::class, 'ebookDetail'])->name('items.ebooks.detail');

    // Issues
    Route::get('issues/fetchUser', [IssueController::class, 'fetchUser'])->name('issues.fetchUser');
    Route::get('issues/fetchBook', [IssueController::class, 'fetchBook'])->name('issues.fetchBook');
    Route::get('issues/penaltySetting', [IssueController::class, 'penaltySetting'])->name('issues.penaltySetting');
    Route::put('issues/penaltyUpdate', [IssueController::class, 'penaltyUpdate'])->name('issues.penaltyUpdate');
    Route::get('issues/borrowSetting', [IssueController::class, 'borrowSetting'])->name('issues.borrowSetting');
    Route::get('issues/fetchRule', [IssueController::class, 'fetchRule'])->name('issues.fetchRule');
    Route::put('issues/borrowUpdate', [IssueController::class, 'borrowUpdate'])->name('issues.borrowUpdate');

    // Issues - Borrows
    Route::get('issues/borrows', [IssueController::class, 'indexBorrow'])->name('issues.borrows.index');
    Route::get('issues/borrows/create', [IssueController::class, 'create'])->name('issues.borrows.create');
    Route::post('issues/borrows', [IssueController::class, 'store'])->name('issues.borrows.store');
    Route::put('issues/borrows/{id}/renew', [IssueController::class, 'renew'])->name('issues.borrows.renew');
    Route::put('issues/borrows/{id}/return', [IssueController::class, 'return'])->name('issues.borrows.return');
    Route::put('issues/borrows/{id}/lost', [IssueController::class, 'lost'])->name('issues.borrows.lost');

    // Issues - Returns
    Route::get('issues/returns', [IssueController::class, 'indexReturn'])->name('issues.returns.index');
});
