<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\MicrosoftController;
use App\Http\Controllers\SendMailController;
use App\Http\Controllers\Drs\Setting\EventController;
use App\Http\Controllers\GeneralSettings\EventDocumentController;
use App\Http\Controllers\GeneralSettings\ParticipantDocumentController;
use App\Http\Controllers\GeneralSettings\UploadController;

use App\Http\Controllers\Security\ActivityAuditController;
use App\Http\Controllers\Security\RoleController as SecurityRoleController;
use App\Http\Controllers\Drs\Admin\UserController as AdminUserController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\Drs\Setting\VenueController;
use App\Http\Controllers\Drs\Setting\FunctionalAreaController;
use App\Http\Controllers\UtilController;
use App\Http\Controllers\Drs\Auth\AdminController as VmsAuthAdminController;
use App\Http\Controllers\Drs\Admin\DashboardController;
use App\Http\Controllers\Drs\Admin\ImportExportController;
use App\Http\Controllers\Drs\Admin\VenueMatchReportController as AdminVenueMatchReportController;
use App\Http\Controllers\Drs\Admin\VenueMatchReportDocumentController;
use App\Http\Controllers\Drs\Customer\VenueMatchReportController;
use App\Http\Controllers\Drs\Setting\AppSettingController;
use App\Http\Controllers\Drs\Setting\EventImageController;
use App\Http\Controllers\Drs\Setting\MatchController;
use App\Http\Controllers\Drs\Shared\VenueMatchReportController as SharedVenueMatchReportController;
use App\Http\Controllers\Drs\Shared\DailyRunSheetController as SharedDailyRunSheetController;
use App\Http\Controllers\Drs\Admin\DailyRunSheetController as AdminDailyRunSheetController;
use App\Models\Drs\VenueMatchReportDocument;
use Illuminate\Support\Facades\Storage;

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
//     if (auth()->check()) {
//         if (auth()->user()->is_admin) {
//             return redirect()->route('vapp.admin');
//         } elseif (auth()->user()->hasRole('Customer')) {
//             appLog('Redirecting to vapp.customer');
//             return redirect()->route('vapp.customer');
//         }
//     } else {
//         return redirect()->route('login');
//     }
// })->name('home');

// Route::get('/debug', function () {
//     return [
//         'scheme' => request()->getScheme(),
//         'host'   => request()->getHost(),
//         'url'    => request()->fullUrl(),
//         'headers'=> request()->headers->all(),
//     ];
// });

Route::get('/', function () {
    appLog('In home route');
    if (!auth()->check()) {
        appLog('User is not authenticated');
        return redirect()->route('login');
    }

    $roleRoutes = [
        'SuperAdmin' => 'drs.admin.report',
        'Customer'   => 'drs.report',
    ];

    foreach ($roleRoutes as $role => $route) {
        if (auth()->user()->hasRole($role)) {
            appLog("Redirecting to $route for role $role");
            return redirect()->route($route);
        }
    }

    abort(403, 'Unauthorized role');
})->name('home');


Route::controller(MicrosoftController::class)->group(function () {
    Route::get('auth/microsoft', 'redirectToMicrosoft')->name('auth.microsoft');
    Route::get('auth/microsoft/callback', 'handleMicrosoftCallback');
});



