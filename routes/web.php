<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// For admins Login and Logout

// 1st we need to make admin login first to get session for created_by or updated_by

// admin id => 1 , password => admin123123 => if admin id is 1, cannot update will raise error
// admin id => 2 , password => admin456456 => if admin id is 2, cannot create will raise error

Route::view('/', 'login');

Route::prefix('admins')->group(function () {

    // Route::view('/register', 'Register');
    Route::view('/login', 'login');
    // Route::post('/register', [AdminController::class, 'register'])->name('admins.register');
    Route::post('/login', [AdminController::class, 'login'])->name('admins.login');
    Route::get('/logout', [AdminController::class, 'logout'])->name('admins.logout');
});

//For employees, projects CRUD
Route::prefix('employees')->middleware('admin')->group(function () {

    // localization
    Route::get('language/{locale}', [LanguageController::class, 'changelanguage'])->name('set.language');

    // Employees CRUD
    Route::get('', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/search',   [EmployeeController::class, 'search'])->name('employees.search');
    Route::get('/register', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::patch('/{id}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::get('/{id}', [EmployeeController::class, 'show'])->name('employees.view');
    Route::delete('/{id}', [EmployeeController::class, 'destroy'])->name('employees.delete');

    // employees_projects's document download
    Route::get('/documents/{id}/download', [DocumentationController::class, 'downloadDocs'])->name('employees.downloadDocument');
    Route::get('/projects/{id}/show', [DocumentationController::class, 'showProjects'])->name('employees.projectsShow');
    Route::get('/projects/{id}/download', [DocumentationController::class, 'projectIndividualDocDownload'])->name('employees.projects.individualDownload');

    // Export Excel, Pdf routes
    Route::get('/download/Excel', [EmployeeController::class, 'excelExport'])->name('employees.exportExcel');
    Route::get('/download/PDF', [EmployeeController::class, 'pdfExport'])->name('employees.exportPdf');

    // Employee Projects routes
    Route::get('/projects/assign', [ProjectController::class, 'projectForm'])->name('employees.projects'); //1st
    Route::get('/projects/fetchDatas', [ProjectController::class, 'fetchAllDatas'])->name('employees.projects.fetchDatas');
    Route::post('/projects/add', [ProjectController::class, 'EmployeeprojectAdd'])->name('employees.projects.add'); //2nd
    Route::post('/projects/remove', [ProjectController::class, 'EmployeeprojectRemove'])->name('employees.projects.remove'); //2nd
    Route::post('/projects/assign', [ProjectController::class, 'projectAssign'])->name('employees.projectAssign'); //4rd
    Route::get('projects/getNames', [EmployeeController::class, 'getNames']);
});
