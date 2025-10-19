<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CourseController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\Frontend\IndexController;
// use App\Http\Controllers\Frontend\WishListController;
use App\Http\Controllers\Frontend\UserTryoutController;
use App\Http\Controllers\Frontend\TryoutHistoryController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\OrderController;
// use App\Http\Controllers\Backend\QuestionController;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\ReviewController;
use App\Http\Controllers\Backend\ActiveUserController;
use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\ChatController;
use App\Http\Controllers\Backend\TryoutPackageController;
use App\Http\Controllers\Backend\QuestionController;
use App\Http\Controllers\Backend\GamificationController;


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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [UserController::class, 'index'])->name('index');

Route::get('/dashboard', function () {
    return view('frontend.dashboard.index');
})->middleware(['auth','roles:user','verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/user/profile', [UserController::class, 'userProfile'])->name('user.profile');
    Route::post('/user/profile/update', [UserController::class, 'userProfileUpdate'])->name('user.profile.update');
    Route::get('/user/logout', [UserController::class, 'userLogout'])->name('user.logout');
    Route::get('/user/change/password', [UserController::class, 'userChangePassword'])->name('user.change.password');
    Route::post('/user/password/update', [UserController::class, 'userPasswordUpdate'])->name('user.password.update');
    
    Route::get('/live/chat', [UserController::class, 'liveChat'])->name('live.chat');

    Route::get('/tryout/{tryout_id}/join', [UserTryoutController::class, 'AddToTryout']);


    Route::controller(UserTryoutController::class)->group(function () {
        Route::get('/user/tryout', 'myTryout')->name('my.tryout');
        
        Route::get('/tryout/start/{id}', 'StartTryout')->name('tryout.start');
        Route::post('/tryout/submit/{id}', 'SubmitTryout')->name('tryout.submit');

        Route::get('/delete/tryout/{id}', 'DeleteTryout')->name('delete.tryout');

        Route::get('/mytryout/result/{id}', 'ResultTryout')->name('user.tryout.result');

        Route::get('/tryout/explanation/{tryout_id}', 'explanation')->name('tryout.explanation');

        Route::post('tryout/{id}/complete', 'completeTryout')->name('tryout.complete');

    });


    Route::get('/mytryout/history', [TryoutHistoryController::class, 'index'])->middleware('auth')->name('tryout.history');
    
    Route::get('/my-badges', [IndexController::class, 'myBadges'])->name('user.badges');

  
});

require __DIR__.'/auth.php';

