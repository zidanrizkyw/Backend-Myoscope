<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Resources\PatientResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\Detection; // Pastikan ini sesuai dengan lokasi model Detection


class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = Patient::all();
    
        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved patients!',
            'status' => 200,
            'data' => $patients
        ], 200);
    }

    public function get_a_patient($id)
    {
        $patient = Patient::find($id);

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient not found',
                'status' => 404,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved patient!',
            'status' => 200,
            'data' => $patient
        ], 200);
    }

    public function register_patient(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female', // Anda dapat menyesuaikan opsi gender sesuai kebutuhan
            'phone' => 'required|string|max:15',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        // Membuat pasien baru
        $patient = Patient::create([
            'name' => $validatedData['name'],
            'gender' => $validatedData['gender'],
            'phone' => $validatedData['phone'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // Mengembalikan respons JSON
        return response()->json([
            'success' => true,
            'message' => 'Patient registered successfully!',
            'status' => 201,
            'data' => $patient
        ], 201);
    }

    public function update_patient(Request $request, $id)
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Memastikan bahwa user yang sedang login adalah patient atau admin
        if (!$user instanceof Patient && !$user instanceof Admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'status' => 403,
            ], 403);
        }

        // Mendapatkan data pasien yang akan diupdate
        $patient = Patient::find($id);

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient not found',
                'status' => 404,
            ], 404);
        }

        // Jika user yang login adalah patient, memastikan bahwa ID pasien yang diupdate adalah ID dari pasien yang sedang login
        if ($user instanceof Patient && $user->id != $patient->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'status' => 403,
            ], 403);
        }

        // Validasi input
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'gender' => 'sometimes|required|string',
            'phone' => 'sometimes|required|string|max:15',
            'email' => 'sometimes|required|email|unique:patients,email,'.$patient->id,
            'password' => 'sometimes|required|string|min:8',
        ]);

        // Mengupdate data pasien
        if (isset($validated['password'])) {
            // Hash password sebelum menyimpan
            $validated['password'] = Hash::make($validated['password']);
        }

        $patient->update($validated);

        // Mengembalikan respons JSON
        return response()->json([
            'success' => true,
            'message' => 'Patient updated successfully!',
            'status' => 200,
            'data' => $patient,
        ], 200);
    }

    public function delete_patient($id)
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

    // Mendapatkan data pasien yang akan dihapus
    $patient = Patient::find($id);

    if (!$patient) {
        return response()->json([
            'success' => false,
            'message' => 'Patient not found',
            'status' => 404,
        ], 404);
    }

    // Menghapus semua deteksi terkait sebelum menghapus pasien
    Detection::where('patient_id', $id)->delete();

    // Menghapus data pasien
    $patient->delete();

    // Mengembalikan respons JSON
    return response()->json([
        'success' => true,
        'message' => 'Patient deleted successfully!',
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
    //  * @param  \App\Models\Patient  $patient
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(Patient $patient)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  \App\Models\Patient  $patient
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit(Patient $patient)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \App\Models\Patient  $patient
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, Patient $patient)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  \App\Models\Patient  $patient
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(Patient $patient)
    // {
    //     //
    // }

    
}