// Report MANAGEMENT ******************************************************************** Admin All Route
Route::middleware(['auth', 'otp', 'mutli.event', 'XssSanitizer', 'role:SuperAdmin', 'prevent-back-history', 'auth.session'])->group(function () {

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/drs/admin/dashboard', 'dashboard')->name('drs.admin.dashboard');
    });

    //Import and Export
    Route::controller(ImportExportController::class)->group(function () {
        Route::get('/drs/admin/report/import', 'showImportForm')->name('drs.admin.report.import');
        Route::post('/drs/admin/report/import', 'import')->name('drs.admin.report.import.store');
        Route::post('/drs/admin/report/export', 'export')->name('drs.admin.report.export');
    });

    //VMS
    Route::controller(AdminVenueMatchReportController::class)->group(function () {
        Route::get('/drs/admin/report', 'index')->name('drs.admin.report');
        Route::get('/drs/admin/report/list', 'list')->name('drs.admin.report.list');
        // Route::get('/drs/admin/events/{id}/switch',  'switch')->name('drs.admin.event.switch');
    });

    // Event Match
    Route::controller(MatchController::class)->group(function () {
        Route::get('/drs/setting/match', 'index')->name('drs.setting.match');
        Route::get('/drs/setting/match/list', 'list')->name('drs.setting.match.list');
        Route::get('/drs/setting/match/get/{id}', 'get')->name('drs.setting.match.get');
        Route::post('/drs/setting/match/update', 'update')->name('drs.setting.match.update');
        Route::delete('/drs/setting/match/delete/{id}', 'delete')->name('drs.setting.match.delete');
        Route::post('/drs/setting/match/store', 'store')->name('drs.setting.match.store');
    });

    // Functional Area
    Route::controller(FunctionalAreaController::class)->group(function () {
        Route::get('/drs/setting/functional_area', 'index')->name('drs.setting.functional_area');
        Route::get('/drs/setting/functional_area/list', 'list')->name('drs.setting.functional_area.list');
        Route::get('/drs/setting/functional_area/get/{id}', 'get')->name('drs.setting.functional_area.get');
        Route::post('/drs/setting/functional_area/update', 'update')->name('drs.setting.functional_area.update');
        Route::delete('/drs/setting/functional_area/delete/{id}', 'delete')->name('drs.setting.functional_area.delete');
        Route::post('/drs/setting/functional_area/store', 'store')->name('drs.setting.functional_area.store');
    });

    // Venue
    Route::controller(VenueController::class)->group(function () {
        Route::get('/drs/setting/venue', 'index')->name('drs.setting.venue');
        Route::get('/drs/setting/venue/list', 'list')->name('drs.setting.venue.list');
        Route::get('/drs/setting/venue/get/{id}', 'get')->name('drs.setting.venue.get');
        Route::post('/drs/setting/venue/update', 'update')->name('drs.setting.venue.update');
        Route::delete('/drs/setting/venue/delete/{id}', 'delete')->name('drs.setting.venue.delete');
        Route::post('/drs/setting/venue/store', 'store')->name('drs.setting.venue.store');
    });

    //Event
    Route::controller(EventController::class)->group(function () {
        Route::get('/drs/setting/event', 'index')->name('drs.setting.event');
        Route::get('/drs/setting/event/list', 'list')->name('drs.setting.event.list');
        Route::get('/drs/setting/event/get/{id}', 'get')->name('drs.setting.event.get');
        Route::post('/drs/setting/event/update', 'update')->name('drs.setting.event.update');
        Route::delete('/drs/setting/event/delete/{id}', 'delete')->name('drs.setting.event.delete');
        Route::post('/drs/setting/event/store', 'store')->name('drs.setting.event.store');
    });

    Route::get('/auth/ms-signup', [VmsAuthAdminController::class, 'msSignUp'])->name('auth.ms.signup');
    Route::post('/signup/ms/store', [UserController::class, 'msStore'])->name('admin.signup.ms.store');

    Route::controller(AdminUserController::class)->group(function () {
        Route::get('/vapp/admin/users/profile', 'profile')->name('vapp.admin.users.profile');
        Route::post('/drs/admin/users/profile/update', 'update')->name('drs.admin.users.profile.update');
        Route::post('/drs/admin/users/profile/password/update', 'updatePassword')->name('drs.admin.users.profile.password.update');
        Route::get('/drs/admin/users/invite-user', 'showForm')->name('drs.admin.users.invite.form');
        Route::post('/drs/invite-user', 'sendInvite')->name('drs.admin.users.invite.send');
    });

    //Applicaiton Setting
    Route::controller(AppSettingController::class)->group(function () {
        Route::get('/drs/setting/application', 'index')->name('drs.setting.application');
        Route::get('/drs/setting/application/list', 'list')->name('drs.setting.application.list');
        Route::get('/drs/setting/application/get/{id}', 'get')->name('drs.setting.application.get');
        Route::post('/drs/setting/application/update', 'update')->name('drs.setting.application.update');
        Route::delete('/drs/setting/application/delete/{id}', 'delete')->name('drs.setting.application.delete');
        Route::post('/drs/setting/application/store', 'store')->name('drs.setting.application.store');
    });

    // docs
    Route::get('/event/docs/{document}/download', [EventDocumentController::class, 'download'])
        ->name('event.docs.download');

    Route::delete('/event/docs/{document}', [EventDocumentController::class, 'destroy'])
        ->name('event.docs.destroy');

    Route::controller(AdminUserController::class)->group(function () {
        Route::get('/drs/admin/users/profile', 'profile')->name('admin.users.profile');
        Route::post('/drs/admin/users/profile/update', 'update')->name('admin.users.profile.update');
        Route::post('/drs/admin/users/profile/password/update', 'updatePassword')->name('admin.users.profile.password.update');
        Route::get('/drs/admin/users/invite-user', 'showForm')->name('admin.users.invite.form');
        // Route::post('/drs/invite-user', 'sendInvite')->name('admin.users.invite.send');
    });
});