////Admin Group Middleware 
Route::middleware(['auth', 'roles:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');
    Route::get('/admin/profile', [AdminController::class, 'adminProfile'])->name('admin.profile');
    Route::post('/admin/profile/store', [AdminController::class, 'adminProfileStore'])->name('admin.profile.store');
    Route::get('/admin/change/password', [AdminController::class, 'adminChangePassword'])->name('admin.change.password');
    Route::post('/admin/password/update', [AdminController::class, 'adminPasswordUpdate'])->name('admin.password.update');

    

    

    // Instructor All Routes
    Route::controller(AdminController::class)->group(function () {
        Route::get('/all/instructor', 'allInstructor')->name('all.instructor');
        Route::post('/update/user/status', 'updateUserStatus')->name('update.user.status');
       
    });
    

    

    // SMPT All Route 
    Route::controller(SettingController::class)->group(function(){
        Route::get('/smtp/setting','smtpSetting')->name('smtp.setting');
        Route::post('/update/smtp','smtpUpdate')->name('update.smtp');
    });

    // Site Setting All Route 
    Route::controller(SettingController::class)->group(function(){
        Route::get('/site/setting','siteSetting')->name('site.setting'); 
        Route::post('/update/site','updateSite')->name('update.site'); 


    });


    Route::controller(GamificationController::class)->group(function(){
        Route::get('/points/rules', 'pointsRules')->name('points.rules');
        Route::get('/points/rules/create', 'createPointsRule')->name('points.rules.create');
        Route::post('/points/rules/store', 'storePointsRule')->name('points.rules.store');
        Route::get('/points/rules/edit/{id}', 'editPointsRule')->name('points.rules.edit');
        Route::post('/points/rules/update', 'updatePointsRule')->name('points.rules.update');
        Route::get('/points/rules/delete/{id}', 'deletePointsRule')->name('points.rules.delete');

        Route::get('/badges', 'badges')->name('badges');
        Route::get('/badges/create', 'createBadge')->name('badges.create');
        Route::post('/badges/store', 'storeBadge')->name('badges.store');
        Route::patch('/admin/badge/{id}/toggle', [GamificationController::class, 'toggleBadge'])->name('badge.toggle');
        Route::get('/badges/edit/{id}', 'editBadge')->name('badges.edit');
        Route::post('/badges/update', 'updateBadge')->name('badges.update');
        // Route::get('/badges/delete/{id}', 'deleteBadge')->name('badges.delete');

        // Route::get('/user/points', 'userPoints')->name('user.points');
        // Route::get('/user/badges', 'userBadges')->name('user.badges');
    });
        

    // Admin Report All Route 
    Route::controller(ReportController::class)->group(function(){
        Route::get('/report/view','reportView')->name('report.view'); 
        Route::post('/search/by/date','searchByDate')->name('search.by.date');    
        Route::post('/search/by/month','searchByMonth')->name('search.by.month');
        Route::post('/search/by/year','searchByYear')->name('search.by.year');    
    });

    // Admin Review All Route 
    Route::controller(ReviewController::class)->group(function(){
        Route::get('/admin/pending/review','adminPendingReview')->name('admin.pending.review'); 
        Route::post('/update/review/status', 'updateReviewStatus')->name('update.review.status');
        Route::get('/admin/active/review','adminActiveReview')->name('admin.active.review');
    });

    // Admin All user and Instructor All Route 
    Route::controller(ActiveUserController::class)->group(function(){
        Route::get('/all/user','allUser')->name('all.user'); 
        Route::get('/all/instructor','allInstructor')->name('all.instructor'); 

    });

    // Blog Category All Route 
    Route::controller(BlogController::class)->group(function(){
        Route::get('/blog/category','allBlogCategory')->name('blog.category'); 
        Route::post('/blog/category/store','storeBlogCategory')->name('blog.category.store'); 
        Route::get('edit/blog/category/{id}','editBlogCategory');
        Route::post('/blog/category/update','updateBlogCategory')->name('blog.category.update'); 
        Route::get('/delete/blog/category/{id}','deleteBlogCategory')->name('delete.blog.category');         
    });

    // Blog Post All Route 
    Route::controller(BlogController::class)->group(function(){
        Route::get('/blog/post','blogPost')->name('blog.post'); 
        Route::get('/add/blog/post','addBlogPost')->name('add.blog.post');      
        Route::post('/store/blog/post','storeBlogPost')->name('store.blog.post');
        Route::get('/edit/post/{id}','editBlogPost')->name('edit.post');  
        Route::post('/update/blog/post','updateBlogPost')->name('update.blog.post');
        Route::get('/delete/post/{id}','deleteBlogPost')->name('delete.post');
    });

    // Permission All Route 
    Route::controller(RoleController::class)->group(function(){
        Route::get('/all/permission','allPermission')->name('all.permission'); 
        Route::get('/add/permission','addPermission')->name('add.permission');      
        Route::post('/store/permission','storePermission')->name('store.permission');
        Route::get('/edit/permission/{id}','editPermission')->name('edit.permission');  
        Route::post('/update/permission','updatePermission')->name('update.permission');
        Route::get('/delete/permission/{id}','deletePermission')->name('delete.permission');
        Route::get('/import/permission','importPermission')->name('import.permission');
        Route::get('/export','export')->name('export');
        Route::post('/import','import')->name('import');
    });

        // Role All Route 
    Route::controller(RoleController::class)->group(function(){
        Route::get('/all/roles','allRoles')->name('all.roles');
        Route::get('/add/roles','addRoles')->name('add.roles'); 
        Route::post('/store/roles','storeRoles')->name('store.roles');
        Route::get('/edit/roles/{id}','editRoles')->name('edit.roles');
        Route::post('/update/roles','updateRoles')->name('update.roles');
        Route::get('/delete/roles/{id}','deleteRoles')->name('delete.roles');
        Route::get('/add/roles/permission','addRolesPermission')->name('add.roles.permission');        
        Route::post('/role/permission/store','rolePermissionStore')->name('role.permission.store');        
        Route::get('/all/roles/permission','allRolesPermission')->name('all.roles.permission');
        Route::get('/admin/edit/roles/{id}','adminEditRoles')->name('admin.edit.roles');
        Route::post('/admin/roles/update/{id}','adminUpdateRoles')->name('admin.roles.update');
        Route::get('/admin/delete/roles/{id}','adminDeleteRoles')->name('admin.delete.roles');
    });
    // Admin User All Route 
    Route::controller(AdminController::class)->group(function(){
        Route::get('/all/admin','allAdmin')->name('all.admin'); 
        Route::get('/add/admin','addAdmin')->name('add.admin');
        Route::post('/store/admin','storeAdmin')->name('store.admin');        
        Route::get('/edit/admin/{id}','editAdmin')->name('edit.admin');
        Route::post('/update/admin/{id}','updateAdmin')->name('update.admin');
        Route::get('/delete/admin/{id}','deleteAdmin')->name('delete.admin');
    });

}); //end admin group middleware

