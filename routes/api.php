<?php
//backend controllers
use Illuminate\Support\Facades\Route;
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



//these are the routes that user should access whihtout login or register
Route::post('/register', [HomeController::class, 'register']);
Route::post('/login', [HomeController::class, 'login']);
Route::get('/home', [HomeController::class, 'home']);
Route::get('/categories/books/{category}', [HomeController::class, 'booksByCategoryId']);
Route::get('/books/detials/{book}', [HomeController::class, 'BookDetialById']);


//these routes are protected by token that user must authenticate
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [HomeController::class, 'logout']);

    //profile controller 
    Route::prefix('/account')->group(function () {
        Route::get('/profile', [ProfileController::class, 'showProfile']);
        Route::post('/update/profile', [ProfileController::class, 'updateProfile']);
        Route::delete('/profile/delete_account', [ProfileController::class, 'deleteAcount']);
    });

    //Cart routes
    Route::prefix('/cart')->group(function () {
        Route::get('/books', [CartController::class, 'getAllCartBook']);
        Route::post('/books/{book}', [CartController::class, 'addBookToCart']);
        Route::delete('/books/{book}', [CartController::class, 'deleteCartBook']);
    });

    //reserve route
    Route::post('/reserve/books/{book}', [HomeController::class, 'reserveBook']);

});


//admin routess
Route::post('admin/login', [EmployeeController::class, 'login']);
Route::prefix('/dashboard')->middleware('auth:admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);

    //admin routes
    Route::prefix('/admin')->group(function () {
        Route::post('/account/update/{admin}', [EmployeeController::class, 'update']);
        Route::post('/logout', [EmployeeController::class, 'logout']);
    });

    Route::apiResource('/faculties', FacultyController::class);
    Route::apiResource('/departments', DepartmentController::class);
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/sections', SectionController::class);
    Route::apiResource('/books', BookController::class);

    Route::prefix('/users')->group(function () {
        //Admin create , update , delete user 
        Route::post('/create', [UserController::class, 'userCreate']);
        Route::put('/update/{user}', [UserController::class, 'userUpdate']);
        Route::delete('/destroy/{user}', [UserController::class, 'destroy']);
        //activate new registered users route;
        Route::get('/unactivated_users', [UserController::class, 'getUnativatedUsers']);
        Route::get('/unactivated_users/detail/{user}', [UserController::class, 'getUnactivatedUserDetial']);
        Route::post('/activate_user/{user}', [UserController::class, 'ativateUserById']);
        Route::get('/activated_users', [UserController::class, 'getActivatedUsers']);
    });

    //reserves routes
    Route::prefix('/reserves')->group(function () {
        Route::get('/inactive/users', [ReserveController::class, 'getAllReserves']);
        Route::get('/inactive/user_detail/{reserve}', [ReserveController::class, 'getReservedBookUserById']);
        Route::get('/inactive/book_detail/{reserve}', [ReserveController::class, 'getReservedBookDetailById']);
        //set book to the user 
        Route::post('/active/{reserve}', [ReserveController::class, 'setBook']);
        Route::get('/activated/users', [ReserveController::class, 'usersGotBook']);
        Route::post('/return/book/{reserve}', [ReserveController::class, 'userReturnBook']);
    });

    //fine controller 
    Route::prefix('/fines')->group(function () {
        Route::get('/unpaid/users', [FineController::class, 'getUnPaidUsers']);
        Route::post('/pay/{fine}', [FineController::class, 'payFine']);
        Route::get('/paid/users', [FineController::class, 'paidUsers']);
    });

});