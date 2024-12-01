<?php
//backend controllers
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BackControllers\BookController;
use App\Http\Controllers\Api\BackControllers\CategoryController;
use App\Http\Controllers\Api\BackControllers\DashboardController;
use App\Http\Controllers\Api\BackControllers\SectionController;
use App\Http\Controllers\Api\BackControllers\FacultyController;
use App\Http\Controllers\Api\BackControllers\DepartmentController;
use App\Http\Controllers\Api\BackControllers\FineController;
use App\Http\Controllers\Api\BackControllers\UserController;
use App\Http\Controllers\Api\BackControllers\ReserveController;
use App\Http\Controllers\Api\BackControllers\EmployeeController;
use App\Http\Controllers\Api\BackControllers\SettingController;
use App\Http\Controllers\Api\BackControllers\BannerController;

//frontend controllers
use App\Http\Controllers\Api\FrontControllers\ProfileController;
use App\Http\Controllers\Api\FrontControllers\HomeController;
use App\Http\Controllers\Api\FrontControllers\CartController;

//get faculties and departments for registering user
Route::get('/home/faculties/with/departments', [DashboardController::class, 'getFacultyWithDepartments']);
//these are the routes that user should access without login or register
Route::post('/register', [HomeController::class, 'register']);
Route::post('/login', [HomeController::class, 'login']);
Route::get('/home', [HomeController::class, 'home']);
Route::get('/categories/books/{category}', [HomeController::class, 'booksByCategoryId']);
Route::get('/books/details/{book}', [HomeController::class, 'BookDetailById']);


//these routes are protected by token that user must authenticate
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [HomeController::class, 'logout']);

    //profile controller 
    Route::prefix('/account')->group(function () {
        Route::get('/profile', [ProfileController::class, 'showProfile']);
        Route::post('/update/profile', [ProfileController::class, 'updateProfile']);
        Route::delete('/profile/delete_account', [ProfileController::class, 'deleteAccount']);
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


//admin routes
Route::post('admin/login', [EmployeeController::class, 'login']);
Route::prefix('/dashboard')->middleware('auth:admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    //get faculties with departments
    Route::get('/faculties/with/departments', [DashboardController::class, 'getFacultyWithDepartments']);
    //admin routes
    Route::prefix('/admin')->group(function () {
        Route::get('/account/employees', [EmployeeController::class, 'getAllEmployees']);
        Route::post('/account/new/create', [EmployeeController::class, 'createEmployee']);
        Route::post('/account/update/employee/{employee}', [EmployeeController::class, 'update']);
        Route::post('/account/set_permissions/', [EmployeeController::class, 'setPermission']);
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
        Route::get('/inactivated_students', [UserController::class, 'getInactivatedStudents']);
        Route::get('/inactivated_teachers', [UserController::class, 'getInactivatedTeachers']);
        Route::get('/inactivated_users/detail/{user}', [UserController::class, 'getInactivatedUserDetail']);
        Route::post('/activate_user/{user}', [UserController::class, 'activateUserById']);
        Route::get('/activated_students', [UserController::class, 'getActivatedStudents']);
        Route::get('/activated_teachers', [UserController::class, 'getActivatedTeachers']);
        Route::get('/activated_users/{user}', [UserController::class, 'getActivatedUserById']);
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
        Route::get('/books/in/reserve', [ReserveController::class, 'allBookInReserve']);
        Route::get('/books/have_reserved', [ReserveController::class, 'allReservedBook']);
    });

    //fine controller 
    Route::prefix('/fines')->group(function () {
        Route::get('/unpaid/users', [FineController::class, 'getUnpaidUsers']);
        Route::post('/pay/{fine}', [FineController::class, 'payFine']);
        Route::get('/paid/users', [FineController::class, 'paidUsers']);
    });

    //setting routes
    Route::apiResource('banners', BannerController::class);
});
