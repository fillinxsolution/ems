<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CafeController;
use App\Http\Controllers\CafeExpenseController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseTypeController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\FundTransferController;
use App\Http\Controllers\ImportCsvController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\QualificationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLoanController;
use App\Http\Controllers\WorkFromHomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('transection', [TransactionController::class, 'index']);

    Route::post('kharcha/store', [ExpenseController::class, 'store']);
    Route::get('user/accounts', [UserController::class, 'accounts']);
    Route::get('user/expenses', [UserController::class, 'expenses']);
    Route::get('user/transections', [UserController::class, 'transections']);
    Route::get('fund_transfer/create', [FundTransferController::class, 'create']);
    Route::post('/import', [UserController::class, 'import']);

    Route::resource('expense',      ExpenseController::class);
    Route::resource('role',         RoleController::class);
    Route::resource('permission',   PermissionController::class);

    Route::resource('user',         UserController::class);
    Route::resource('bank',         BankController::class);
    Route::resource('account',      AccountController::class);
    Route::resource('expense_type', ExpenseTypeController::class);
    Route::resource('fund_transfer', FundTransferController::class);
    Route::apiResource('csv',          ImportCsvController::class);
    // Route::get('expense', [ExpenseController::class, 'index']);
    // Route::get('expense/{expense}', [ExpenseController::class, 'show']);

    Route::apiResource('department', DepartmentController::class);
    Route::apiResource('designation', DesignationController::class);
    Route::apiResource('qualification', QualificationController::class);


    Route::apiResource('user-loan', UserLoanController::class);
    Route::apiResource('cafe', CafeController::class);
    Route::apiResource('cafe-expense', CafeExpenseController::class);
    Route::apiResource('fine', FineController::class);
    Route::apiResource('installment', InstallmentController::class);
    Route::apiResource('wfh', WorkFromHomeController::class);
});
