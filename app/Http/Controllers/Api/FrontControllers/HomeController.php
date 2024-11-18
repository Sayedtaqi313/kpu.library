<?php

namespace App\Http\Controllers\Api\FrontControllers;

use App\Http\Resources\FacultyResource;
use App\Http\Resources\HomeResource;
use App\Models\Department;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\BookResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use App\Models\Reserve;
use App\Models\Faculty;
use App\Models\Banner;

/**
 * @OA\PathItem(path="/api")
 *
 * @OA\Info(
 *      version="0.0.0",
 *      title="Anophel API Documentation"
 *  )
 */
class HomeController extends Controller
{

    /**
     * @OA\Post(
     * path="/api/register",
     * operationId="Register",
     * tags={"Register"},
     * summary="User Register",
     * description="User Register here but first you have to fill the the faculty and department because the user belongs to a faculty and department",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"firstName","lastName", "email","password","current_residence","original_residence","phone","nic","fac_id", "dep_id", "type"},
     *               @OA\Property(property="firstName", type="text"),
     *               @OA\Property(property="lastName", type="text"),
     *               @OA\Property(property="email", type="text"),
     *               @OA\Property(property="password", type="password"),
     *               @OA\Property(property="phone", type="text"),
     *               @OA\Property(property="nic", type="text"),
     *               @OA\Property(property="current_residence", type="text"),
     *               @OA\Property(property="original_residence", type="text"),
     *               @OA\Property(property="fac_id", type="text"),
     *               @OA\Property(property="dep_id", type="text"),
     *               @OA\Property(property="type", type="text"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Type is incorrect"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function register(RegisterRequest $request)
    {
        $user = null;
        $faculty = Faculty::find($request->fac_id);
        $department = Department::find($request->dep_id);
        if (!$faculty) {
            return response()->json(['message' => "Faculty not found"], Response::HTTP_NOT_FOUND);
        }
        if (!$department) {
            return response()->json(['message' => "Department not found"], Response::HTTP_NOT_FOUND);
        }
        if (strtolower($request->type) == "teacher") {
            $teacher = Teacher::create([
                "firstName" => $request->firstName,
                "lastName" => $request->lastName,
                "phone" => $request->phone,
                "nin" => $request->nin,
                "nic" => $request->nic,
                "current_residence" => $request->current_residence,
                "original_residence" => $request->original_residence,
                "fac_id" => $request->fac_id,
                "dep_id" => $request->dep_id,
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images/users', 'public');
                $path = "storage/" . $path;
                $teacher->image()->create([
                    'image' => $path
                ]);
            }
            $user = $teacher->user()->create([
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "status" => "inactive",
                "type" => $request->type,
            ]);
        } else if (strtolower($request->type) == "student") {
            $student = Student::create([
                "firstName" => $request->firstName,
                "lastName" => $request->lastName,
                "phone" => $request->phone,
                "nin" => $request->nin,
                "nic" => $request->nic,
                "current_residence" => $request->current_residence,
                "original_residence" => $request->original_residence,
                "fac_id" => $request->fac_id,
                "dep_id" => $request->dep_id,
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images/users', 'public');
                $path = "storage/" . $path;
                $student->image()->create([
                    'image' => $path
                ]);

                $user = $student->user()->create([
                    "email" => $request->email,
                    "password" => Hash::make($request->password),
                    "status" => "inactive",
                    "type" => $request->type,
                ]);
            } else {
                return response()->json(['message' => 'Type is incorrect'], 400);
            }



            $token = $user->createToken($user->email . '123')->plainTextToken;
            return response()->json(['token' => $token, 'user' => UserResource::make($user)]);
        }
    }
    public function login(LoginRequest $request)
    {
        $request->merge(['login' => 'yes']);
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken($user->email . '123')->plainTextToken;
                return response()->json(['token' => $token, 'user' => UserResource::make($user)]);
            } else {
                return response()->json(['message' => 'ایمیل یا پسورد شما اشتباه می باشد']);
            }
        } else {
            return response()->json(['message' => 'ایمیل یا پسورد شما اشتبا ه می باشد']);
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function home(Request $request)
    {
        $request->merge(['category_with_books' => 'yes']);
        $categories_num_books = Category::select('name')->withCount('books')->get();
        $categories = Category::all();
        $books = Book::all();
        $hardBooks = Book::where('format', 'hard')->count();
        $pdfBooks = Book::where('format', 'pdf')->count();
        $bothTypeBooks = Book::where('format', 'both')->count();
        $reservableBooks = Book::where('borrow', 'no')->count();
        $borrowableBooks = Book::where('borrow', 'yes')->count();
        $banners = Banner::all();
        $users = User::all()->count();
        return response()->json(
            [
                'banners' => BannerResource::collection($banners),
                'main_information' => [
                    'all_books' => $books->count(),
                    'all_reservable_books' => $reservableBooks,
                    'all_borrowable_books' => $borrowableBooks,
                    'pdf_books' => $pdfBooks,
                    'hard_books' => $hardBooks,
                    'both_type_books' => $bothTypeBooks,
                    'all_registered_users' => $users,
                ],
                'categories_num_books' => $categories_num_books,
                'categories_with_books' => CategoryResource::collection($categories),
            ]
        );
    }

    public function booksByCategoryId(Request $request, string $id)
    {
        $request->merge(['category_with_books' => 'yes']);
        $category = Category::find($id);
        if ($category) {
            return CategoryResource::make($category);
        }

        return response()->json(['message' => "کتگوری وجود ندارد"], Response::HTTP_NOT_FOUND);
    }

    public function BookDetailById(Request $request, string $id)
    {
        $request->merge(['detail' => "yes"]);
        $book = Book::find($id);
        if ($book) {
            return BookResource::make($book);
        }

        return response()->json(['message' => "کتاب وجود ندارد"], Response::HTTP_NOT_FOUND);
    }

    public function reserveBook(Request $request, string $id)
    {

        if (auth()->user()->status == "inactive") {
            return response()->json(['message' => 'شما کاربر فعال نمی باشید']);
        }

        $reserved_books = auth()->user()->reserves;
        foreach ($reserved_books as $reserved_book) {
            if ($reserved_book->book_id == $id) {
                return response()->json(['message' => 'شما این کتاب را دو بار رزرو نمی توانید']);
            }
        }
        $book = Book::find($id);
        if ($book) {
            if ($book->stock->remain > 0) {
                $reserve = Reserve::create([
                    'book_id' => $book->id,
                    'user_id' => auth()->user()->id,
                    'user_type' => auth()->user()->type,
                ]);

                $book->stock->remain = $book->stock->remain - 1;
                $book->stock->save();

                return response()->json(['message' => 'شما موفقانه کتاب را روزو کردید']);
            } else {
                return response()->json(['message' => 'همه جلد های این کتاب رزرو شده است بعدا کوشش کنید'], Response::HTTP_CONFLICT);
            }
        } else {
            return response()->json(['message' => 'کتاب پیدا نشد'], Response::HTTP_NOT_FOUND);
        }
    }
}
