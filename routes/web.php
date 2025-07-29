<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\EventStreamController;
use Illuminate\Http\Request;
use App\Http\Controllers\GeneralInfoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PrecalPdfController;
use App\Http\Controllers\Dashboard\TeamController;
use App\Http\Controllers\Dashboard\TeamDetailsController;
use App\Http\Controllers\PaymentAuthController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FinancieraController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderInstallationController;
use App\Http\Controllers\ChatOrderInstallationController;

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
//login - inicio app

Route::get('/', function () {
    return view('auth.login');
});

//ruta dashboard despues del logueo y btn Inicio
Route::any('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::post('/dashboard/bar', [App\Http\Controllers\HomeController::class, 'application'])->middleware(['auth', 'verified'])->name('dashboard.bar');
Route::get('/home/application', [App\Http\Controllers\HomeController::class, 'application'])->middleware(['auth', 'verified'])->name('home.application');
Route::get('/dashboard/data', [App\Http\Controllers\HomeController::class, 'getDashboardData'])->middleware(['auth', 'verified'])->name('dashboard.data');

//ruta reporte
Route::any('/report', [App\Http\Controllers\ReportController::class, 'index'])->middleware(['auth', 'verified'])->name('report');
Route::post('/report/bar', [App\Http\Controllers\ReportController::class, 'bar'])->middleware(['auth', 'verified'])->name('report.bar');
Route::any('/report/pie', [App\Http\Controllers\ReportController::class, 'pie'])->middleware(['auth', 'verified'])->name('report.pie');
Route::get('/report/teamsales', [App\Http\Controllers\ReportController::class, 'teamSales'])->middleware(['auth', 'verified'])->name('report.teamsales');
Route::get('/report/sales', [App\Http\Controllers\ReportController::class, 'sales'])->middleware(['auth', 'verified'])->name('report.sales');
Route::get('/report/sales/export', [App\Http\Controllers\ReportController::class, 'exportSales'])->middleware(['auth', 'verified'])->name('report.exportsales');
Route::get('/report/sales/exportTeam', [App\Http\Controllers\ReportController::class, 'exporTeamSales'])->middleware(['auth', 'verified'])->name('report.exportsalesteam');
Route::get('/report/team', [App\Http\Controllers\ReportController::class, 'teamLevel'])->middleware(['auth', 'verified'])->name('report.team');
Route::get('/report/team/data', [App\Http\Controllers\ReportController::class, 'getTeamLevelData'])->middleware(['auth', 'verified'])->name('report.team.data');
Route::post('/report/team', [App\Http\Controllers\ReportController::class, 'exportTeam'])->middleware(['auth', 'verified'])->name('report.exportTeam');
Route::get('/report/application', [App\Http\Controllers\ReportApplicationController::class, 'index'])->middleware(['auth', 'verified'])->name('report.application');
Route::post('/report/installed', [App\Http\Controllers\ReportApplicationController::class, 'installedApp'])->middleware(['auth', 'verified'])->name('report.installed.app');
Route::get('/report/installed/export', [App\Http\Controllers\ReportApplicationController::class, 'installedExport'])->middleware(['auth', 'verified'])->name('report.installed.export');
Route::post('/report/application', [App\Http\Controllers\ReportApplicationController::class, 'application'])->middleware(['auth', 'verified'])->name('report.application.bar');
Route::get('/report/application/export', [App\Http\Controllers\ReportApplicationController::class, 'applicationExport'])->middleware(['auth', 'verified'])->name('report.application.export');

//rutas universidad

Route::prefix('university')->middleware(['auth', 'verified'])->group(function () {
    Route::any('/', [App\Http\Controllers\UniversityController::class, 'index'])->name('university');
    Route::get('/documents', [App\Http\Controllers\UniversityController::class, 'documents'])->name('u_documents');
    Route::get('/videos', [App\Http\Controllers\UniversityController::class, 'videos'])->name('u_videos');
    Route::get('/faq', [App\Http\Controllers\UniversityController::class, 'faq'])->name('u_faq');
    Route::get('/contact', [App\Http\Controllers\UniversityController::class, 'contact'])->name('u_contact');
    Route::get('/blog', [App\Http\Controllers\UniversityController::class, 'blog'])->name('u_blog');
});

//ruta eventos
Route::any('/event', [App\Http\Controllers\EventController::class, 'index'])->middleware(['auth', 'verified'])->name('event');
Route::post('/event/store', [App\Http\Controllers\EventController::class, 'store'])->middleware(['auth', 'verified'])->name('event.store');
Route::get('/obtener-adjuntos-evento/{id}', [App\Http\Controllers\EventController::class, 'getAdjuntos'])->middleware(['auth', 'verified'])->name('event.adjuntos');

//ruta tienda
Route::any('/store/store', [App\Http\Controllers\StoreController::class, 'index'])->middleware(['auth', 'verified'])->name('shop');

//ruta team
Route::any('/team', [App\Http\Controllers\TeamController::class, 'index'])->middleware(['auth', 'verified'])->name('team');

//ruta clientes
Route::any('/customers', [App\Http\Controllers\CustomersController::class, 'index'])->middleware(['auth', 'verified'])->name('customers');

//ruta cuenta
Route::any('/account/{type?}', [App\Http\Controllers\AccountController::class, 'index'])->middleware(['auth', 'verified'])->name('account');


//ruta ordenes de instalacion
Route::get('/ordenes-instalacion', [OrderInstallationController::class, 'index'])->middleware(['auth', 'verified'])->name('ordenes.instalacion');

//rutas auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//registro de usuario para el acceso a la app
Route::get('/register/acces', [App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->middleware(['auth', 'verified'])->name('registeracces');
Route::post('/store/acces', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store'])->middleware(['auth', 'verified'])->name('storeacces');

Route::get('/edit/acces', [App\Http\Controllers\Auth\RegisteredUserController::class, 'edit'])->middleware(['auth', 'verified'])->name('editacces');


Route::get('/show/update/acces', [App\Http\Controllers\SettingController::class, 'editPassword'])->middleware(['auth', 'verified'])->name('showupdatepassword');
Route::post('/update/acces', [App\Http\Controllers\SettingController::class, 'updatePassword'])->middleware(['auth', 'verified'])->name('updateacces');
Route::get('/setting', [App\Http\Controllers\SettingController::class, 'show'])->middleware(['auth', 'verified'])->name('setting.show');
Route::put('/setting/update', [App\Http\Controllers\SettingController::class, 'update'])->middleware(['auth', 'verified'])->name('setting.update');
Route::get('/setting/backUrl', [App\Http\Controllers\SettingController::class, 'backLastUrl'])->middleware(['auth', 'verified'])->name('setting.backLastUrl');

//leads
Route::post('/store/lead', [App\Http\Controllers\StoreController::class, 'createNewLead'])->middleware(['auth', 'verified'])->name('shopNewLead');
Route::post('/store/lead/edit', [App\Http\Controllers\StoreController::class, 'editLead'])->middleware(['auth', 'verified'])->name('shopEditLead');
Route::get('/store/lead/ordertable', [App\Http\Controllers\StoreController::class, 'orderListBtn'])->middleware(['auth', 'verified'])->name('orderLead');
Route::any('/store/lead/search', [App\Http\Controllers\StoreController::class, 'searchLeads'])->middleware(['auth', 'verified'])->name('leadsSearch');
Route::get('/store/lead/precualificacion', [App\Http\Controllers\StoreController::class, 'armarRequestPreclasificacion'])->middleware(['auth', 'verified'])->name('req_precualificacion');
Route::get('/store/lead/searchname', [App\Http\Controllers\StoreController::class, 'searchTextLeads'])->middleware(['auth', 'verified'])->name('searchTextLeads');

//ruta proyectos
Route::get('/account/projects/urls', [App\Http\Controllers\AccountController::class, 'armarRequestProjects'])->middleware(['auth', 'verified'])->name('req_urls_projects');
Route::get('/account/projects/search/{type?}', [App\Http\Controllers\AccountController::class, 'searchProjects'])->middleware(['auth', 'verified'])->name('project_Search');
Route::get('/account/projects/ordertable', [App\Http\Controllers\AccountController::class, 'orderListBtn'])->middleware(['auth', 'verified'])->name('orderProject');
Route::get('/account/projects/download/{type?}', [App\Http\Controllers\AccountController::class, 'exportProjects'])->middleware(['auth', 'verified'])->name('exportProjects');
Route::get('/account/notifications/{co_aplicacion}/{type?}', [App\Http\Controllers\AccountController::class, 'showNotifications'])
->middleware(['auth', 'verified'])->name('account.notifications');

Route::get('/forms/proposal', function (Request $request) {
    $projectData = [
        'project_id' => $request->query('project_id'),
        'client_name' => $request->query('client_name'),
        'state' => $request->query('state'),
        'city' => $request->query('city'),
        'address' => $request->query('address'),
        'zip_code' => $request->query('zip_code'),
        'phone' => $request->query('phone'),
        'email' => $request->query('email')
    ];
    return view('dashboard.forms.proposal', ['project' => $projectData]);
})->middleware(['auth', 'verified'])->name('forms.proposal');

//Route::post('/forms/proposal', [App\Http\Controllers\ProposalController::class, 'store'])
//    ->middleware(['auth', 'verified'])->name('forms.proposal.store');

Route::get('/forms/credit-card-auth', function (Request $request) {
    $projectData = [
        'project_id' => $request->query('project_id'),
        'client_name' => $request->query('client_name'),
        'state' => $request->query('state'),
        'city' => $request->query('city'),
        'address' => $request->query('address'),
        'zip_code' => $request->query('zip_code'),
        'phone' => $request->query('phone'),
        'email' => $request->query('email')
    ];
    return view('dashboard.forms.credit-card-auth', ['project' => $projectData]);
})->middleware(['auth', 'verified'])->name('forms.credit-card-auth');

//Route::post('/forms/credit-card-auth', [App\Http\Controllers\CreditCardAuthController::class, 'store'])
//    ->middleware(['auth', 'verified'])->name('forms.credit-card-auth.store');

//Formulario de precalificacion
Route::get('/forms/general-info/create', [GeneralInfoController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('forms.general-info.create');
Route::post('/forms/general-info', [GeneralInfoController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('forms.general-info.store');
Route::get('/forms/general-info/edit/{urldestination?}', [GeneralInfoController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('forms.general-info.edit');
Route::post('/forms/general-info/update', [GeneralInfoController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('forms.general-info.update');    
Route::get('/creation-new-user', function () {
    return view('users.create');
})->middleware(['auth', 'verified'])->name('users.create');

Route::get('/users/create', [UserController::class, 'create'])->name('users.create')->middleware('tipo_usuario');
Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware('tipo_usuario');
Route::get('/users', [UserController::class, 'index'])->name('users.index')->middleware('tipo_usuario');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('tipo_usuario');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('tipo_usuario');
Route::get('/users/detail', [UserController::class, 'show'])->name('users.detail')->middleware('tipo_usuario');


Route::get('/dashboard/team', [TeamController::class, 'showTeamData'])->name('dashboard.team');
Route::get('/dashboard/team-details', [TeamDetailsController::class, 'showTeamData'])->name('dashboard.team-details');
Route::get('/dashboard/team-update', [TeamDetailsController::class, 'nextStateApp'])->name('dashboard.team.update-state');
Route::get('/dashboard/team-stop', [TeamDetailsController::class, 'stopApp'])->name('dashboard.team.stop');
Route::get('/dashboard/team-cancel', [TeamDetailsController::class, 'cancelApp'])->name('dashboard.team.cancel');
Route::get('/dashboard/team-activate', [TeamDetailsController::class, 'activateApp'])->name('dashboard.team.activate');
Route::get('/dashboard/team/order', [TeamController::class, 'showTeamDataOrder'])->name('dashboard.team.order');
Route::get('/dashboard/team/tsearch', [TeamController::class, 'showTeamSearch'])
->middleware(['auth', 'verified'])
->name('dashboard.team.tsearch');
Route::get('/dashboard/team/tfilter', [TeamController::class, 'showTeamFilter'])
->middleware(['auth', 'verified'])
->name('dashboard.team.tfilter');

Route::get('/dashboard/team/workorder', [TeamDetailsController::class, 'workOrder'])
->middleware(['auth', 'verified'])
->name('dashboard.team.workorder');

Route::post('/dashboard/team/financial', [TeamDetailsController::class, 'updateFinancialStatus'])
->middleware(['auth', 'verified'])
->name('dashboard.team.financial');

Route::put('/dashboard/team/financial-info', [TeamDetailsController::class, 'updateFinancialInfo'])
->middleware(['auth', 'verified'])
->name('dashboard.team.financial-info');



Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

// Payment Authorization Form Routes
Route::get('/forms/payment-auth', [PaymentAuthController::class, 'create'])->name('forms.payment-auth');
Route::post('/forms/payment-auth', [PaymentAuthController::class, 'store'])->name('forms.payment-auth.store');
// Notification Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/count', [NotificationsController::class, 'getUnreadCount'])->name('notifications.count');
    Route::get('/notifications/recent', [NotificationsController::class, 'getRecentNotifications'])->name('notifications.recent');
    Route::post('/notifications/{id}/mark-as-read', [NotificationsController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationsController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
});

Route::get('/notifications/preferences', function () {
    return view('dashboard.notifications.preferences');
})->name('notifications.preferences');
Route::get('/notifications/preferences', [NotificationsController::class, 'preferences'])->name('notifications.preferences');
Route::put('/notifications/preferences', [NotificationsController::class, 'updatePreferences'])->name('notifications.updatePreferences'); // Use PUT route
Route::get('/notifications/create', [NotificationsController::class, 'create'])->middleware(['auth', 'verified'])->name('notifications.create'); 
Route::post('/notifications/store', [NotificationsController::class, 'store'])->middleware(['auth', 'verified'])->name('notifications.store'); 

Route::prefix('chat')
    ->middleware(['auth', 'verified', 'throttle:60,1'])
    ->name('chat.')
    ->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('chat.index');
        Route::get('messages', [ChatController::class, 'history'])->name('chat.history');
        Route::post('send', [ChatController::class, 'send'])->name('chat.send');        
        Route::post('destroy', [ChatController::class, 'destroy'])->name('chat.destroy');
});

Route::get('/document', [DocumentController::class, 'index'])->middleware(['auth', 'verified'])->name('document.index');
Route::post('/document/store', [DocumentController::class, 'store'])->middleware(['auth', 'verified'])->name('document.store');
Route::post('/document/store-contract', [DocumentController::class, 'storeContract'])->middleware(['auth', 'verified'])->name('document.storecontract');

Route::get('/financiera/show', [FinancieraController::class, 'getFinanciera'])->middleware(['auth', 'verified'])->name('financiera.show');

Route::get('/team-applications-last-12-months', [HomeController::class, 'getTeamApplicationsLast12Months'])->name('team.applications.last12months');
Route::get('/team-applications-view', [HomeController::class, 'showTeamApplicationsView'])->name('team.applications.view');

Route::prefix('installation')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [OrderInstallationController::class, 'index'])->name('installation');
    Route::get('/details', [OrderInstallationController::class, 'show'])->name('installation.details');
    Route::get('/search', [OrderInstallationController::class, 'search'])->name('installation.search');
    Route::get('/export', [OrderInstallationController::class, 'exportOrders'])->name('installation.export');
    Route::get('/notifications/{co_aplicacion}', function($co_aplicacion) {
        return app(OrderInstallationController::class)->showNotifications($co_aplicacion, 'unread');
    })->name('installation.notifications');
    Route::get('/notifications/{co_aplicacion}/all', function($co_aplicacion) {
        return app(OrderInstallationController::class)->showNotifications($co_aplicacion, 'all');
    })->name('installation.notifications.all');
    Route::post('/notifications/{co_aplicacion}/mark-read', [OrderInstallationController::class, 'markNotificationsAsRead'])->name('installation.notifications.markread');
});
//Chat ordenes de instalacion
Route::prefix('chatoi')->middleware(['auth', 'verified', 'throttle:60,1'])->group(function () {
    Route::get('/', [ChatOrderInstallationController::class, 'index'])->name('chatoi');
    Route::get('messages', [ChatOrderInstallationController::class, 'history'])->name('chatoi.history');
    Route::post('send', [ChatOrderInstallationController::class, 'send'])->name('chatoi.send');        
    Route::post('destroy', [ChatOrderInstallationController::class, 'destroy'])->name('chatoi.destroy');
});

// Rutas para notificaciones de órdenes de instalación
/*
Route::get('/ordenes-instalacion/notifications/{co_aplicacion}', [AccountController::class, 'showNotifications'])
    ->middleware(['auth', 'verified'])->name('ordenes.instalacion.notifications');
Route::get('/ordenes-instalacion/notifications/{co_aplicacion}/all', [AccountController::class, 'showNotifications'])
    ->middleware(['auth', 'verified'])->name('ordenes.instalacion.notifications.all');
*/

// Rutas para verificaciones y gastos
Route::prefix('verifications')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [App\Http\Controllers\VerificationController::class, 'index'])->name('verifications.index');
    Route::get('/create', [App\Http\Controllers\VerificationController::class, 'create'])->name('verifications.create');
    Route::post('/', [App\Http\Controllers\VerificationController::class, 'store'])->name('verifications.store');
    Route::get('/{verification}/edit', [App\Http\Controllers\VerificationController::class, 'edit'])->name('verifications.edit');
    Route::put('/{verification}', [App\Http\Controllers\VerificationController::class, 'update'])->name('verifications.update');
    Route::delete('/{verification}', [App\Http\Controllers\VerificationController::class, 'destroy'])->name('verifications.destroy');
    Route::get('/{verification}/water-types', [App\Http\Controllers\VerificationController::class, 'getWaterTypes'])->name('verifications.getWaterTypes');
    Route::post('/{verification}/water-types', [App\Http\Controllers\VerificationController::class, 'updateWaterTypes'])->name('verifications.updateWaterTypes');
});

Route::prefix('expenses')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [App\Http\Controllers\ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/create', [App\Http\Controllers\ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/', [App\Http\Controllers\ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/{expense}/edit', [App\Http\Controllers\ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('/{expense}', [App\Http\Controllers\ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/{expense}', [App\Http\Controllers\ExpenseController::class, 'destroy'])->name('expenses.destroy');
    Route::get('/{expense}/water-types', [App\Http\Controllers\ExpenseController::class, 'getWaterTypes'])->name('expenses.getWaterTypes');
    Route::post('/{expense}/water-types', [App\Http\Controllers\ExpenseController::class, 'updateWaterTypes'])->name('expenses.updateWaterTypes');
});

require __DIR__ . '/auth.php';
