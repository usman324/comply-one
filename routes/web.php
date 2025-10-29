<?php

use App\Actions\Auth\GetLoginAction;
use App\Actions\Auth\LoginAction;
use App\Actions\Admin\Dashboard\DashboardAction;
use App\Actions\Admin\Dashboard\SelectRecordAction;
use App\Actions\Admin\GenerelSetting\GetGenerelSettingAction;
use App\Actions\Admin\GenerelSetting\UpdateGenerelSettingAction;
use App\Actions\Admin\Profile\GetProfileAction;
use App\Actions\Admin\Profile\UpdateProfileAction;
use App\Actions\Admin\Questionnaire\DeleteQuestionnaireAction;
use App\Actions\Admin\Questionnaire\GetQuestionnaireAction;
use App\Actions\Admin\Questionnaire\GetQuestionnaireListAction;
use App\Actions\Admin\Questionnaire\StoreQuestionnaireAction;
use App\Actions\Admin\Questionnaire\UpdateQuestionnaireAction;
use App\Actions\Admin\Role\DeleteRoleAction;
use App\Actions\Admin\User\DeleteUserAction;
use App\Actions\Admin\Role\GetRoleAction;
use App\Actions\Admin\Role\GetRoleListAction;
use App\Actions\Admin\User\GetUserAction;
use App\Actions\Admin\User\GetUserListAction;
use App\Actions\Admin\Role\StoreRoleAction;
use App\Actions\Admin\User\StoreUserAction;
use App\Actions\Admin\Role\UpdateRoleAction;
use App\Actions\Admin\Section\DeleteSectionAction;
use App\Actions\Admin\Section\GetSectionAction;
use App\Actions\Admin\Section\GetSectionListAction;
use App\Actions\Admin\Section\StoreSectionAction;
use App\Actions\Admin\Section\UpdateSectionAction;
use App\Actions\Admin\User\ActivityUserListAction;
use App\Actions\Admin\User\UpdateUserAction;
use App\Actions\Admin\Workspace\DeleteWorkspaceAction;
use App\Actions\Admin\Workspace\DraftWorkspaceAction;
use App\Actions\Admin\Workspace\GetWorkspaceAction;
use App\Actions\Admin\Workspace\GetWorkspaceListAction;
use App\Actions\Admin\Workspace\LoadDraftWorkspaceAction;
use App\Actions\Admin\Workspace\StoreWorkspaceAction;
use App\Actions\Admin\Workspace\UpdateWorkspaceAction;
use App\Actions\Auth\LogoutAction;
use App\Actions\Admin\Workspace\AssignQuestionsToWorkspaceAction;
use App\Actions\Admin\Workspace\UpdateQuestionSettingsAction;
use App\Actions\Admin\Workspace\DetachQuestionFromWorkspaceAction;
use App\Actions\Admin\Workspace\AttachQuestionToWorkspaceAction;
use App\Actions\Admin\Workspace\ReorderWorkspaceQuestionsAction;
use App\Actions\Admin\Workspace\BulkAssignQuestionsAction;
use App\Actions\Admin\Workspace\CopyQuestionsToWorkspaceAction;
use App\Actions\Admin\Workspace\GetWorkspaceQuestionsAction;
use App\Actions\Admin\Workspace\GetAvailableQuestionsAction;
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

    Route::prefix('sections')->name('admin.section.')->group(function () {
        Route::get('/', GetSectionListAction::class);        // List all plans
        Route::get('/create', GetSectionAction::class)->name('create'); // Create form
        Route::get('/{id}', GetSectionAction::class)->name('show');     // Show details
        Route::get('/{id}/edit', GetSectionAction::class)->name('edit'); // Edit form
        Route::post('/', StoreSectionAction::class)->name('store');     // Store plan
        Route::put('/{id}', UpdateSectionAction::class)->name('update'); // Update plan
        Route::delete('/{id}', DeleteSectionAction::class)->name('destroy'); // Delete plan
    });
    Route::prefix('questionnaires')->name('admin.questionnaire.')->group(function () {
        Route::get('/', GetQuestionnaireListAction::class);        // List all plans
        Route::get('/create', GetQuestionnaireAction::class)->name('create'); // Create form
        Route::get('/{id}', GetQuestionnaireAction::class)->name('show');     // Show details
        Route::get('/{id}/edit', GetQuestionnaireAction::class)->name('edit'); // Edit form
        Route::post('/', StoreQuestionnaireAction::class)->name('store');     // Store plan
        Route::put('/{id}', UpdateQuestionnaireAction::class)->name('update'); // Update plan
        Route::delete('/{id}', DeleteQuestionnaireAction::class)->name('destroy'); // Delete plan
    });
    Route::prefix('workspaces')->name('admin.workspace.')->group(function () {
        // List all workspaces
        Route::get('/', GetQuestionnaireListAction::class);        // List all workspaces
        Route::get('/create', GetQuestionnaireAction::class)->name('create'); // Create form
        Route::get('/{id}', GetWorkspaceAction::class)->name('show');     // Show details
        Route::get('/{id}/edit', GetWorkspaceAction::class)->name('edit'); // Edit form
        Route::post('/', StoreWorkspaceAction::class)->name('store');     // Store workspaces
        Route::put('/{id}', UpdateWorkspaceAction::class)->name('update'); // Update workspaces
        Route::delete('/{id}', DeleteWorkspaceAction::class)->name('destroy'); // Delete workspaces
        Route::post('/{workspace}/assign-questions', AssignQuestionsToWorkspaceAction::class);

        Route::put('/{workspace}/questions/{question}', UpdateQuestionSettingsAction::class)
            ->name('questions.update-settings');

        Route::delete('/{workspace}/questions/{question}', DetachQuestionFromWorkspaceAction::class)
            ->name('questions.detach');

        Route::post('/{workspace}/questions/reorder', ReorderWorkspaceQuestionsAction::class)
            ->name('questions.reorder');

        Route::post('/{workspace}/questions/{question}/attach', AttachQuestionToWorkspaceAction::class)
            ->name('questions.attach');

        // Bulk operations
        Route::post('/bulk-assign-questions', BulkAssignQuestionsAction::class)
            ->name('questions.bulk-assign');

        Route::post('/{sourceWorkspace}/copy-questions-to/{targetWorkspace}', CopyQuestionsToWorkspaceAction::class)
            ->name('questions.copy');

        // Get workspace questions
        Route::get('/{workspace}/questions', GetWorkspaceQuestionsAction::class)
            ->name('questions.index');

        Route::get('/{workspace}/available-questions', GetAvailableQuestionsAction::class)
            ->name('questions.available');
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
Route::get('storage', function () {
    \Artisan::call('storage:link');
    return back();
});



// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
