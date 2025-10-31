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
use App\Actions\Admin\Workspace\User\ActivityUserListAction;
use App\Actions\Admin\User\UpdateUserAction;
use App\Actions\Admin\Workspace\DeleteWorkspaceAction;
use App\Actions\Admin\Workspace\GetWorkspaceAction;
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
use App\Actions\Admin\Workspace\User\AddWorkspaceUserAction;
use App\Actions\Admin\Workspace\User\DeleteWorkspaceUserAction;
use App\Actions\Admin\Workspace\User\GetWorkspaceUserAction;
use App\Actions\Admin\Workspace\User\GetWorkspaceUserListAction;
use App\Actions\Admin\Workspace\User\UpdateWorkspaceUserAction;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\FolderController;
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
        Route::get('/', GetSectionListAction::class);
        Route::get('/create', GetSectionAction::class)->name('create');
        Route::get('/{id}', GetSectionAction::class)->name('show');
        Route::get('/{id}/edit', GetSectionAction::class)->name('edit');
        Route::post('/', StoreSectionAction::class)->name('store');
        Route::put('/{id}', UpdateSectionAction::class)->name('update');
        Route::delete('/{id}', DeleteSectionAction::class)->name('destroy');
    });
    Route::prefix('questionnaires')->name('admin.questionnaire.')->group(function () {
        Route::get('/', GetQuestionnaireListAction::class);
        Route::get('/create', GetQuestionnaireAction::class)->name('create');
        Route::get('/{id}', GetQuestionnaireAction::class)->name('show');
        Route::get('/{id}/edit', GetQuestionnaireAction::class)->name('edit');
        Route::post('/', StoreQuestionnaireAction::class)->name('store');
        Route::put('/{id}', UpdateQuestionnaireAction::class)->name('update');
        Route::delete('/{id}', DeleteQuestionnaireAction::class)->name('destroy');
    });
    Route::prefix('workspaces')->name('admin.workspace.')->group(function () {
        Route::get('/', GetQuestionnaireListAction::class);
        Route::get('/create', GetQuestionnaireAction::class)->name('create');
        Route::get('/{id}', GetWorkspaceAction::class)->name('show');
        Route::get('/{id}/edit', GetWorkspaceAction::class)->name('edit');
        Route::post('/', StoreWorkspaceAction::class)->name('store');
        Route::put('/{id}', UpdateWorkspaceAction::class)->name('update');
        Route::delete('/{id}', DeleteWorkspaceAction::class)->name('destroy');

        Route::prefix('/{workspace}')->group(function () {
            Route::post('/assign-questions', AssignQuestionsToWorkspaceAction::class);

            Route::prefix('/questions')->name('questions.')->group(function () {
                // Get workspace questions
                Route::get('/', GetWorkspaceQuestionsAction::class)
                    ->name('index');
                Route::put('/{question}', UpdateQuestionSettingsAction::class)
                    ->name('update-settings');

                Route::delete('/{question}', DetachQuestionFromWorkspaceAction::class)
                    ->name('questions.detach');

                Route::post('/reorder', ReorderWorkspaceQuestionsAction::class)
                    ->name('reorder');

                Route::post('/{question}/attach', AttachQuestionToWorkspaceAction::class)
                    ->name('attach');
            });

            Route::get('/available-questions', GetAvailableQuestionsAction::class)
                ->name('questions.available');

            // Folder Routes
            Route::prefix('folders')->name('folders.')->group(function () {
                Route::get('/', [FolderController::class, 'index'])->name('index');
                Route::get('/create', [FolderController::class, 'create'])->name('create');
                Route::post('/', [FolderController::class, 'store'])->name('store');
                Route::get('/{folder}', [FolderController::class, 'show'])->name('show');
                Route::get('/{folder}/edit', [FolderController::class, 'edit'])->name('edit');
                Route::put('/{folder}', [FolderController::class, 'update'])->name('update');
                Route::delete('/{folder}', [FolderController::class, 'destroy'])->name('destroy');

                // Additional folder actions
                Route::post('/{folder}/move', [FolderController::class, 'move'])->name('move');
                Route::post('/{folder}/archive', [FolderController::class, 'archive'])->name('archive');
                Route::post('/{folder}/restore', [FolderController::class, 'restore'])->name('restore');
            });

            // File Routes
            Route::prefix('files')->name('files.')->group(function () {
                Route::get('/', [FileController::class, 'index'])->name('index');
                Route::get('/create', [FileController::class, 'create'])->name('create');
                Route::post('/', [FileController::class, 'store'])->name('store');
                Route::get('/{file}', [FileController::class, 'show'])->name('show');
                Route::get('/{file}/edit', [FileController::class, 'edit'])->name('edit');
                Route::put('/{file}', [FileController::class, 'update'])->name('update');
                Route::delete('/{file}', [FileController::class, 'destroy'])->name('destroy');

                // File-specific actions
                Route::get('/{file}/download', [FileController::class, 'download'])->name('download');
                Route::post('/{file}/toggle-star', [FileController::class, 'toggleStar'])->name('toggle-star');
                Route::post('/{file}/move', [FileController::class, 'move'])->name('move');
                Route::post('/{file}/archive', [FileController::class, 'archive'])->name('archive');
                Route::post('/{file}/restore', [FileController::class, 'restore'])->name('restore');
                Route::delete('/{file}/force', [FileController::class, 'forceDestroy'])->name('force-destroy');

                // Bulk operations
                Route::post('/bulk-delete', [FileController::class, 'bulkDelete'])->name('bulk-delete');
                Route::post('/bulk-move', [FileController::class, 'bulkMove'])->name('bulk-move');
                Route::post('/bulk-archive', [FileController::class, 'bulkArchive'])->name('bulk-archive');
            });

            Route::get('/file-managers', [FileManagerController::class, 'index'])->name('file-manager');

            Route::prefix('/users')->name('user.')->group(function () {
                Route::get('/', GetWorkspaceUserListAction::class);
                Route::get('/create', GetWorkspaceUserAction::class)->name('create');
                Route::get('/{user:id}', GetWorkspaceUserAction::class)->name('show');
                Route::get('/{user:id}/edit', GetWorkspaceUserAction::class)->name('edit');
                Route::post('/', AddWorkspaceUserAction::class)->name('store');
                Route::put('/{user:id}', UpdateWorkspaceUserAction::class)->name('update');

                Route::delete('/{id}', DeleteWorkspaceUserAction::class)->name('destroy');
            });
        });
        Route::post('/{sourceWorkspace}/copy-questions-to/{targetWorkspace}', CopyQuestionsToWorkspaceAction::class)
            ->name('questions.copy');

        // Bulk operations
        Route::post('/bulk-assign-questions', BulkAssignQuestionsAction::class)
            ->name('questions.bulk-assign');
    });

    Route::prefix('users')->name('admin.user.')->group(function () {
        Route::get('/', GetUserListAction::class);
        Route::get('/create', GetUserAction::class)->name('create');
        Route::get('/{id}', GetUserAction::class)->name('show');
        Route::post('/', StoreUserAction::class)->name('store');
        Route::put('/{id}', UpdateUserAction::class)->name('update');
        Route::get('/{id}/edit', GetUserAction::class)->name('edit');
        Route::delete('/{id}', DeleteUserAction::class)->name('destroy');
    });

    Route::prefix('roles')->name('admin.role.')->group(function () {
        Route::get('/', GetRoleListAction::class);
        Route::get('/create', GetRoleAction::class)->name('create');
        Route::get('/{id}', GetRoleAction::class)->name('show');
        Route::get('/{id}/edit', GetRoleAction::class)->name('edit');
        Route::post('/', StoreRoleAction::class)->name('store');
        Route::put('/{id}', UpdateRoleAction::class)->name('update');
        Route::delete('/{id}', DeleteRoleAction::class)->name('destroy');
    });


    Route::prefix('general-settings')->name('admin.general-setting.')->group(function () {
        Route::get('/', GetGenerelSettingAction::class);
        Route::put('/{id}', UpdateGenerelSettingAction::class)->name('update');
    });




    // Statistics and Reports
    Route::get('/statistics', [FileManagerController::class, 'statistics'])->name('statistics');
    Route::get('/trash', [FileManagerController::class, 'trash'])->name('trash');
    Route::get('/recent', [FileManagerController::class, 'recent'])->name('recent');
    Route::get('/starred', [FileManagerController::class, 'starred'])->name('starred');

    // Search
    Route::get('/search', [FileManagerController::class, 'search'])->name('search');
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