// Daily Run Sheet — SuperAdmin only list
Route::middleware(['auth', 'otp', 'mutli.event', 'XssSanitizer', 'role:SuperAdmin', 'prevent-back-history', 'auth.session'])->group(function () {
    Route::controller(AdminDailyRunSheetController::class)->group(function () {
        Route::get('/drs/admin/drs', 'index')->name('drs.admin.drs');
        // Route::get('/drs/admin/drs/list', 'list')->name('drs.admin.drs.list');
        Route::get('/drs/admin/show', 'show')->name('drs.admin.show');
        Route::get('/drs/admin/drs/show/list', 'showAdminList')->name('drs.admin.show.list');
        Route::get('/drs/admin/events/{id}/switch',  'switch')->name('drs.admin.event.switch');
    });
});

// shared routes between SuperAdmin and Customer
Route::middleware(['auth', 'otp', 'mutli.event', 'XssSanitizer', 'role:SuperAdmin|Customer',  'prevent-back-history', 'auth.session'])->group(function () {
    // docs used for filepond view inline

    // Event Image
    Route::controller(EventImageController::class)->group(function () {
        Route::get('/drs/setting/event/file/{id}', 'getPrivateFile')->name('drs.setting.event.file');
    });
    // Daily Run Sheets (shared)
    Route::controller(SharedDailyRunSheetController::class)->group(function () {
        Route::get('/drs/drs',                          'index')->name('drs.drs.index');
        Route::get('/drs/drs/list',                     'list')->name('drs.drs.list');
        Route::get('/drs/drs/show/{id}',               'showList')->name('drs.show.list');
        Route::get('/drs/drs/create',                   'create')->name('drs.drs.create');
        Route::post('/drs/drs/store',                   'store')->name('drs.drs.store');
        Route::get('/drs/drs/{id}/get',                 'get')->name('drs.drs.get');
        Route::get('/drs/venue/{id}/matches',           'matchesByVenue')->name('drs.venue.matches');
        Route::get('/drs/drs/{id}',                     'show')->name('drs.drs.show');
        Route::get('/drs/drs/{id}/edit',                'edit')->name('drs.drs.edit');
        Route::post('/drs/drs/update',                  'update')->name('drs.drs.update');
        Route::delete('/drs/drs/destroy/{id}',          'destroy')->name('drs.drs.destroy');
        Route::get('/drs/drs/{id}/export',              'export')->name('drs.drs.export');
        // Items
        Route::get('/drs/drs/{runSheetId}/items/create', 'itemCreate')->name('drs.drs.item.create');
        Route::post('/drs/drs/items/store',             'itemStore')->name('drs.drs.item.store');
        Route::get('/drs/drs/items/{id}/get',           'itemGet')->name('drs.drs.item.get');
        Route::get('/drs/drs/items/{id}/edit',          'itemEdit')->name('drs.drs.item.edit');
        Route::post('/drs/drs/items/update',            'itemUpdate')->name('drs.drs.item.update');
        Route::delete('/drs/drs/items/{id}',            'itemDestroy')->name('drs.drs.item.destroy');
    });

    Route::controller(SharedVenueMatchReportController::class)->group(function () {
        Route::get('/drs/shared/create', 'create')->name('drs.report.create');
        Route::post('/drs/shared/store', 'store')->name('drs.report.store');
        Route::get('/drs/shared/edit/{id}', 'edit')->name('drs.report.edit');
        Route::post('/drs/shared/update', 'update')->name('drs.report.update');
        // Route::delete('/drs/shared/delete/{id}', 'destroy')->name('drs.report.destroy');
        Route::get('/drs/shared/gallery/{id}', 'gallery')->name('drs.report.gallery');
        Route::get('/drs/shared/pdf/{id}', 'reportPdf')->name('drs.report.pdf');
        // export issue log excel
        Route::get('/issue-logs/export/{id}', 'export')->name('issue-logs.export');
        Route::post('/reports/voc/import', 'importAjax')->name('reports.voc.import');
        Route::get('/reports/voc/preview', 'preview')->name('reports.voc.preview');
        // Route::delete('/reports/voc/clear', 'clear')->name('reports.voc.clear');

        // get matches by venue for dynamic dropdown in create/edit form
        Route::get('/ajax/matches-by-venue/{venue}', 'getByVenue')->name('ajax.matches.byVenue');
        Route::get('/ajax/match-details/{match}', 'getDetails')->name('ajax.match.details');
    });

    Route::get('/reports/{report}/images/export', [VenueMatchReportDocumentController::class, 'exportImages'])
        ->name('reports.images.export');

    Route::get('/drs/docs/{document}/download', [VenueMatchReportDocumentController::class, 'download'])
        ->name('drs.docs.download');
    Route::get('/drs/docs/{document}/view.{ext}', [VenueMatchReportDocumentController::class, 'view'])
        ->name('drs.docs.view.ext');

    Route::delete('/drs/docs/{document}', [VenueMatchReportDocumentController::class, 'destroy'])
        ->name('drs.docs.destroy');
});

