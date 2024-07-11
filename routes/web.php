<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Project\ProjectController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('project', function () {
        return view('project.dashboard');
    })->name('project');
    Route::get('project/create', function () {
        return view('project.create');
    })->name('project/create');
    Route::get('project/{id}', [ProjectController::class, 'show'], function ($id) {
        return view('project.show', ['id' => $id]);
    })->name('project/{id}');
    Route::get('project/{id}/addprojectmember', [ProjectController::class, 'addMember'], function ($id) {
        return view('project.add-project-member', ['id' => $id]);
    })->name('project/{id}/addprojectmember');
    Route::get('project/{id}/manage', [ProjectController::class, 'projectManager'], function ($id) {
        return view('project.project', ['id' => $id]);
    })->name('project/{id}/manage');
    Route::get('project/{id}/createvm', [ProjectController::class, 'createVm'], function ($id) {
        return view('project.vm.create', ['id' => $id]);
    })->name('project/{id}/createvm');
});

Route::middleware(['auth:sanctum', 'isAdmin', config('jetstream.auth_session'), 'verified'])->group(function () {

    Route::get('manage/{id}', function ($id) {
        return view('manage.show', ['id' => $id]);
    })->name('manage');
    Route::get('manage/{id}/location', function ($id) {
        return view('manage.location.show', ['id' => $id]);
    })->name('location');
    Route::get('manage/{id}/environment', function ($id) {
        return view('manage.environment.show', ['id' => $id]);
    })->name('environment');
    Route::get('manage/{id}/tier', function ($id) {
        return view('manage.tier.show', ['id' => $id]);
    })->name('tier');
    Route::get('manage/{id}/infrapolicy', function ($id) {
        return view('manage.infra-policy.show', ['id' => $id]);
    })->name('infrapolicy');
    Route::get('manage/{id}/vmpolicy', function ($id) {
        return view('manage.vm-config.show', ['id' => $id]);
    })->name('vmconfig');
    Route::get('manage/{id}/storage', function ($id) {
        return view('manage.storage.show', ['id' => $id]);
    })->name('storage');
    Route::get('manage/{id}/os', function ($id) {
        return view('manage.os.show', ['id' => $id]);
    })->name('os');
    Route::get('manage/{id}/appservice', function ($id) {
        return view('manage.app-service.show', ['id' => $id]);
    })->name('appservice');
    
});

require_once __DIR__ . '/jetstream.php';