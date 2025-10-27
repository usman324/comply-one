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
use App\Actions\Admin\Workspace\DraftWorkspaceAction;
use App\Actions\Admin\Workspace\GetWorkspaceAction;
use App\Actions\Admin\Workspace\GetWorkspaceListAction;
use App\Actions\Admin\Workspace\LoadDraftWorkspaceAction;
use App\Actions\Admin\Workspace\StoreWorkspaceAction;
use App\Actions\Admin\Workspace\UpdateWorkspaceAction;
use App\Actions\Auth\LogoutAction;
use App\Actions\Admin\Workspace\AssignQuestionsToWorkspaceAction;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\WorkspaceQuestionController;
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
        // List all workspaces
        Route::get('/', GetWorkspaceListAction::class)->name('index');

        // Create form
        Route::get('/create', GetWorkspaceAction::class)->name('create');

        // Store workspace with questionnaire (used by the wizard form)
        Route::post('/create-with-questionnaire', StoreWorkspaceAction::class)->name('store-with-questionnaire');

        // Regular store (if you still want to keep it)
        Route::post('/', StoreWorkspaceAction::class)->name('store');

        // Auto-save draft
        Route::post('/auto-save', DraftWorkspaceAction::class)->name('auto-save');

        // Load draft
        Route::get('/load-draft', LoadDraftWorkspaceAction::class)->name('load-draft');

        // Workspace specific routes
        Route::prefix('/{id}')->group(function () {
            // Show details
            Route::get('', GetWorkspaceAction::class)->name('show');

            // Edit form
            Route::get('/edit', GetWorkspaceAction::class)->name('edit');

            // Update workspace
            Route::put('', UpdateWorkspaceAction::class)->name('update');

            // Update with questionnaire (for wizard-style edit)
            Route::put('/update-with-questionnaire', UpdateWorkspaceAction::class)->name('update-with-questionnaire');

            // Delete workspace
            Route::delete('', DeleteWorkspaceAction::class)->name('destroy');
        });
        Route::get('/{workspace}/assign-questions', [WorkspaceQuestionController::class, 'showAssignForm'])
            ->name('assign-questions.form');

        Route::post('/{workspace}/assign-questions', AssignQuestionsToWorkspaceAction::class)
        ;

        Route::put('/{workspace}/questions/{question}', [WorkspaceQuestionController::class, 'updateQuestionSettings'])
            ->name('questions.update-settings');

        Route::delete('/{workspace}/questions/{question}', [WorkspaceQuestionController::class, 'detachQuestion'])
            ->name('questions.detach');

        Route::post('/{workspace}/questions/reorder', [WorkspaceQuestionController::class, 'reorderQuestions'])
            ->name('questions.reorder');

        Route::post('/{workspace}/questions/{question}/attach', [WorkspaceQuestionController::class, 'attachQuestion'])
            ->name('questions.attach');

        // Bulk operations
        Route::post('/bulk-assign-questions', [WorkspaceQuestionController::class, 'bulkAssign'])
            ->name('questions.bulk-assign');

        Route::post('/{sourceWorkspace}/copy-questions-to/{targetWorkspace}', [WorkspaceQuestionController::class, 'copyQuestions'])
            ->name('questions.copy');
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

    Route::prefix('customers')->name('admin.customer.')->group(function () {
        Route::get('/', GetCustomerListAction::class);        // List all plans
        Route::get('/create', GetCustomerAction::class)->name('create'); // Create form
        Route::get('/{id}', GetCustomerAction::class)->name('show');     // Show details
        Route::get('/{id}/edit', GetCustomerAction::class)->name('edit'); // Edit form
        Route::post('/', StoreCustomerAction::class)->name('store');     // Store plan
        Route::put('/{id}', UpdateCustomerAction::class)->name('update'); // Update plan
        Route::delete('/{id}', DeleteCustomerAction::class)->name('destroy'); // Delete plan
    });

    Route::prefix('general-settings')->name('admin.general-setting.')->group(function () {
        Route::get('/', GetGenerelSettingAction::class);        // List all plans
        Route::put('/{id}', UpdateGenerelSettingAction::class)->name('update'); // Update plan
    });

    // Admin routes for managing questionnaires (requires authentication)
    Route::prefix('questionnaires')->group(function () {
        // List all questionnaires
        Route::get('/', [QuestionnaireController::class, 'index'])->name('questionnaires.index');

        // Create new questionnaire
        Route::get('/create', [QuestionnaireController::class, 'create'])->name('questionnaires.create');
        Route::post('/', [QuestionnaireController::class, 'store'])->name('questionnaires.store');

        // Edit questionnaire
        Route::get('/{id}/edit', [QuestionnaireController::class, 'edit'])->name('questionnaires.edit');
        Route::put('/{id}', [QuestionnaireController::class, 'update'])->name('questionnaires.update');

        // Delete questionnaire
        Route::delete('/{id}', [QuestionnaireController::class, 'destroy'])->name('questionnaires.destroy');

        // View questionnaire results and analytics
        Route::get('/{id}/results', [QuestionnaireController::class, 'results'])->name('questionnaires.results');
    });

    // Public routes for taking questionnaires (no authentication required)
    Route::prefix('questionnaire')->group(function () {
        // Take/view questionnaire
        Route::get('/{id}/take', [QuestionnaireController::class, 'take'])->name('questionnaire.take');

        // Submit questionnaire response
        Route::post('/submit', [QuestionnaireController::class, 'submit'])->name('questionnaire.submit');

        // Save draft (requires authentication)
        Route::post('/save-draft', [QuestionnaireController::class, 'saveDraft'])
            ->middleware('auth')
            ->name('questionnaire.save-draft');
    });
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
