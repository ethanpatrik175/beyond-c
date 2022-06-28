<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SpeakerController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductMetaController;
use App\Http\Controllers\RelatedProductController;
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

Route::name('front.')->group(function(){
    Route::get('/', [FrontendController::class, 'welcome'])->name('welcome');
    Route::get('/home', [FrontendController::class, 'welcome'])->name('home');
    Route::get('/about-us', [FrontendController::class, 'aboutUs'])->name('about.us');
    Route::get('/blogs', [FrontendController::class, 'blogs'])->name('blogs');
    Route::get('/blog/{slug}', [FrontendController::class, 'blogDetail'])->name('blog.detail');
    Route::get('/events', [FrontendController::class, 'viewEvents'])->name('view.events');
    Route::get('/event/{slug}', [FrontendController::class, 'eventDetail'])->name('event.detail');
    
    Route::get('/charity-donation', [FrontendController::class, 'charityDonation'])->name('charity.donation');
    Route::post('/process-charity-donation', [FrontendController::class, 'processCharityDonation'])->name('process.charity.donation');

    Route::get('/checkout', [FrontendController::class, 'checkout'])->name('checkout');
    Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
    Route::get('/product-promotion', [FrontendController::class, 'productPromotion'])->name('product.promotion');
    Route::get('/products/{id}', [FrontendController::class, 'productDetail'])->name('product.detail');
    Route::get('/products', [FrontendController::class, 'Products'])->name('products');
    Route::get('/travel-packages', [FrontendController::class, 'travelPackages'])->name('travel.packages');
    Route::get('/travel-package/{slug}', [FrontendController::class, 'travelPackageDetail'])->name('travel.package.detail');

    // Route::get('/Category_products', [EcommerceController::class, 'Products_Category']);
    Route::get('/addtocart', [FrontendController::class, 'addtocart']);

    Route::get('cart', [CartController::class, 'cartList'])->name('cart.list');
    Route::get('cart/{id}', [CartController::class, 'addToCart'])->name('cart.store');
    Route::post('update-cart', [CartController::class, 'updateCart'])->name('cart.update');
    Route::post('/remove/{id}', [CartController::class, 'removeCart'])->name('cart.remove');
    Route::post('clear', [CartController::class, 'clearAllCart'])->name('cart.clear');

});

Auth::routes();
Route::get('/admin', function(){ return redirect()->route('admin.login'); });
Route::get('/admin/login', [LoginController::class, 'adminLogin'])->name('admin.login');
Route::post('/login-process', [LoginController::class,'loginProcess'])->name('login.process');

Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('user.dashboard');//->middleware('verified');
});

Route::middleware(['auth', 'verified'])->name('general.')->group(function(){
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/update-profile-process', [UserController::class, 'updateProfileProcess'])->name('update.profile.process');
    Route::post('/change-password-process', [UserController::class, 'changePasswordProcess'])->name('change.password.process');
    Route::post('/change-picture-process', [UserController::class, 'changePictureProcess'])->name('change.picture.process');
});

