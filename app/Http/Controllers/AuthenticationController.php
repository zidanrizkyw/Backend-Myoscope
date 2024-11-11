<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Try to login as a patient
        $patient = Patient::where('email', $request->email)->first();
        if ($patient && Hash::check($request->password, $patient->password)) {
            return response()->json([
                'token' => $patient->createToken('patient login')->plainTextToken,
                'role' => 'patient',
                'data' => $patient
            ]);
        }

        // Try to login as an admin
        $admin = Admin::where('email', $request->email)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            return response()->json([
                'token' => $admin->createToken('admin login')->plainTextToken,
                'role' => 'admin',
                'data' => $admin
            ]);
        }

        // Try to login as a doctor
        $doctor = Doctor::where('email', $request->email)->first();
        if ($doctor && Hash::check($request->password, $doctor->password)) {
            return response()->json([
                'token' => $doctor->createToken('doctor login')->plainTextToken,
                'role' => 'doctor',
                'data' => $doctor
            ]);
        }

        // If none of the above conditions are met, return an error
        throw ValidationException::withMessages([
            'email' => ['Email or password is incorrect.'],
        ]);
    }


    public function logout(Request $request)
    {
         // Mendapatkan nama guard default
         $guardName = Auth::getDefaultDriver();

         // Menghapus token akses saat ini
         Auth::guard($guardName)->user()->tokens()->delete();
 
         return response()->json([
             'message' => ucfirst($guardName) . ' logged out successfully'
         ]);
    }
}