// Customer specific routes.. without event switching
Route::middleware(['auth', 'otp', 'XssSanitizer',  'role:Customer',  'prevent-back-history', 'auth.session'])->group(function () {
    // used to select venues from event
    Route::get('/drs/events/{event_id}/venues', [VenueMatchReportController::class, 'byEvent'])->name('events.venues');
});

// Customer Report MANAGEMENT ******************************************************************** Customer All Route
Route::middleware(['auth', 'otp', 'mutli.event', 'XssSanitizer',  'role:Customer',  'prevent-back-history', 'auth.session'])->group(function () {

    Route::controller(VenueMatchReportController::class)->group(function () {
        Route::get('/drs/report', 'index')->name('drs.report');
        Route::get('/drs/report/list', 'list')->name('drs.report.list');
        // for event switching
        Route::get('/drs/customer/events/{id}/switch',  'switch')->name('drs.customer.report.switch');
    });
});


// Customer Pick an event
Route::get('/drs/customer/report/pick', function () {
    return view('/drs/customer/report/pick');
})->name('drs.customer.report.pick')->middleware('role:Customer');
Route::post('/drs/customer/events/switch', [VenueMatchReportController::class, 'pickEvent'])->name('drs.customer.report.event.switch')->middleware('role:Customer');

Route::get('/drs/logout', [VmsAuthAdminController::class, 'logout'])->name('drs.logout');


