<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\HobbyController;
use App\Http\Controllers\API\Auth\LoginController;

Route::middleware('auth:sanctum','role:Super Admin')->group(function () {
    // List all users
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    
    // Show a single user by ID
    Route::get('users/{id}', [UserController::class, 'show'])->name('users.show');
    
    // Create a new user
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    
    // Update a user by ID
    Route::post('users/{id}', [UserController::class, 'update'])->name('users.update');
    
    // Delete a user by ID
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    //filter users by hobby 
    Route::get('/users/filter-by-hobby/{hobbyId}', [UserController::class, 'filterByHobby']);
});

Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/hobbies', [HobbyController::class, 'index']); 
    Route::post('/hobbies/update', [HobbyController::class, 'updateHobbies']); 
});




