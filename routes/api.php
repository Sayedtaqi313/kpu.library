<?php
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\FacultyController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ReserveController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//user routes // this route should protected by gate or policy if user is authentiaced
Route::get('home/faculties',[HomeController::class,'getFacultyWithDepartments']);
Route::post('/register',[HomeController::class,'register']);
Route::post('/login',[HomeController::class,'login']);
Route::get('home',[HomeController::class,'home']);
Route::get('category/books/{category}',[HomeController::class,'booksByCategoryId']);
Route::get('book/detials/{book}',[HomeController::class,'BookDetialById']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout',[HomeController::class,'logout']);
    Route::post('/reserve/book/{book}',[HomeController::class,'reserveBook']);
});


//admin routess
Route::apiResource('faculties',FacultyController::class);
Route::apiResource('departments',DepartmentController::class);
Route::apiResource('categories',CategoryController::class);
Route::apiResource('sections',SectionController::class);
Route::apiResource('books',BookController::class);
//activate new registered users route;
Route::get('users/unactivated_users',[UserController::class,'getUnativatedUsers']);
Route::get('users/unactivated_users/{user}',[UserController::class,'getUnactivatedUserDetial']);
Route::post('users/activate_user/{user}',[UserController::class,'ativatedUserById']);
//set book to the active user if they are active
Route::get('reserves/inactive/users',[ReserveController::class,'getAllReserves']);
Route::get('reserves/inactive/users_detail/{reserve}',[ReserveController::class,'getReservedBookUserById']);
Route::get('reserves/inactive/book_detail/{reserve}',[ReserveController::class,'getReservedBookDetailById']);
Route::post('reserves/atctive/{reserve}',[ReserveController::class,'setBook']);

