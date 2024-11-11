<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DetectionController;
use App\Http\Controllers\AuthenticationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

// api login dan logout
Route::post('/login', [AuthenticationController::class, 'login']);
Route::get('/logout', [AuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);

// api Patient
Route::get('/patients',[PatientController::class, 'index'])->middleware(['auth:sanctum']); 
Route::get('/patients/{id}',[PatientController::class, 'get_a_patient'])->middleware(['auth:sanctum']);
Route::post('/register', [PatientController::class, 'register_patient']);
Route::patch('/update-patient/{id}', [PatientController::class, 'update_patient'])->middleware(['auth:sanctum']);
Route::delete('/delete-patient/{id}', [PatientController::class, 'delete_patient'])->middleware(['auth:sanctum']);



//api doctors
Route::get('/doctors',[DoctorController::class, 'index'])->middleware(['auth:sanctum']);
Route::post('/add-doctor',[DoctorController::class, 'add_doctor'])->middleware(['auth:sanctum']);
Route::patch('/update-doctor/{id}',[DoctorController::class, 'update_doctor'])->middleware(['auth:sanctum']);
Route::delete('/delete-doctor/{id}',[DoctorController::class, 'delete_doctor'])->middleware(['auth:sanctum']);

//api detections
Route::get('/detections',[DetectionController::class, 'index'])->middleware(['auth:sanctum']);
Route::get('/detections/{patient_id}', [DetectionController::class, 'get_all_detections_by_patient_id'])->middleware(['auth:sanctum']);
Route::get('/detection/{patient_id}/{detection_id}', [DetectionController::class, 'get_a_single_detection_by_patient_id'])->middleware(['auth:sanctum']);
Route::post('/add-detection', [DetectionController::class, 'add_detection'])->middleware(['auth:sanctum']);
Route::patch('/update-detection/{patient_id}/{detection_id}', [DetectionController::class, 'update_detection'])->middleware(['auth:sanctum']);
Route::delete('/delete-detection/{patient_id}/{detection_id}', [DetectionController::class, 'delete_detection'])->middleware(['auth:sanctum']);

// api Admin
Route::get('/admins',[AdminController::class, 'index']);