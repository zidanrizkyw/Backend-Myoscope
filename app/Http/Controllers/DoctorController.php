<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doctors = Doctor::all();
    
        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved patients!',
            'status' => 200,
            'data' => $doctors
        ], 200);
    }

    public function add_doctor(Request $request)
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Memastikan bahwa user yang sedang login adalah admin
        if (!$user instanceof Admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'status' => 403,
            ], 403);
        }

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female',
            'specialization' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        // Hash password sebelum menyimpan
        $validated['password'] = Hash::make($validated['password']);

        // Menambahkan data doctor baru
        $doctor = Doctor::create($validated);

        // Mengembalikan respons JSON
        return response()->json([
            'success' => true,
            'message' => 'Doctor added successfully!',
            'status' => 201,
            'data' => $doctor,
        ], 201);
    }

    public function update_doctor(Request $request, $doctor_id)
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Mendapatkan data dokter yang akan diupdate
        $doctor = Doctor::find($doctor_id);

        // Memastikan dokter yang diupdate ada
        if (!$doctor) {
            return response()->json([
                'success' => false,
                'message' => 'Doctor not found',
                'status' => 404,
            ], 404);
        }

        // Memastikan bahwa user yang sedang login adalah admin atau dokter yang sesuai
        if (!$user instanceof Admin && $user->id !== $doctor->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'status' => 403,
            ], 403);
        }

        // Validasi input
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'gender' => 'required|string|in:male,female',
            'specialization' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:15',
            'email' => 'sometimes|required|email',
            'password' => 'sometimes|required|string|min:8',
        ]);

        // Hash password jika ada dalam input
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        // Mengupdate data dokter
        $doctor->update($validated);

        // Mengembalikan respons JSON
        return response()->json([
            'success' => true,
            'message' => 'Doctor updated successfully!',
            'status' => 200,
            'data' => $doctor,
        ], 200);
    }

    public function delete_doctor($doctor_id)
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Memastikan bahwa user yang sedang login adalah admin
        if (!$user instanceof Admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'status' => 403,
            ], 403);
        }

        // Mendapatkan data dokter yang akan dihapus
        $doctor = Doctor::find($doctor_id);

        // Memastikan dokter yang akan dihapus ada
        if (!$doctor) {
            return response()->json([
                'success' => false,
                'message' => 'Doctor not found',
                'status' => 404,
            ], 404);
        }

        // Menghapus dokter
        $doctor->delete();

        // Mengembalikan respons JSON
        return response()->json([
            'success' => true,
            'message' => 'Doctor deleted successfully!',
            'status' => 200,
        ], 200);
    }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     //
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(Request $request)
    // {
    //     //
    // }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  \App\Models\Doctor  $doctor
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(Doctor $doctor)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  \App\Models\Doctor  $doctor
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit(Doctor $doctor)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \App\Models\Doctor  $doctor
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, Doctor $doctor)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  \App\Models\Doctor  $doctor
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(Doctor $doctor)
    // {
    //     //
    // }
}