Route::get('/admin/login', [AdminController::class, 'adminLogin'])->name('admin.login')->middleware(RedirectIfAuthenticated::class);
Route::get('/become/instructor', [AdminController::class, 'becomeInstructor'])->name('become.instructor');
Route::post('/instructor/register', [AdminController::class, 'instructorRegister'])->name('instructor.register');


////Instructor Group Middleware
Route::middleware(['auth', 'roles:instructor'])->group(function () {
    Route::get('/instructor/dashboard', [InstructorController::class, 'instructorDashboard'])->name('instructor.dashboard');
    Route::get('/instructor/logout', [InstructorController::class, 'instructorLogout'])->name('instructor.logout');
    Route::get('/instructor/profile', [InstructorController::class, 'instructorProfile'])->name('instructor.profile');
    Route::post('/instructor/profile/store', [InstructorController::class, 'instructorProfileStore'])->name('instructor.profile.store');
    Route::get('/instructor/change/password', [InstructorController::class, 'instructorChangePassword'])->name('instructor.change.password');
    Route::post('/instructor/password/update', [InstructorController::class, 'instructorPasswordUpdate'])->name('instructor.password.update');
   
    Route::controller(TryoutPackageController::class)->group(function () {
        Route::get('/all/tryout/packages','allTryoutPackages')->name('all.tryout.packages');
        Route::get('/add/tryout/package', 'addTryoutPackage')->name('add.tryout');
        Route::post('/store/tryout/package', 'storeTryoutPackage')->name('store.tryout.package');
        Route::get('/edit/tryout/package/{id}', 'editTryoutPackage')->name('edit.tryout.package');
        Route::post('/update/tryout/package', 'updateTryoutPackage')->name('update.tryout.package');
        Route::get('/delete/tryout/package/{id}', 'deleteTryoutPackage')->name('delete.tryout.package');
        Route::post('/update/tryout/package/status', 'updateTryoutPackageStatus')->name('update.tryout.package.status');
   
    Route::get('/instructor/packages/{package}/manage','managePackage')->name('packages.manage');

    Route::post('/instructor/packages/{package}/questions/attach','attachQuestions')->name('packages.questions.attach');

    Route::get('/instructor/packages/{package}/questions/{question}/detach','detachQuestion')->name('packages.questions.detach');

    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/all/category','allCategory')->name('all.category');
        Route::post('/store/category', 'storeCategory')->name('store.category');
        // Route::get('/add/category', 'addCategory')->name('add.category');
        Route::get('/edit/category/{id}', 'editCategory')->name('edit.category');
        Route::post('/update/category', 'updateCategory')->name('update.category');
        Route::get('/delete/category/{id}', 'deleteCategory')->name('delete.category');
    });

    Route::controller(QuestionController::class)->group(function () {
        Route::get('/all/question', 'allQuestion')->name('all.question');
        Route::get('/add/question', 'addQuestion')->name('add.question');
        Route::post('/store/question', 'storeQuestion')->name('store.question');
        Route::get('/edit/question/{id}', 'editQuestion')->name('edit.question');
        Route::post('/update/question', 'updateQuestion')->name('update.question');
        Route::get('/delete/question/{id}', 'deleteQuestion')->name('delete.question');
        Route::get('/import/question','importQuestion')->name('import.question');
        Route::post('/import','import')->name('import');

    });

    

    // // Course Section and Lecture Routes
    // Route::controller(CourseController::class)->group(function () {
    //     Route::get('/add/course/lecture/{id}', 'addCourseLecture')->name('add.course.lecture');
    //     Route::post('/add/course/section/', 'addCourseSection')->name('add.course.section');
    //     Route::post('/save-lecture/', 'saveLecture')->name('save-lecture');
    //     Route::get('/edit/lecture/{id}','editLecture')->name('edit.lecture');
    //     Route::post('/update/course/lecture','updateCourseLecture')->name('update.course.lecture');
    //     Route::get('/delete/lecture/{id}','deleteLecture')->name('delete.lecture');
    //     Route::post('/delete/section/{id}','deleteSection')->name('delete.section');

    // });

    // instructor All Order Route 
    Route::controller(OrderController::class)->group(function(){
        Route::get('/instructor/all/order','instructorAllOrder')->name('instructor.all.order'); 
        Route::get('/instructor/order/details/{payment_id}','instructorOrderDetails')->name('instructor.order.details');
    Route::get('/instructor/order/invoice/{payment_id}','instructorOrderInvoice')->name('instructor.order.invoice');        
    });      

    // Instructor Coupon All Route 
    Route::controller(CouponController::class)->group(function(){
        Route::get('/instructor/all/coupon','instructorAllCoupon')->name('instructor.all.coupon');
        Route::get('/instructor/add/coupon','instructorAddCoupon')->name('instructor.add.coupon');
        Route::post('/instructor/store/coupon','instructorStoreCoupon')->name('instructor.store.coupon');
        Route::get('/instructor/edit/coupon/{id}','instructorEditCoupon')->name('instructor.edit.coupon');
        Route::post('/instructor/update/coupon','instructorUpdateCoupon')->name('instructor.update.coupon');
        Route::get('/instructor/delete/coupon/{id}','instructorDeleteCoupon')->name('instructor.delete.coupon');
    });    

    // Instructor Review All Route 
    Route::controller(ReviewController::class)->group(function(){
        Route::get('/instructor/all/review','instructorAllReview')->name('instructor.all.review');  
        
    });


}); //end instructor group middleware