// ****************** ADMIN *********************
Route::group(['middleware' => 'prevent-back-history'], function () {

    // Add User
    Route::get('/drs/auth/signup', [VmsAuthAdminController::class, 'signUp'])->name('auth.signup')->middleware('signed');
    Route::post('/signup/store', [UserController::class, 'store'])->name('admin.signup.store');

    // Add User
    Route::get('/register/{event_id}', [VmsAuthAdminController::class, 'register'])->name('auth.register');
    Route::post('/register/store', [VmsAuthAdminController::class, 'storeRegister'])->name('admin.register.store');

    Route::middleware(['auth', 'prevent-back-history'])->group(function () {

        Route::get('auth/otp', [VmsAuthAdminController::class, 'showOtp'])->name('otp.get');
        Route::post('verify-otp', [VmsAuthAdminController::class, 'verifyOtpAndLogin'])->name('auth.otp.post');
        Route::get('auth/resend', [VmsAuthAdminController::class, 'resendOTP'])->name('otp.resend.get');

        //used to show images in private folder
        Route::get('/doc/{file}', [UtilController::class, 'showImage'])->name('a');

        /*************************************** Play ground */
        // Route::get('/a/{GlobalAttachment}', [UtilController::class, 'serve'])->name('a');
        Route::get('/doc/{file}', [UtilController::class, 'showImage'])->name('a');
        Route::get('/a', function () {
            return response()->file(storage_path('app/private/users/502828276250308124600avatar-2.png'));
        })->name('b');
        /*************************************** End Play ground */


        Route::get('/drs/logout', [VmsAuthAdminController::class, 'logout'])->name('drs.logout');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
    });

    require __DIR__ . '/auth.php';

    Route::middleware(['prevent-back-history'])->group(function () {
        Route::get('/auth/forgot', [AdminController::class, 'forgotPassword'])->name('auth.forgot');
        Route::post('forget-password', [AdminController::class, 'submitForgetPasswordForm'])->name('forgot.password.post');
        Route::get('/auth/reset/{token}', [AdminController::class, 'showResetPasswordForm'])->name('reset.password.get');
        Route::post('reset-password', [AdminController::class, 'submitResetPasswordForm'])->name('reset.password.post');
        Route::get('/send-mail', [SendMailController::class, 'index']);
    });

    Route::middleware(['auth', 'otp', 'XssSanitizer', 'role:SuperAdmin', 'prevent-back-history', 'auth.session'])->group(function () {

        Route::controller(SecurityRoleController::class)->group(function () {
            //Admin User
            Route::get('/sec/adminuser/list', 'listAdminUser')->name('sec.adminuser.list');
            Route::post('updateadminuser', 'updateAdminUser')->name('sec.adminuser.update');
            Route::post('createadminuser', 'createAdminUser')->name('sec.adminuser.create');
            Route::get('/sec/adminuser/{id}/edit', 'editAdminUser')->name('sec.adminuser.edit');
            Route::get('/sec/adminuser/{id}/delete', 'deleteAdminUser')->name('sec.adminuser.delete');
            Route::get('/sec/adminuser/add', 'addAdminUser')->name('sec.adminuser.add');
            Route::get('/sec/adminuser/add2', 'addAdminUser2')->name('sec.adminuser.add2');
        });
    });

    // HR Security Settings all routes
    Route::middleware(['auth', 'otp', 'XssSanitizer', 'role:SecurityRole', 'prevent-back-history', 'auth.session'])->group(function () {

        Route::controller(ActivityAuditController::class)->group(function () {
            Route::get('/sec/audit', 'index')->name('sec.audit');
            Route::get('/sec/audit/list', 'list')->name('sec.audit.list');
        });
        // Roles
        Route::controller(SecurityRoleController::class)->group(function () {

            Route::get('/sec/roles/add', function () {
                return view('/sec/roles/add');
            })->name('sec.roles.add');
            Route::get('/sec/roles/roles/list', 'listRole')->name('sec.roles.list');
            Route::post('updaterole', 'updateRole')->name('sec.roles.update');
            Route::post('createrole', 'createRole')->name('sec.roles.create');
            Route::get('/sec/roles/{id}/edit', 'editRole')->name('sec.roles.edit');
            Route::get('/sec/roles/{id}/delete', 'deleteRole')->name('sec.roles.delete');

            // group
            Route::get('/sec/groups/add', function () {
                return view('/sec/groups/add');
            })->name('sec.groups.add');
            Route::get('/sec/groups/list', 'listGroup')->name('sec.groups.list');
            Route::post('updategroup', 'updateGroup')->name('sec.groups.update');
            Route::post('creategroup', 'createGroup')->name('sec.groups.create');
            Route::get('/sec/groups/{id}/edit', 'editGroup')->name('sec.groups.edit');
            Route::get('/sec/groups/{id}/delete', 'deleteGroup')->name('sec.groups.delete');

            // Permission
            Route::get('/sec/permissions/list', 'listPermission')->name('sec.perm.list');
            Route::post('updatepermission', 'updatePermission')->name('sec.perm.update');
            Route::post('createpermission', 'createPermission')->name('sec.perm.create');
            Route::get('/sec/perm/{id}/edit', 'editPermission')->name('sec.perm.edit');
            Route::get('/sec/perm/{id}/delete', 'deletePermission')->name('sec.perm.delete');
            Route::get('/sec/permissions/add', 'addPermission')->name('sec.perm.add');

            Route::get('/sec/perm/import', 'ImportPermission')->name('sec.perm.import');
            Route::post('importnow', 'ImportNowPermission')->name('sec.perm.import.now');


            // Roles in Permission
            Route::get('/sec/rolesetup/list', 'listRolePermission')->name('sec.rolesetup.list');
            Route::post('updaterolesetup', 'updateRolePermission')->name('sec.rolesetup.update');
            Route::post('createrolesetup', 'createRolePermission')->name('sec.rolesetup.create');
            Route::get('/sec/rolesetup/{id}/edit', 'editRolePermission')->name('sec.rolesetup.edit');
            Route::get('/sec/rolesetup/{id}/delete', 'deleteRolePermission')->name('sec.rolesetup.delete');
            Route::get('/sec/rolesetup/add', 'addRolePermission')->name('sec.rolesetup.add');
        });  //
    });  //
    // Route::get('/run-migration', function () {
    //     Artisan::call('optimize:clear');

    //     Artisan::call('migrate:refresh --seed');
    //     return "Migration executed successfully";
    // });


});
