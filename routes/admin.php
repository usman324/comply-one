<?php


use App\Actions\Admin\Customer\DeleteCustomerAction;
use App\Actions\Admin\Customer\GetCustomerAction;
use App\Actions\Admin\Customer\GetCustomerListAction;
use App\Actions\Admin\Customer\StoreCustomerAction;
use App\Actions\Admin\Customer\UpdateCustomerAction;
use App\Actions\Auth\GetLoginAction;
use App\Actions\Auth\LoginAction;
use App\Actions\Admin\Dashboard\DashboardAction;
use App\Actions\Admin\Dashboard\SelectRecordAction;
use App\Actions\Admin\GenerelSetting\GetGenerelSettingAction;
use App\Actions\Admin\GenerelSetting\UpdateGenerelSettingAction;
use App\Actions\Admin\Profile\GetProfileAction;
use App\Actions\Admin\Profile\UpdateProfileAction;
use App\Actions\Admin\Role\DeleteRoleAction;
use App\Actions\Admin\User\DeleteUserAction;
use App\Actions\Admin\Role\GetRoleAction;
use App\Actions\Admin\Role\GetRoleListAction;
use App\Actions\Admin\User\GetUserAction;
use App\Actions\Admin\User\GetUserListAction;
use App\Actions\Admin\Role\StoreRoleAction;
use App\Actions\Admin\User\StoreUserAction;
use App\Actions\Admin\Role\UpdateRoleAction;
use App\Actions\Admin\User\ActivityUserListAction;
use App\Actions\Admin\User\UpdateUserAction;
use App\Actions\Admin\Workspace\DeleteWorkspaceAction;
use App\Actions\Admin\Workspace\GetWorkspaceAction;
use App\Actions\Admin\Workspace\GetWorkspaceListAction;
use App\Actions\Admin\Workspace\StoreWorkspaceAction;
use App\Actions\Admin\Workspace\UpdateWorkspaceAction;
use App\Actions\Auth\LogoutAction;
use App\Http\Controllers\QuestionnaireController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
// Auth::routes();
Route::group(['middleware' => ['guest']], function () {
    Route::get('/login', GetLoginAction::class)->name('login');
    Route::post('login', LoginAction::class);
});
Route::post('logout', LogoutAction::class)->name('logout');
// Route::get('forgot-password', [DashboardController::class, 'forgotPassword']);
// Route::post('forgot-password', [DashboardController::class, 'postForgotPassword']);
// Route::get('reset-password/{token}/{id}', [DashboardController::class, 'resetPassword']);
// Route::post('reset-password', [DashboardController::class, 'postResetPassword']);
Route::middleware('auth')->group(function () {
    Route::get('/', DashboardAction::class);
    Route::get('select-record', SelectRecordAction::class);
    Route::get('profile', GetProfileAction::class);
    Route::post('profile/{id}', UpdateProfileAction::class);
    Route::get('user-activities', ActivityUserListAction::class);

    Route::prefix('workspaces')->name('admin.workspace.')->group(function () {
        Route::get('/', GetWorkspaceListAction::class);        // List all plans
        Route::get('/create', GetWorkspaceAction::class)->name('create'); // Create form
        Route::get('/{id}', GetWorkspaceAction::class)->name('show');     // Show details
        Route::get('/{id}/edit', GetWorkspaceAction::class)->name('edit'); // Edit form
        Route::post('/', StoreWorkspaceAction::class)->name('store');     // Store plan
        Route::put('/{id}', UpdateWorkspaceAction::class)->name('update'); // Update plan
        Route::delete('/{id}', DeleteWorkspaceAction::class)->name('destroy'); // Delete plan
    });
    Route::prefix('users')->name('admin.user.')->group(function () {
        Route::get('/', GetUserListAction::class);        // List all plans
        Route::get('/create', GetUserAction::class)->name('create'); // Create form
        Route::get('/{id}', GetUserAction::class)->name('show');     // Show details
        Route::get('/{id}/edit', GetUserAction::class)->name('edit'); // Edit form
        Route::post('/', StoreUserAction::class)->name('store');     // Store plan
        Route::put('/{id}', UpdateUserAction::class)->name('update'); // Update plan
        Route::delete('/{id}', DeleteUserAction::class)->name('destroy'); // Delete plan
    });

    Route::prefix('roles')->name('admin.role.')->group(function () {
        Route::get('/', GetRoleListAction::class);        // List all plans
        Route::get('/create', GetRoleAction::class)->name('create'); // Create form
        Route::get('/{id}', GetRoleAction::class)->name('show');     // Show details
        Route::get('/{id}/edit', GetRoleAction::class)->name('edit'); // Edit form
        Route::post('/', StoreRoleAction::class)->name('store');     // Store plan
        Route::put('/{id}', UpdateRoleAction::class)->name('update'); // Update plan
        Route::delete('/{id}', DeleteRoleAction::class)->name('destroy'); // Delete plan
    });

    Route::prefix('general-settings')->name('admin.general-setting.')->group(function () {
        Route::get('/', GetGenerelSettingAction::class);        // List all plans
        Route::put('/{id}', UpdateGenerelSettingAction::class)->name('update'); // Update plan
    });

    // Admin routes for managing questionnaires (requires authentication)
   
});


// Route::get('images/{dir}/{filename}', [HomeController::class, 'getImage']);

Route::get('clear-cache', function () {
    \Artisan::call('optimize:clear');
    return back();
});
Route::get('seed', function () {
    \Artisan::call('db:seed');
    return back();
});



// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