/** Admin Routes */
Route::middleware(['auth', 'admin.middleware'])->prefix('/admin')->group(function(){
    Route::resource('speakers', SpeakerController::class);
    Route::get('/speaker/trash', [SpeakerController::class, 'trash'])->name('speakers.trash');
    Route::post('/speaker/update-status', [SpeakerController::class, 'updateStatus'])->name('speakers.update.status');
    Route::post('/speaker/restore', [SpeakerController::class, 'restore'])->name('speakers.restore');

    Route::resource('venues', VenueController::class);
    Route::get('/venue/trash', [VenueController::class, 'trash'])->name('venues.trash');
    Route::post('/venue/update-status', [VenueController::class, 'updateStatus'])->name('venues.update.status');
    Route::post('/venue/restore', [VenueController::class, 'restore'])->name('venues.restore');

    Route::resource('sponsors', SponsorController::class);
    Route::get('/sponsor/trash', [SponsorController::class, 'trash'])->name('sponsors.trash');
    Route::post('/sponsor/update-status', [SponsorController::class, 'updateStatus'])->name('sponsors.update.status');
    Route::post('/sponsor/restore', [SponsorController::class, 'restore'])->name('sponsors.restore');

    Route::resource('events', EventController::class);
    Route::get('/event/trash', [EventController::class, 'trash'])->name('events.trash');
    Route::post('/event/update-status', [EventController::class, 'updateStatus'])->name('events.update.status');
    Route::post('/event/restore', [EventController::class, 'restore'])->name('events.restore');

    //blogs
    Route::resource('blogs', PostController::class);
    Route::post('/blog/updates-status-process', [PostController::class, 'updateStatus'])->name('blog.update.status');
    Route::get('blog/trash', [PostController::class, 'trash'])->name('blog.trash');
    Route::post('/blog/restore', [PostController::class, 'restorePost'])->name('blog.restore');
    //tags
    Route::resource('tags', TagController::class);
    Route::post('/tag/updates-status-process', [TagController::class, 'updateStatus'])->name('tag.update.status');
    Route::get('tag/trash', [TagController::class, 'trash'])->name('tag.trash');
    Route::post('/tag/restore', [TagController::class, 'restoretags'])->name('tag.restore');
    //category
    Route::resource('categories', CategoryController::class);
    Route::post('/category/updates-status-process', [CategoryController::class, 'updateStatus'])->name('category.update.status');
    Route::get('category/trash', [CategoryController::class, 'trash'])->name('category.trash');
    Route::post('/category/restore', [CategoryController::class, 'restoreCategory'])->name('category.restore');
    //Comments
    Route::resource('comments', CommentController::class);
    Route::post('status-update', [CommentController::class, 'updateStatus'])->name('status.update');
    Route::get('approved', [CommentController::class, 'approved'])->name('status.approved');
    Route::get('non-approved', [CommentController::class, 'non_approved'])->name('status.non.approved');

    // Route::get('comment/trash', [CommentController::class, 'trash'])->name('comment.trash');
    // Route::post('/comment/restore', [CommentController::class, 'restorecomments'])->name('comment.restore');
     //Product_Category
     Route::resource('product_categories', ProductCategoryController::class);
     Route::post('/product_category/updates-status-process', [ProductCategoryController::class, 'updateStatus'])->name('product_category.update.status');
     Route::get('product_category/trash', [ProductCategoryController::class, 'trash'])->name('product_category.trash');
     Route::post('/product_category/restore', [ProductCategoryController::class, 'restore'])->name('product_category.restore');
     //Product
     Route::resource('products', ProductController::class);
     Route::post('/product/updates-status-process', [ProductController::class, 'updateStatus'])->name('product.update.status');
     Route::get('product/trash', [ProductController::class, 'trash'])->name('product.trash');
     Route::post('/product/restore', [ProductController::class, 'restore'])->name('product.restore');
     //Product Meta
     Route::resource('product_metas', ProductMetaController::class);
     Route::post('/product_meta/updates-status-process', [ProductMetaController::class, 'updateStatus'])->name('product_meta.update.status');
     Route::get('product_meta/trash', [ProductMetaController::class, 'trash'])->name('product_meta.trash');
     Route::post('/product_meta/restore', [ProductMetaController::class, 'restore'])->name('product_meta.restore');
     //Related Product
     Route::resource('related_products', RelatedProductController::class);
     Route::post('/related_product/updates-status-process', [RelatedProductController::class, 'updateStatus'])->name('related_product.update.status');
     Route::get('related_product/trash', [RelatedProductController::class, 'trash'])->name('related_product.trash');
     Route::post('/related_product/restore', [RelatedProductController::class, 'restore'])->name('related_product.restore');
    //brands
     Route::resource('brands', BrandController::class);
     Route::post('/brand/updates-status-process', [BrandController::class, 'updateStatus'])->name('brand.update.status');
     Route::get('brand/trash', [BrandController::class, 'trash'])->name('brand.trash');
     Route::post('/brand/restore', [BrandController::class, 'restore'])->name('brand.restore');
     //order
     Route::resource('orders', OrderController::class);
     Route::post('updated', [OrderController::class, 'update'])->name('order.updated');
     Route::get('order-history/{id}', [OrderController::class, 'orderHistory'])->name('order.history');
    //Contact us
     Route::resource('contact-us', ContactController::class);
     Route::get('/contact-read-message', [ContactController::class, 'readMessage'])->name('contact.read.message');
     Route::post('/reomve-message', [ContactController::class, 'removeMessage'])->name('remove.message');


    //  Route::post('/brand/updates-status-process', [BrandController::class, 'updateStatus'])->name('brand.update.status');
    //  Route::get('brand/trash', [BrandController::class, 'trash'])->name('brand.trash');
    //  Route::post('/brand/restore', [BrandController::class, 'restore'])->name('brand.restore');
});
/**End Admin Routes */

Route::middleware(['auth'])->group(function(){
    Route::post('/book-ticket', [CustomerController::class, 'bookTicket'])->name('book.ticket');
    Route::post('/order', [OrderController::class, 'store'])->name('store');
});

// Dating Routes Start
Route::middleware(['auth', 'datting.middleware'])->group(function(){
    Route::get('/find-your-date', [DatingController::class, 'findYourDate'])->name('find.your.date');
});

Route::middleware(['auth'])->name('dating.')->group(function(){
    // Create Account for dating account
    Route::get('/create-account', [DatingController::class, 'createAccount'])->name('create.account');
    Route::post('/dating/step-one-process', [DatingController::class, 'stepOneProcess'])->name('step.one.process');
    Route::post('/dating/step-two-process', [DatingController::class, 'stepTwoProcess'])->name('step.two.process');
    Route::post('/dating/step-three-process', [DatingController::class, 'stepThreeProcess'])->name('step.three.process');
    Route::post('/dating/step-four-process', [DatingController::class, 'stepFourProcess'])->name('step.four.process');
    Route::post('/dating/final-step-process', [DatingController::class, 'finalStepProcess'])->name('final.step.process');

    Route::post('/dating/upload-image', [DatingController::class, 'uploadImage'])->name('upload.image.process');
    Route::post('/dating/remove-image', [DatingController::class, 'removeImage'])->name('upload.remove.image.process');

    Route::post('/dating/restore', [DatingController::class, 'restoreStep'])->name('restore.step');
    Route::post('/dating/step-back', [DatingController::class, 'stepBack'])->name('step.back');
});

//Dating Routes End



Route::get('/thank-you', [FrontendController::class, 'thankYou'])->name('thank.you');
Route::post('/comment-store', [CommentController::class, 'store'])->name('comments.store');
Route::post('/create-slug', [GlobalController::class, 'createSlug'])->name('create.slug');
Route::get('/file-path',  [GlobalController::class, 'getPath'])->name('get.assets.path');
Route::get('/clear', [GlobalController::class, 'clear'])->name('clear');