Route::get('/tryout/{tryout_id}/join',[UserTryoutController::class, 'AddToTryout'])->name('user.join.tryout');
///route accessable for all
Route::get('/instructor/login', [InstructorController::class, 'instructorLogin'])->name('instructor.login')->middleware(RedirectIfAuthenticated::class);

Route::get('/course/details/{id}/{slug}', [IndexController::class, 'courseDetails']);
// Route::get('/category/{id}/{slug}', [IndexController::class, 'categoryCourse']);
// Route::get('/subcategory/{id}/{slug}', [IndexController::class, 'subcategoryCourse']);
Route::get('/instructor/details/{id}', [IndexController::class, 'instructorDetails'])->name('instructor.details');
Route::post('/add-to-wishlist/{tryout_id}', [WishListController::class, 'addToWishList']);
Route::post('/cart/data/store/{id}', [CartController::class, 'addToCart']);
Route::post('/buy/data/store/{id}', [CartController::class, 'buyToCart']);
Route::get('/cart/data/', [CartController::class, 'cartData']);
// Get Data from Minicart 
Route::get('/course/mini/cart/', [CartController::class, 'addMiniCart']);
Route::get('/minicart/course/remove/{rowId}', [CartController::class, 'removeMiniCart']);

// Cart All Route 
Route::controller(CartController::class)->group(function(){
    Route::get('/mycart','myCart')->name('mycart');
    Route::get('/get-cart-course','getCartCourse');
    Route::get('/cart-remove/{rowId}','cartRemove');

});

Route::post('/coupon-apply', [CartController::class, 'couponApply']);
Route::post('/inscoupon-apply', [CartController::class, 'insCouponApply']);
Route::get('/coupon-calculation', [CartController::class, 'couponCalculation']);
Route::get('/coupon-remove', [CartController::class, 'couponRemove']);

/// Checkout Page Route 
Route::get('/checkout', [CartController::class, 'checkoutCreate'])->name('checkout');
Route::post('/payment', [CartController::class, 'payment'])->name('payment');
Route::post('/stripe_order', [CartController::class, 'stripeOrder'])->name('stripe_order');

Route::post('/store/review', [ReviewController::class, 'storeReview'])->name('store.review');

Route::get('/blog/details/{slug}', [BlogController::class, 'blogDetails']);
Route::get('/blog/cat/list/{id}', [BlogController::class, 'blogCatList']);
Route::get('/blog', [BlogController::class, 'blogList'])->name('blog');

Route::post('/mark-notification-as-read/{notification}', [CartController::class, 'markAsRead']);

// Chat Post Request Route
Route::post('/send-message', [ChatController::class, 'sendMessage']);

Route::get('/user-all', [ChatController::class, 'getAllUsers']);

Route::get('/user-message/{id}', [ChatController::class, 'userMsgById']);

Route::get('/instructor/live/chat', [ChatController::class, 'liveChat'])->name('instructor.live.chat');
///// End Route Accessable for All 