<?php

use App\Actions\Auth\GetLoginAction;
use App\Actions\Auth\LoginAction;
use App\Actions\Admin\Dashboard\DashboardAction;
use App\Actions\Admin\Dashboard\SelectRecordAction;
use App\Actions\Admin\Workspace\Folder\GetFolderStatisticsAction;
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
use App\Actions\Admin\Workspace\File\DeleteFileAction;
use App\Actions\Admin\Workspace\File\DownloadFileAction;
use App\Actions\Admin\Workspace\File\ForceDeleteFileAction;
use App\Actions\Admin\Workspace\File\GetFileAction;
use App\Actions\Admin\Workspace\File\GetFileListAction;
use App\Actions\Admin\Workspace\File\StoreDemoFileAction;
use App\Actions\Admin\Workspace\File\StoreFileAction;
use App\Actions\Admin\Workspace\File\ToggleStarFileAction;
use App\Actions\Admin\Workspace\File\UpdateFileAction;
use App\Actions\Admin\Workspace\FileManager\GetFileManagerAction;
use App\Actions\Admin\Workspace\FileManager\GetRecentFilesAction;
use App\Actions\Admin\Workspace\FileManager\GetStarredFilesAction;
use App\Actions\Admin\Workspace\FileManager\GetStatisticsAction;
use App\Actions\Admin\Workspace\FileManager\GetTrashAction;
use App\Actions\Admin\Workspace\FileManager\SearchFilesAction;
use App\Actions\Admin\Workspace\Folder\DeleteFolderAction;
use App\Actions\Admin\Workspace\Folder\GetFolderAction;
use App\Actions\Admin\Workspace\Folder\GetFolderListAction;
use App\Actions\Admin\Workspace\Folder\StoreFolderAction;
use App\Actions\Admin\Workspace\Folder\UpdateFolderAction;
use App\Actions\Admin\Workspace\GetWorkspaceQuestionsAction;
use App\Actions\Admin\Workspace\GetAvailableQuestionsAction;
use App\Actions\Admin\Workspace\User\AddWorkspaceUserAction;
use App\Actions\Admin\Workspace\User\DeleteWorkspaceUserAction;
use App\Actions\Admin\Workspace\User\GetWorkspaceUserAction;
use App\Actions\Admin\Workspace\User\GetWorkspaceUserListAction;
use App\Actions\Admin\Workspace\User\UpdateWorkspaceUserAction;
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
                Route::get('/', GetFolderListAction::class)->name('index');
                Route::get('/statistics', GetFolderStatisticsAction::class)->name('statistics');
                Route::get('/create', GetFolderAction::class)->name('create');
                Route::post('', StoreFolderAction::class)->name('store');

                Route::prefix('/{folder}')->group(function () {
                    Route::get('/', GetFolderAction::class)->name('show');
                    Route::get('/edit', GetFolderAction::class)->name('edit');
                    Route::put('/', UpdateFolderAction::class)->name('update');
                    Route::delete('/', DeleteFolderAction::class)->name('destroy');

                    // Additional folder actions (to be implemented)
                    // Route::post('/move', MoveFolderAction::class)->name('move');
                    // Route::post('/archive', ArchiveFolderAction::class)->name('archive');
                    // Route::post('/restore', RestoreFolderAction::class)->name('restore');
                });
            });

            // File Routes
            Route::prefix('files')->name('files.')->group(function () {
                Route::get('/', GetFileListAction::class)->name('index');
                Route::get('/create', GetFileAction::class)->name('create');
                Route::post('/', StoreFileAction::class)->name('store');
                Route::post('/demo', StoreDemoFileAction::class)->name('store-demo');

                Route::prefix('/{file}')->group(function () {
                    Route::get('/', GetFileAction::class)->name('show');
                    Route::get('/edit', GetFileAction::class)->name('edit');
                    Route::put('/', UpdateFileAction::class)->name('update');
                    Route::delete('/', DeleteFileAction::class)->name('destroy');

                    // File-specific actions
                    Route::get('/download', DownloadFileAction::class)->name('download');
                    Route::post('/toggle-star', ToggleStarFileAction::class)->name('toggle-star');
                    Route::delete('/force', ForceDeleteFileAction::class)->name('force-destroy');

                    // Additional file actions (to be implemented)
                    // Route::post('/move', MoveFileAction::class)->name('move');
                    // Route::post('/archive', ArchiveFileAction::class)->name('archive');
                    // Route::post('/restore', RestoreFileAction::class)->name('restore');
                });

                // Bulk operations (to be implemented)
                // Route::post('/bulk-delete', BulkDeleteFilesAction::class)->name('bulk-delete');
                // Route::post('/bulk-move', BulkMoveFilesAction::class)->name('bulk-move');
                // Route::post('/bulk-archive', BulkArchiveFilesAction::class)->name('bulk-archive');
            });

            // File Manager Routes
            Route::prefix('file-managers')->name('file-managers.')->group(function () {
                Route::get('/', GetFileManagerAction::class)->name('index');
                Route::get('/statistics', GetStatisticsAction::class)->name('statistics');
                Route::get('/trash', GetTrashAction::class)->name('trash');
                Route::get('/recent', GetRecentFilesAction::class)->name('recent');
                Route::get('/starred', GetStarredFilesAction::class)->name('starred');
                Route::get('/search', SearchFilesAction::class)->name('search');
            });

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
