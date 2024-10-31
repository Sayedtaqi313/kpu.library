<?php

namespace App\Http\Controllers\Api\backControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUpdateRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Requests\AdminLoginRequest;
use App\Models\Employee;
use App\Models\Permission;



class EmployeeController extends Controller
{

    public function getAllEmployee() {
        $employees = Employee::where('type','employee')->get();
        return EmployeeResource::collection($employees);
    }
    public function createEmployee(StoreEmployeeRequest $request) {
        Employee::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => 'employee'
        ]);
        return response()->json(['message' => 'کارمند موفقانه اضافه شد']);
    }

    public function setPermiision(Request $request) {
        $employeeId = $request->employeeId;
        $employee = Employee::find($request->$employeeId)->first();
        $permissions = $request->permissions;
        $employee->permissions()->sync($permissions);
        return response()->json(['message' => 'دیتا موفقانه بروزرسانی شد']);
    }
    public function login(AdminLoginRequest $request)
    {
        if ($request->type == "employee") {
            $employees = Employee::where("type", "employee")->get();
            if (!$employees->isEmpty()) {

                $employee = Employee::where("email", $request->email)->first();
                if ($employee && Hash::check($request->password, $employee->password)) {
                    auth()->setUser($employee);
                    $token = auth()->user()->createToken($employee->name)->plainTextToken;
                    return response()->json(['token' => $token, 'employee' => EmployeeResource::make($employee)]);
                } else {
                    return response()->json(['message' => 'Credentail is incorrect']);
                }
            } else {
                $tempEmployee = Employee::create([
                    "name" => "admin",
                    "email" => "admin@gmail.com",
                    "password" => Hash::make("password"),
                    "type" => "employee"
                ]);
                
                $permissionsId = Permission::pluck('id')->toArray();
                $tempEmployee->permissions()->attach($permissionsId);
                if ($request->email == $tempEmployee->email && Hash::check($request->password, $tempEmployee->password)) {
                    auth()->setUser($tempEmployee);
                    $token = auth()->user()->createToken($tempEmployee->name)->plainTextToken;
                    return response()->json(['token' => $token, 'employee' =>  EmployeeResource::make($tempEmployee)]);
                } else {
                    return response()->json(['message' => 'Credentail is incorrect']);
                }
            }
        } else if ($request->type == "assistant") {
            $assistant = Employee::where("type", "assistant")->first();
            if ($assistant) {
                if ($request->email == $assistant->email && Hash::check($request->password, $assistant->password)) {
                    $token = auth()->user()->createToken($assistant->name);
                    return response()->json(['token' => $token, '$assistant' =>  EmployeeResource::make($assistant)]);
                } else {
                    return response()->json(['message' => 'Credentail is incorrect']);
                }
            } else {
                $tempAssistant = Employee::create([
                    "name" => "admin",
                    "email" => "admin@gmail.com",
                    "password" => Hash::make("password"),
                    "type" => "assistant"
                ]);

                if ($tempAssistant->email == $request->email && Hash::check($request->password, $tempAssistant->password)) {
                    auth()->setUser($tempAssistant);
                    $token = auth()->user()->createToken($tempAssistant->name);
                    return response()->json(['token' => $token, 'assistant' =>  EmployeeResource::make($tempAssistant)]);
                } else {
                    return response()->json(['message' => 'Credentail is incorrect']);
                }
            }
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->noContent();
    }

    public function update(AdminUpdateRequest $request, Employee $employee)
    {

        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->password = Hash::make($request->password);
        $employee->save();
        return response()->json(['message' => 'Admin updated successfully']);
    }

   


}