<?php

namespace App\Http\Controllers\Api\backControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUpdateRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Requests\AdminLoginRequest;


class EmployeeController extends Controller
{
    public function login(AdminLoginRequest $request)
    {
        if ($request->type == "employee") {
            $employees = Employee::where("type", "employee")->get();
            if (!$employees->isEmpty()) {

                $employee = Employee::where("email", $request->email)->first();
                if ($employee && Hash::check($request->password,$employee->password)) {
                    auth()->setUser($employee);
                    $token = auth()->user()->createToken($employee->name)->plainTextToken;
                    return response()->json(['token' => $token, 'employee' => $employee]);
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

                if ($request->email == $tempEmployee->email && Hash::check($request->password,$tempEmployee->password)) {
                    auth()->setUser($tempEmployee);
                    $token = auth()->user()->createToken($tempEmployee->name)->plainTextToken;
                    return response()->json(['token' => $token, 'employee' => $tempEmployee]);
                } else {
                    return response()->json(['message' => 'Credentail is incorrect']);
                }
            }
        } else if ($request->type == "assistant") {
            $assistant = Employee::where("type", "assistant")->first();
            if ($assistant) {
                if ($request->email == $assistant->email && Hash::check($request->password,$assistant->password)) {
                    $token = auth()->user()->createToken($assistant->name);
                    return response()->json(['token' => $token, 'assi$assistant' => $assistant]);
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

                if ($tempAssistant->email == $request->email && Hash::check($request->password,$tempAssistant->password)) {
                    auth()->setUser($tempAssistant);
                    $token = auth()->user()->createToken($tempAssistant->name);
                    return response()->json(['token' => $token, 'assistant' => $tempAssistant]);
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

    public function update(AdminUpdateRequest $request, Employee $admin)
    {

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->save();
        return response()->json(['message' => 'Admin updated successfully']);
    }


}