<?php

use App\Http\Controllers\ActionPlanController;
use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExecutiveBriefController;
use App\Http\Controllers\StrategicInitiativeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\Auth\EdRetailLoginController;

// Login khusus tim ED Retail
Route::get('/penugasan/login', [EdRetailLoginController::class, 'showLoginForm'])
    ->name('penugasan.login');

Route::post('/penugasan/login', [EdRetailLoginController::class, 'login'])
    ->name('penugasan.login.submit');

Route::post('/penugasan/logout', [EdRetailLoginController::class, 'logout'])
    ->name('penugasan.logout');

// Tambahkan ini
Route::get('/login', function () {
    return redirect()->route('penugasan.login');
})->name('login');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/strategic-initiative', [StrategicInitiativeController::class, 'index'])->name('strategic-initiative.index');

Route::get('/strategic-initiative/create', [StrategicInitiativeController::class, 'create'])
    ->name('strategic-initiative.create');

Route::post('/strategic-initiative', [StrategicInitiativeController::class, 'store'])
    ->name('strategic-initiative.store');

Route::get('/strategic-initiative/{strategicInitiative}', [StrategicInitiativeController::class, 'show'])
    ->name('strategic-initiative.show');

Route::get('/strategic-initiative/{strategicInitiative}/edit', [StrategicInitiativeController::class, 'edit'])
    ->name('strategic-initiative.edit');

Route::put('/strategic-initiative/{strategicInitiative}', [StrategicInitiativeController::class, 'update'])
    ->name('strategic-initiative.update');

Route::delete('/strategic-initiative/{strategicInitiative}', [StrategicInitiativeController::class, 'destroy'])
    ->name('strategic-initiative.destroy');

Route::get('/strategic-initiative/{strategicInitiative}/action-plan/create', [ActionPlanController::class, 'create'])
    ->name('action-plan.create');

Route::post('/strategic-initiative/{strategicInitiative}/action-plan', [ActionPlanController::class, 'store'])
    ->name('action-plan.store');

Route::get('/strategic-initiative/{strategicInitiative}/action-plan/{actionPlan}/edit', [ActionPlanController::class, 'edit'])
    ->name('action-plan.edit');

Route::put('/strategic-initiative/{strategicInitiative}/action-plan/{actionPlan}', [ActionPlanController::class, 'update'])
    ->name('action-plan.update');

Route::delete('/strategic-initiative/{strategicInitiative}/action-plan/{actionPlan}', [ActionPlanController::class, 'destroy'])
    ->name('action-plan.destroy');

Route::get('/administration', [AdministrationController::class, 'index'])->name('administration.index');

Route::post('/administration/evp', [AdministrationController::class, 'storeEvp'])->name('administration.evp.store');
Route::delete('/administration/evp/{evp}', [AdministrationController::class, 'destroyEvp'])->name('administration.evp.destroy');

Route::post('/administration/division', [AdministrationController::class, 'storeDivision'])->name('administration.division.store');
Route::delete('/administration/division/{division}', [AdministrationController::class, 'destroyDivision'])->name('administration.division.destroy');

Route::post('/administration/meeting', [AdministrationController::class, 'storeMeeting'])->name('administration.meeting.store');
Route::delete('/administration/meeting/{meeting}', [AdministrationController::class, 'destroyMeeting'])->name('administration.meeting.destroy');

Route::post('/administration/user', [AdministrationController::class, 'storeUser'])->name('administration.user.store');
Route::delete('/administration/user/{user}', [AdministrationController::class, 'destroyUser'])->name('administration.user.destroy');

Route::post('/administration/trash/strategic-initiative/{id}/restore', [AdministrationController::class, 'restoreInitiative'])
    ->name('administration.trash.initiative.restore');
Route::delete('/administration/trash/strategic-initiative/{id}/force', [AdministrationController::class, 'forceDeleteInitiative'])
    ->name('administration.trash.initiative.force');

Route::post('/administration/trash/action-plan/{id}/restore', [AdministrationController::class, 'restoreActionPlan'])
    ->name('administration.trash.action-plan.restore');
Route::delete('/administration/trash/action-plan/{id}/force', [AdministrationController::class, 'forceDeleteActionPlan'])
    ->name('administration.trash.action-plan.force');

Route::get('/executive-brief', [ExecutiveBriefController::class, 'index'])->name('executive-brief.index');
Route::get('/executive-brief/{meeting}/pdf', [ExecutiveBriefController::class, 'pdf'])->name('executive-brief.pdf');
