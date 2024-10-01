<?php

use App\Http\Controllers\TaskManagementController;
use Illuminate\Support\Facades\Route;

Route::controller(TaskManagementController::class)->group(function(){
    Route::get('', 'index')->name('index');
    Route::post('store', 'store')->name('store');
    Route::post('update/{task_id}', 'update')->name('update');
    Route::post('mark-as-completed/{task_id}', 'markAsCompleted')->name('mark-as-completed');
    Route::delete('delete/{task_id}', 'destroy')->name('delete');
});
