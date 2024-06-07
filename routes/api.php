<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CafeController;
use App\Http\Controllers\CafeExpenseController;
use App\Http\Controllers\SalaryMonthController;
use App\Http\Controllers\UserQualificationController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseTypeController;
use App\Http\Controllers\UserExperienceController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\UserCertificationController;
use App\Http\Controllers\FundTransferController;
use App\Http\Controllers\ImportCsvController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\QualificationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserBonusController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLoanController;
use App\Http\Controllers\WorkFromHomeController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\LeaveManagementController;
use App\Http\Controllers\LeaveApplicationController;
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
// forgot password
Route::post('forgotPassword', [ForgotPasswordController::class, 'create']);
Route::post('resetPassword', [ForgotPasswordController::class, 'update'])->name('password.reset');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    Route::get('transection', [TransactionController::class, 'index']);

    Route::post('kharcha/store', [ExpenseController::class, 'store']);
    Route::get('user/accounts', [UserController::class, 'accounts']);
    Route::get('user/expenses', [UserController::class, 'expenses']);
    Route::get('user/salary-detail', [UserController::class, 'salaryDetail']);
    Route::post('user/connected', [UserController::class, 'userConnected']);
    Route::get('user/transections', [UserController::class, 'transections']);
    Route::get('user/export', [UserController::class, 'userExport']);
    Route::get('user/csv-export/{id}', [UserController::class, 'csvExport']);
    Route::post('user/import', [UserController::class, 'importUser']);
    Route::get('fund_transfer/create', [FundTransferController::class, 'create']);
    Route::post('/import', [UserController::class, 'import']);
    Route::get('/employee-wishes', [UserController::class, 'employeeWishes']);

    Route::resource('expense',      ExpenseController::class);
    Route::resource('role',         RoleController::class);
    Route::resource('permission',   PermissionController::class);

    Route::resource('user',         UserController::class);
    Route::get('user-list', [UserController::class,'list']);
    Route::resource('bank',         BankController::class);
    Route::get('bank-list',         [BankController::class,'list']);
    Route::resource('account',      AccountController::class);
    Route::resource('expense_type', ExpenseTypeController::class);
    Route::get('expense_type_list', [ExpenseTypeController::class,'list']);
    Route::get('expense_filter_list', [ExpenseTypeController::class,'filter']);
    Route::resource('fund_transfer', FundTransferController::class);
    Route::apiResource('csv',          ImportCsvController::class);
    Route::post('salary-generate',         [ImportCsvController::class,'salaryGenerate']);
    // Route::get('expense', [ExpenseController::class, 'index']);
    // Route::get('expense/{expense}', [ExpenseController::class, 'show']);

    Route::apiResource('department', DepartmentController::class);
    Route::get('department-list', [DepartmentController::class,'list']);
    Route::apiResource('designation', DesignationController::class);
    Route::apiResource('qualification', QualificationController::class);


    Route::apiResource('user-loan', UserLoanController::class);
    Route::controller(UserBonusController::class)->prefix('user-bonus')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::patch('/{userBonus}', 'update');
        Route::get('/{userBonus}', 'show');
        Route::delete('/{userBonus}', 'destroy');
    });
    Route::resource('user-bonus', UserBonusController::class);
    Route::apiResource('cafe', CafeController::class);
    Route::get('cafe-list', [CafeController::class,'list']);
    Route::apiResource('cafe-expense', CafeExpenseController::class);
    Route::apiResource('fine', FineController::class);
    Route::apiResource('installment', InstallmentController::class);
    Route::apiResource('wfh', WorkFromHomeController::class);
    Route::apiResource('salary-month', SalaryMonthController::class);
    Route::get('salary-month-active', [SalaryMonthController::class,'active']);

    Route::apiResource('user_qualification', UserQualificationController::class);
    Route::apiResource('user_certification', UserCertificationController::class);
    Route::apiResource('user_experience', UserExperienceController::class);


    Route::resource('leave-type', LeaveTypeController::class);
    Route::get('leave-type-list', [LeaveTypeController::class,'list']);

    Route::resource('leave-management', LeaveManagementController::class);
    Route::resource('leave-application', LeaveApplicationController::class);


});
