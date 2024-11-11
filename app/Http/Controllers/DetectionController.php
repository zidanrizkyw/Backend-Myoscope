<?php

namespace App\Http\Controllers;

use App\Models\Detection;
use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


use Illuminate\Http\Request;

class DetectionController extends Controller
{
    public function index()
    {
        $detections = Detection::all();
    
        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved patients!',
            'status' => 200,
            'data' => $detections
        ], 200);
    }

    public function get_all_detections_by_patient_id($patient_id)
    {
        // Memastikan bahwa pasien dengan ID yang diberikan ada
        $patient = Patient::find($patient_id);

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient not found',
                'status' => 404,
            ], 404);
        }

        // Mengambil semua deteksi berdasarkan patient_id
        $detections = Detection::where('patient_id', $patient_id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved detections for patient!',
            'status' => 200,
            'data' => $detections
        ], 200);
    }

    public function get_a_single_detection_by_patient_id($patient_id, $detection_id)
    {

        // Temukan pasien berdasarkan ID
        $patient = Patient::find($patient_id);

         if (!$patient) {

            \Log::error('Patient not found with ID: ' . $patient_id);
            return response()->json([
                'success' => false,
                'message' => 'Patient not found',
                'status' => 404,
            ], 404);
        }

        // Temukan deteksi berdasarkan ID pasien dan ID deteksi
        $detection = DB::table('detections')
            ->where('patient_id', $patient_id)
            ->where('id', $detection_id)
            ->first();

        if (!$detection) {
            \Log::error('Detection not found with ID: ' . $detection_id . ' for Patient ID: ' . $patient_id);

            return response()->json([

                'success' => false,
                'message' => 'Detection not found',
                'status' => 404,
            ], 404);
        }

        // Log jika ditemukan
        \Log::info('Detection found: ' . json_encode($detection));

        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved detection for patient!',
            'status' => 200,
            'detection' => $detection
        ], 200);
    }

    public function add_detection(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'condition' => 'required',
            'heartwave' => 'required|file|mimes:wav',
        ]);

        // Mendapatkan pengguna yang saat ini diautentikasi
        $user = Auth::user();

        // Pastikan pengguna tersebut adalah pasien
        if (!$user || !$user instanceof Patient) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized or user is not a patient',
                'status' => 401,
            ], 401);
        }

        // Menyimpan file wav
        if ($request->hasFile('heartwave')) {
            $file = $request->file('heartwave');
            $fileName = $file->getClientOriginalName(); // Mengambil nama asli file
            $path = $file->storeAs('heartwaves', $fileName, 'public'); // Menyimpan file dengan nama asli
            $url = Storage::url($path);
        }

        // Buat deteksi baru
        $detection = Detection::create([
            'condition' => $request->condition,
            'heartwave' => $url, // Simpan URL file
            'patient_id' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Detection data successfully created',
            'status' => 201,
            'data' => $detection
        ], 201);
    }

    public function update_detection(Request $request, $patient_id, $detection_id)
    {
        $validated = $request->validate([
            'notes' => 'required',
            'verified' => 'required',
        ]);

        // Mendapatkan pengguna yang saat ini diautentikasi
        $user = Auth::user();

        // Pastikan pengguna adalah dokter
        if (!$user || !$user instanceof Doctor) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized or user is not a doctor',
                'status' => 401,
            ], 401);
        }

        // Mendapatkan pasien
        $patient = Patient::find($patient_id);
        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient not found',
                'status' => 404,
            ], 404);
        }

        // Mendapatkan detection
        $detection = Detection::where('patient_id', $patient_id)->where('id', $detection_id)->first();
        if (!$detection) {
            return response()->json([
                'success' => false,
                'message' => 'Detection not found',
                'status' => 404,
            ], 404);
        }
        // Update detection
        $detection->notes = $request->notes;
        $detection->verified = $request->verified;
        $detection->save();

        return response()->json([
            'success' => true,
            'message' => 'Detection updated successfully',
            'status' => 200,
            'detection' => $detection,
        ], 200);
    }

    public function delete_detection($patient_id, $detection_id)
    {
        // Mendapatkan pengguna yang saat ini diautentikasi
        $user = Auth::user();

        // Pastikan pengguna adalah pasien atau admin
        if (!$user || (!$user instanceof Patient && !$user instanceof Admin)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized or user is not a patient or admin',
                'status' => 401,
            ], 401);
        }

        // Jika pengguna adalah pasien, pastikan ID pasien sesuai
        if ($user instanceof Patient && $user->id != $patient_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Patients can only delete their own detections',
                'status' => 401,
            ], 401);
        }

        // Mendapatkan detection
        $detection = Detection::where('patient_id', $patient_id)->where('id', $detection_id)->first();
        if (!$detection) {
            return response()->json([
                'success' => false,
                'message' => 'Detection not found',
                'status' => 404,
            ], 404);
        }

        // Hapus detection
        $detection->delete();

        return response()->json([
            'success' => true,
            'message' => 'Detection deleted successfully',
            'status' => 200,
        ], 200);
    }

    
}
