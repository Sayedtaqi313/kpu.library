<?php
//backend controllers
use App\Http\Controllers\Api\backControllers\BookController;
use App\Http\Controllers\Api\backControllers\CategoryController;
use App\Http\Controllers\Api\BackControllers\DashboardController;
use App\Http\Controllers\Api\backControllers\SectionController;
use App\Http\Controllers\Api\backControllers\FacultyController;
use App\Http\Controllers\Api\backControllers\DepartmentController;
use App\Http\Controllers\Api\backControllers\FineController;
use App\Http\Controllers\Api\backControllers\UserController;
use App\Http\Controllers\Api\backControllers\ReserveController;
use App\Http\Controllers\Api\backControllers\EmployeeController;
//frontend controllers
use App\Http\Controllers\Api\frontControllers\ProfileController;
use App\Http\Controllers\Api\frontControllers\HomeController;
use App\Http\Controllers\Api\frontControllers\CartController;
use Illuminate\Support\Facades\Route;


//these are the routes that use should access whihtout login or register
Route::post('/register', [HomeController::class, 'register']);
Route::post('/login', [HomeController::class, 'login']);
Route::get('/home', [HomeController::class, 'home']);
Route::get('/categories/books/{category}', [HomeController::class, 'booksByCategoryId']);
Route::get('/books/detials/{book}', [HomeController::class, 'BookDetialById']);


//these routes are protected by token that user must authenticate
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [HomeController::class, 'logout']);
    //profile controller 
    Route::get('/account/profile', [ProfileController::class, 'showProfile']);
    Route::post('/account/update/profile', [ProfileController::class, 'updateProfile']);
    Route::delete('/account/profile/delete_account', [ProfileController::class, 'deleteAcount']);
    //Cart routes
    Route::get('/cart/books', [CartController::class, 'getAllCartBook']);
    Route::post('/cart/book/{book}', [CartController::class, 'addBookToCart']);
    Route::delete('/cart/book/{book}', [CartController::class, 'deleteCartBook']);
    //reserve route
    Route::post('/reserve/book/{book}', [HomeController::class, 'reserveBook']);

});


//admin routess
Route::post('admin/login',[EmployeeController::class,'login']);
Route::prefix('/dashboard')->middleware('auth:admin')->group(function () {
    Route::get('/',[DashboardController::class,'index']);
    Route::post('/admin/account/{admin}',[EmployeeController::class,'update']);
    Route::post('/admin/logout',[EmployeeController::class,'logout']);
    Route::apiResource('/faculties', FacultyController::class);
    Route::apiResource('/departments', DepartmentController::class);
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/sections', SectionController::class);
    Route::apiResource('/books', BookController::class);
    //Admin create , update , delete user 
    Route::post('/users/create', [UserController::class, 'userCreate']);
    Route::put('/users/update/{user}', [UserController::class, 'userUpdate']);
    Route::delete('/users/destroy/{user}', [UserController::class, 'destroy']);
    //activate new registered users route;
    Route::get('/users/unactivated_users', [UserController::class, 'getUnativatedUsers']);
    Route::get('/users/unactivated_users/detail/{user}', [UserController::class, 'getUnactivatedUserDetial']);
    Route::post('/users/activate_user/{user}', [UserController::class, 'ativatedUserById']);
    Route::get('/users/activated_users', [UserController::class, 'getActivatedUsers']);
    //set book to the active user if they are active
    Route::get('/reserves/inactive/users', [ReserveController::class, 'getAllReserves']);
    Route::get('/reserves/inactive/users_detail/{reserve}', [ReserveController::class, 'getReservedBookUserById']);
    Route::get('/reserves/inactive/book_detail/{reserve}', [ReserveController::class, 'getReservedBookDetailById']);
    Route::post('/reserves/active/{reserve}', [ReserveController::class, 'setBook']);
    Route::get('/reserves/active/users', [ReserveController::class, 'usersGotBook']);
    Route::post('/reserves/return/book/{reserve}', [ReserveController::class, 'userReturnBook']);
    //fine controller 
    Route::get('/fines/unpaid/users', [FineController::class, 'getUnPaidUsers']);
    Route::post('/fines/pay/{fine}', [FineController::class, 'payFine']);
    Route::get('/fines/paid/users', [FineController::class, 'paidusers']);
});