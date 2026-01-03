<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectPhasePaymentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\WorkerPositionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\SuperAdminController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', function () {
    return view('auth.register');
})->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes with tenant middleware
Route::middleware(['auth', 'tenant.data'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');

    // Projects CRUD
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    // Project Phase Payments
    Route::get('/projects/{project}/phase-payments/{phase}/create', [ProjectPhasePaymentController::class, 'create'])->name('projects.phase-payments.create');
    Route::post('/projects/{project}/phase-payments', [ProjectPhasePaymentController::class, 'store'])->name('projects.phase-payments.store');
    Route::get('/projects/{project}/phase-payments/{payment}/edit', [ProjectPhasePaymentController::class, 'edit'])->name('projects.phase-payments.edit');
    Route::put('/projects/{project}/phase-payments/{payment}', [ProjectPhasePaymentController::class, 'update'])->name('projects.phase-payments.update');
    Route::delete('/projects/{project}/phase-payments/{payment}', [ProjectPhasePaymentController::class, 'destroy'])->name('projects.phase-payments.destroy');

    // Tasks CRUD (Project-based tasks)
    Route::get('/projects/{project}/tasks', [TaskController::class, 'index'])->name('projects.tasks.index');
    Route::get('/projects/{project}/tasks/create', [TaskController::class, 'create'])->name('projects.tasks.create');
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])->name('projects.tasks.store');
    Route::get('/projects/{project}/tasks/{task}/edit', [TaskController::class, 'edit'])->name('projects.tasks.edit');
    Route::put('/projects/{project}/tasks/{task}', [TaskController::class, 'update'])->name('projects.tasks.update');
    Route::delete('/projects/{project}/tasks/{task}', [TaskController::class, 'destroy'])->name('projects.tasks.destroy');

    // Global Tasks (all tasks)
    Route::get('/tasks', [TaskController::class, 'globalIndex'])->name('tasks.index');
    Route::post('/tasks/store-from-global', [TaskController::class, 'storeFromGlobal'])->name('tasks.store-from-global');

    // Clients CRUD
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');

    // Workers CRUD
    Route::get('/workers', [WorkerController::class, 'index'])->name('workers.index');
    Route::get('/workers/create', [WorkerController::class, 'create'])->name('workers.create');
    Route::post('/workers', [WorkerController::class, 'store'])->name('workers.store');
    Route::get('/workers/{worker}', [WorkerController::class, 'show'])->name('workers.show');
    Route::get('/workers/{worker}/edit', [WorkerController::class, 'edit'])->name('workers.edit');
    Route::put('/workers/{worker}', [WorkerController::class, 'update'])->name('workers.update');
    Route::delete('/workers/{worker}', [WorkerController::class, 'destroy'])->name('workers.destroy');

    // Worker Positions CRUD
    Route::get('/positions', [WorkerPositionController::class, 'index'])->name('positions.index');
    Route::get('/positions/create', [WorkerPositionController::class, 'create'])->name('positions.create');
    Route::post('/positions', [WorkerPositionController::class, 'store'])->name('positions.store');
    Route::get('/positions/{position}', [WorkerPositionController::class, 'show'])->name('positions.show');
    Route::get('/positions/{position}/edit', [WorkerPositionController::class, 'edit'])->name('positions.edit');
    Route::put('/positions/{position}', [WorkerPositionController::class, 'update'])->name('positions.update');
    Route::delete('/positions/{position}', [WorkerPositionController::class, 'destroy'])->name('positions.destroy');

    // Payments CRUD
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');

    // Revenues CRUD
    Route::get('/revenues', [IncomeController::class, 'index'])->name('revenues.index');
    Route::get('/revenues/create', [IncomeController::class, 'create'])->name('revenues.create');
    Route::post('/revenues', [IncomeController::class, 'store'])->name('revenues.store');
    Route::get('/revenues/{income}', [IncomeController::class, 'show'])->name('revenues.show');
    Route::get('/revenues/{income}/edit', [IncomeController::class, 'edit'])->name('revenues.edit');
    Route::put('/revenues/{income}', [IncomeController::class, 'update'])->name('revenues.update');
    Route::delete('/revenues/{income}', [IncomeController::class, 'destroy'])->name('revenues.destroy');

    // Expenses CRUD
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');
    Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    // Calendar API endpoints
    Route::get('/api/calendar/daily-summary', [DashboardController::class, 'calendarDailySummary'])->name('api.calendar.daily-summary');
    Route::get('/api/calendar/month-data', [DashboardController::class, 'calendarMonthData'])->name('api.calendar.month-data');

    // Notifications
    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationsController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('/notifications/{id}/read', [NotificationsController::class, 'markRead'])->name('notifications.read');
    Route::delete('/notifications/{id}', [NotificationsController::class, 'destroy'])->name('notifications.destroy');
});

// Super Admin Routes
Route::middleware(['auth', 'tenant.data'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');

    // User Management
    Route::get('/users', [SuperAdminController::class, 'users'])->name('users.index');
    Route::get('/users/{user}', [SuperAdminController::class, 'showUser'])->name('users.show');
    Route::put('/users/{user}/roles', [SuperAdminController::class, 'updateUserRoles'])->name('users.update-roles');
    Route::post('/assign-tenant', [SuperAdminController::class, 'assignTenantToUser'])->name('assign-tenant');
    Route::post('/remove-tenant', [SuperAdminController::class, 'removeTenantFromUser'])->name('remove-tenant');

    // Tenant Management
    Route::get('/tenants', [SuperAdminController::class, 'tenants'])->name('tenants.index');
    Route::get('/tenants/{tenant}', [SuperAdminController::class, 'showTenant'])->name('tenants.show');

    // Role Management
    Route::get('/roles', [SuperAdminController::class, 'roles'])->name('roles.index');
    Route::get('/roles/{role}', [SuperAdminController::class, 'showRole'])->name('roles.show');
    Route::put('/roles/{role}/permissions', [SuperAdminController::class, 'updateRolePermissions'])->name('roles.update-permissions');

    // Permission Management
    Route::get('/permissions', [SuperAdminController::class, 'permissions'])->name('permissions.index');

    // Audit & Settings
    Route::get('/audit', [SuperAdminController::class, 'audit'])->name('audit');
    Route::get('/settings', [SuperAdminController::class, 'settings'])->name('settings');
    Route::put('/settings', [SuperAdminController::class, 'updateSettings'])->name('settings.update');
});
