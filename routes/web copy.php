<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\MicrosoftController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\SendMailController;
// use App\Http\Controllers\Cms\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Vapp\Setting\EventController;
use App\Http\Controllers\GeneralSettings\AttachmentController;
use App\Http\Controllers\GeneralSettings\CompanyAddressController;
use App\Http\Controllers\GeneralSettings\CompanyController;
use App\Http\Controllers\GeneralSettings\CurrencyController;
use App\Http\Controllers\Gms\Admin\AccommodationController;
use App\Http\Controllers\Gms\Admin\FlightController;
use App\Http\Controllers\Gms\Admin\GuestController;
use App\Http\Controllers\Gms\Setting\AirlineController;
use App\Http\Controllers\Gms\Setting\AirportController;
use App\Http\Controllers\Gms\Setting\CabinTypeController;
use App\Http\Controllers\Gms\Setting\ClientGroupController;
use App\Http\Controllers\Gms\Setting\DesignationController;
use App\Http\Controllers\Gms\Setting\FlightStatusController;
use App\Http\Controllers\Gms\Setting\FlightTypeController;
use App\Http\Controllers\Gms\Setting\GuestTypeController;
use App\Http\Controllers\Gms\Setting\HostedByController;
use App\Http\Controllers\Gms\Setting\NationalityController;
use App\Http\Controllers\Vapp\Setting\FunctionalAreaController;

// use App\Http\Controllers\Mds\Admin\DashboardController;
use App\Http\Controllers\Security\ActivityAuditController;
use App\Http\Controllers\Security\RoleController as SecurityRoleController;
use App\Http\Controllers\Vapp\Admin\UserController as AdminUserController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\Vapp\Setting\VehicleTypeController;
use App\Http\Controllers\Vapp\Setting\VenueController;
use App\Http\Controllers\UtilController;
use App\Http\Controllers\Vapp\Admin\BookingController;
use App\Http\Controllers\Vapp\Auth\AdminController as VappAuthAdminController;
use App\Http\Controllers\Vapp\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Vapp\Manager\BookingController as ManagerBookingController;
use App\Http\Controllers\Vapp\Operator\BookingController as OperatorBookingController;
use App\Http\Controllers\Vapp\Setting\AppSettingController;
use App\Http\Controllers\Vapp\Setting\CollectionDetailController;
use App\Http\Controllers\Vapp\Setting\EventImageController;
use App\Http\Controllers\Vapp\Setting\MatchController;
use App\Http\Controllers\Vapp\Setting\ParkingCapacityController;
use App\Http\Controllers\Vapp\Setting\ParkingMasterController;
use App\Http\Controllers\Vapp\Setting\VappInventoryController;
use App\Http\Controllers\Vapp\Setting\VappPrintBatchConroller;
use App\Http\Controllers\Vapp\Setting\VappSizeController;
use App\Http\Controllers\Vapp\Setting\VappVariationController;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;

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
        'SuperAdmin' => 'gms.admin.guest',
        'Customer'   => 'vapp.customer',
        'Operator'   => 'vapp.operator',
    ];

    foreach ($roleRoutes as $role => $route) {
        if (auth()->user()->hasRole($role)) {
            appLog("Redirecting to $route for role $role");
            return redirect()->route($route);
        }
    }

    // Auth::guard('web')->logout();
    // $request->session()->invalidate();
    // $request->session()->regenerateToken();
    // $tenantId = config('services.microsoft.tenant_id'); // from .env
    // $redirectUri = urlencode(route('home')); // or any route you want after logout
    // $microsoftLogoutUrl = Socialite::driver('microsoft')->getLogoutUrl(route('login')); // Replace 'azure' with your Microsoft Socialite driver name if different, and 'login' with your desired redirect URI after Microsoft logout.
    // return redirect($microsoftLogoutUrl);
    abort(403, 'Unauthorized role');
})->name('home');


Route::controller(MicrosoftController::class)->group(function () {
    Route::get('auth/microsoft', 'redirectToMicrosoft')->name('auth.microsoft');
    Route::get('auth/microsoft/callback', 'handleMicrosoftCallback');
});

Route::group(['middleware' => 'prevent-back-history', 'XssSanitizer'], function () {


    // Email Templates
    Route::middleware(['auth', 'otp', 'mutli.event', 'XssSanitizer', 'role:SuperAdmin|SuperMDS', 'prevent-back-history', 'auth.session'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('email-templates', [EmailTemplateController::class, 'index'])
                ->name('email-templates.index');

            Route::get('email-templates/list', [EmailTemplateController::class, 'list'])
                ->name('email-templates.list');

            Route::post('email-templates/{template}/toggle', [EmailTemplateController::class, 'toggle'])
                ->name('email-templates.toggle');

            Route::post('email-templates/{template}/preview', [EmailTemplateController::class, 'preview'])
                ->name('email-templates.preview');

            Route::post('email-templates/{template}/send-test', [EmailTemplateController::class, 'sendTest'])
                ->name('email-templates.sendTest');

            Route::get('email-templates/{template}/edit', [EmailTemplateController::class, 'edit'])
                ->name('email-templates.edit');

            Route::delete('email-templates/{template}', [EmailTemplateController::class, 'destroy'])
                ->name('email-templates.destroy');
        });
    });
    

    // Booking MANAGEMENT ******************************************************************** Admin All Route
    Route::middleware(['auth', 'otp', 'mutli.event', 'XssSanitizer', 'role:SuperAdmin|SuperMDS', 'prevent-back-history', 'auth.session'])->group(function () {

        Route::controller(DashboardController::class)->group(function () {
            Route::get('/mds/admin/dashboard', 'dashboard')->name('mds.admin.dashboard');
        });


        // GMS
        Route::controller(GuestController::class)->group(function () {

            Route::get('/gms/admin/guest/create', function () {
                return view('/gms/admin/guest/createme');
            })->name('gms.admin.guest.create');

            // for event switching
            Route::get('/vapp/admin/events/{id}/switch',  'switch')->name('gms.admin.booking.switch');
            Route::get('/vapp/admin/dashboard', 'dashboard')->name('gms.admin.dashboard');

            // test dynamic email
            Route::get('/gms/admin/guest/test-email', [SendMailController::class, 'testDynamicEmail'])->name('gms.admin.guest.test.email');

            // guest routes
            Route::get('/gms/admin/guest', 'index')->name('gms.admin.guest');
            Route::get('/gms/admin/guest/list', 'list')->name('gms.admin.guest.list');
            Route::get('/gms/admin/guest/detail/{id}', 'detail')->name('gms.admin.guest.detail');
            Route::post('/gms/admin/guest/store', 'store')->name('gms.admin.guest.store');
            Route::post('gms/admin/guest/update', 'update')->name('gms.admin.guest.update');
            Route::delete('/gms/admin/guest/delete/{id}', 'destroy')->name('gms.admin.guest.delete');
            Route::get('/gms/admin/guest/get/{id}', 'get')->name('gms.admin.guest.get');
            Route::get('/gms/admin/guest/mv/get/{id}', 'getView')->name('gms.admin.guest.get.mv');


            //Booking note
            Route::get('/mds/admin/booking/mv/notes/{id}', 'getNotesView')->name('mds.admin.booking.mv.notes');
            Route::post('mds/admin/booking/note/store', 'noteStore')->name('mds.admin.booking.note.store');
            Route::delete('mds/admin/booking/note/delete/{id}', 'deleteNote')->name('mds.admin.booking.note.delete');

            //Booking file upload
            Route::post('mds/admin/booking/file/store', 'fileStore')->name('mds.admin.booking.file.store');
            Route::delete('mds/admin/booking/file/{id}/delete', 'fileDelete')->name('mds.admin.booking.file.delete');
        });

        Route::controller(GuestTypeController::class)->group(function () {
            Route::get('/gms/setting/guest_type', 'index')->name('gms.setting.guest_type');
            Route::get('/gms/setting/guest_type/list', 'list')->name('gms.setting.guest_type.list');
            Route::get('/gms/setting/guest_type/get/{id}', 'get')->name('gms.setting.guest_type.get');
            Route::post('gms/setting/guest_type/update', 'update')->name('gms.setting.guest_type.update');
            Route::delete('/gms/setting/guest_type/delete/{id}', 'delete')->name('gms.setting.guest_type.delete');
            Route::post('/gms/setting/guest_type/store', 'store')->name('gms.setting.guest_type.store');
            Route::get('/gms/setting/guest_type/mv/get/{id}', 'getEventView')->name('gms.setting.guest_type.get.mv');
            // Route::get('/mds/setting/event/file/{file}', 'getPrivateFile')->name('mds.setting.event.file');
        });

        Route::controller(ClientGroupController::class)->group(function () {
            Route::get('/gms/setting/client_group', 'index')->name('gms.setting.client_group');
            Route::get('/gms/setting/client_group/list', 'list')->name('gms.setting.client_group.list');
            Route::get('/gms/setting/client_group/get/{id}', 'get')->name('gms.setting.client_group.get');
            Route::post('gms/setting/client_group/update', 'update')->name('gms.setting.client_group.update');
            Route::delete('/gms/setting/client_group/delete/{id}', 'delete')->name('gms.setting.client_group.delete');
            Route::post('/gms/setting/client_group/store', 'store')->name('gms.setting.client_group.store');
            Route::get('/gms/setting/client_group/mv/get/{id}', 'getEventView')->name('gms.setting.client_group.get.mv');
            // Route::get('/mds/setting/event/file/{file}', 'getPrivateFile')->name('mds.setting.event.file');
        });

        Route::controller(DesignationController::class)->group(function () {
            Route::get('/gms/setting/designation', 'index')->name('gms.setting.designation');
            Route::get('/gms/setting/designation/list', 'list')->name('gms.setting.designation.list');
            Route::get('/gms/setting/designation/get/{id}', 'get')->name('gms.setting.designation.get');
            Route::post('gms/setting/designation/update', 'update')->name('gms.setting.designation.update');
            Route::delete('/gms/setting/designation/delete/{id}', 'delete')->name('gms.setting.designation.delete');
            Route::post('/gms/setting/designation/store', 'store')->name('gms.setting.designation.store');
            Route::get('/gms/setting/designation/mv/get/{id}', 'getEventView')->name('gms.setting.designation.get.mv');
            // Route::get('/mds/setting/event/file/{file}', 'getPrivateFile')->name('mds.setting.event.file');
        });

        Route::controller(NationalityController::class)->group(function () {
            Route::get('/gms/setting/nationality', 'index')->name('gms.setting.nationality');
            Route::get('/gms/setting/nationality/list', 'list')->name('gms.setting.nationality.list');
            Route::get('/gms/setting/nationality/get/{id}', 'get')->name('gms.setting.nationality.get');
            Route::post('gms/setting/nationality/update', 'update')->name('gms.setting.nationality.update');
            Route::delete('/gms/setting/nationality/delete/{id}', 'delete')->name('gms.setting.nationality.delete');
            Route::post('/gms/setting/nationality/store', 'store')->name('gms.setting.nationality.store');
            Route::get('/gms/setting/nationality/mv/get/{id}', 'getEventView')->name('gms.setting.nationality.get.mv');
            // Route::get('/mds/setting/event/file/{file}', 'getPrivateFile')->name('mds.setting.event.file');
        });

        Route::controller(HostedByController::class)->group(function () {
            Route::get('/gms/setting/hosted_by', 'index')->name('gms.setting.hosted_by');
            Route::get('/gms/setting/hosted_by/list', 'list')->name('gms.setting.hosted_by.list');
            Route::get('/gms/setting/hosted_by/get/{id}', 'get')->name('gms.setting.hosted_by.get');
            Route::post('gms/setting/hosted_by/update', 'update')->name('gms.setting.hosted_by.update');
            Route::delete('/gms/setting/hosted_by/delete/{id}', 'delete')->name('gms.setting.hosted_by.delete');
            Route::post('/gms/setting/hosted_by/store', 'store')->name('gms.setting.hosted_by.store');
            Route::get('/gms/setting/hosted_by/mv/get/{id}', 'getEventView')->name('gms.setting.hosted_by.get.mv');
            // Route::get('/mds/setting/event/file/{file}', 'getPrivateFile')->name('mds.setting.event.file');
        });

        // Flight
        Route::controller(FlightController::class)->group(function () {
            Route::get('/gms/admin/flight', 'index')->name('gms.admin.flight');
            Route::get('/gms/admin/flight/list', 'list')->name('gms.admin.flight.list');
            Route::post('/gms/admin/flight/store', 'store')->name('gms.admin.flight.store');
            Route::get('/gms/admin/flight/detail/{id}', 'detail')->name('gms.admin.flight.detail');
        });

        Route::controller(FlightStatusController::class)->group(function () {
            Route::get('/gms/setting/flight_status', 'index')->name('gms.setting.flight_status');
            Route::get('/gms/setting/flight_status/list', 'list')->name('gms.setting.flight_status.list');
            Route::get('/gms/setting/flight_status/get/{id}', 'get')->name('gms.setting.flight_status.get');
            Route::post('gms/setting/flight_status/update', 'update')->name('gms.setting.flight_status.update');
            Route::delete('/gms/setting/flight_status/delete/{id}', 'delete')->name('gms.setting.flight_status.delete');
            Route::post('/gms/setting/flight_status/store', 'store')->name('gms.setting.flight_status.store');
            Route::get('/gms/setting/flight_status/mv/get/{id}', 'getEventView')->name('gms.setting.flight_status.get.mv');
            // Route::get('/mds/setting/event/file/{file}', 'getPrivateFile')->name('mds.setting.event.file');
        });

        Route::controller(AirlineController::class)->group(function () {
            Route::get('/gms/setting/airline', 'index')->name('gms.setting.airline');
            Route::get('/gms/setting/airline/list', 'list')->name('gms.setting.airline.list');
            Route::get('/gms/setting/airline/get/{id}', 'get')->name('gms.setting.airline.get');
            Route::post('gms/setting/airline/update', 'update')->name('gms.setting.airline.update');
            Route::delete('/gms/setting/airline/delete/{id}', 'delete')->name('gms.setting.airline.delete');
            Route::post('/gms/setting/airline/store', 'store')->name('gms.setting.airline.store');
            Route::get('/gms/setting/airline/mv/get/{id}', 'getEventView')->name('gms.setting.airline.get.mv');
            // Route::get('/mds/setting/event/file/{file}', 'getPrivateFile')->name('mds.setting.event.file');
        });

        Route::controller(CabinTypeController::class)->group(function () {
            Route::get('/gms/setting/cabin_type', 'index')->name('gms.setting.cabin_type');
            Route::get('/gms/setting/cabin_type/list', 'list')->name('gms.setting.cabin_type.list');
            Route::get('/gms/setting/cabin_type/get/{id}', 'get')->name('gms.setting.cabin_type.get');
            Route::post('gms/setting/cabin_type/update', 'update')->name('gms.setting.cabin_type.update');
            Route::delete('/gms/setting/cabin_type/delete/{id}', 'delete')->name('gms.setting.cabin_type.delete');
            Route::post('/gms/setting/cabin_type/store', 'store')->name('gms.setting.cabin_type.store');
            Route::get('/gms/setting/cabin_type/mv/get/{id}', 'getEventView')->name('gms.setting.cabin_type.get.mv');
            // Route::get('/mds/setting/event/file/{file}', 'getPrivateFile')->name('mds.setting.event.file');
        });

        Route::controller(FlightTypeController::class)->group(function () {
            Route::get('/gms/setting/flight_type', 'index')->name('gms.setting.flight_type');
            Route::get('/gms/setting/flight_type/list', 'list')->name('gms.setting.flight_type.list');
            Route::get('/gms/setting/flight_type/get/{id}', 'get')->name('gms.setting.flight_type.get');
            Route::post('gms/setting/flight_type/update', 'update')->name('gms.setting.flight_type.update');
            Route::delete('/gms/setting/flight_type/delete/{id}', 'delete')->name('gms.setting.flight_type.delete');
            Route::post('/gms/setting/flight_type/store', 'store')->name('gms.setting.flight_type.store');
            Route::get('/gms/setting/flight_type/mv/get/{id}', 'getEventView')->name('gms.setting.flight_type.get.mv');
            // Route::get('/mds/setting/event/file/{file}', 'getPrivateFile')->name('mds.setting.event.file');
        });

        Route::controller(AirportController::class)->group(function () {
            Route::get('/gms/setting/airport', 'index')->name('gms.setting.airport');
            Route::get('/gms/setting/airport/list', 'list')->name('gms.setting.airport.list');
            Route::get('/gms/setting/airport/get/{id}', 'get')->name('gms.setting.airport.get');
            Route::post('gms/setting/airport/update', 'update')->name('gms.setting.airport.update');
            Route::delete('/gms/setting/airport/delete/{id}', 'delete')->name('gms.setting.airport.delete');
            Route::post('/gms/setting/airport/store', 'store')->name('gms.setting.airport.store');
            Route::get('/gms/setting/airport/mv/get/{id}', 'getEventView')->name('gms.setting.airport.get.mv');
            // Route::get('/mds/setting/event/file/{file}', 'getPrivateFile')->name('mds.setting.event.file');
        });

        // Accomodation
        Route::controller(AccommodationController::class)->group(function () {
            Route::get('/gms/admin/accomm', 'index')->name('gms.admin.accomm');
            Route::get('/gms/admin/accomm/list', 'list')->name('gms.admin.accomm.list');
            Route::post('/gms/admin/accomm/store', 'store')->name('gms.admin.accomm.store');
        });

        //     // Venue
        Route::controller(VenueController::class)->group(function () {
            Route::get('/gms/setting/venue', 'index')->name('gms.setting.venue');
            Route::get('/gms/setting/venue/list', 'list')->name('gms.setting.venue.list');
            Route::get('/gms/setting/venue/get/{id}', 'get')->name('gms.setting.venue.get');
            Route::post('gms/setting/venue/update', 'update')->name('gms.setting.venue.update');
            Route::delete('/gms/setting/venue/delete/{id}', 'delete')->name('gms.setting.venue.delete');
            Route::post('/gms/setting/venue/store', 'store')->name('gms.setting.venue.store');
        });

        // Functional Area
        Route::controller(FunctionalAreaController::class)->group(function () {
            Route::get('/gms/setting/funcareas', 'index')->name('gms.setting.funcareas');
            Route::get('/gms/setting/funcareas/list', 'list')->name('gms.setting.funcareas.list');
            Route::get('/gms/setting/funcareas/get/{id}', 'get')->name('gms.setting.funcareas.get');
            Route::post('gms/setting/funcareas/update', 'update')->name('gms.setting.funcareas.update');
            Route::delete('/gms/setting/funcareas/delete/{id}', 'delete')->name('gms.setting.funcareas.delete');
            Route::post('/gms/setting/funcareas/store', 'store')->name('gms.setting.funcareas.store');
        });

        //Event
        Route::controller(EventController::class)->group(function () {
            Route::get('/gms/setting/event', 'index')->name('gms.setting.event');
            Route::get('/gms/setting/event/list', 'list')->name('gms.setting.event.list');
            Route::get('/gms/setting/event/get/{id}', 'get')->name('gms.setting.event.get');
            Route::post('gms/setting/event/update', 'update')->name('gms.setting.event.update');
            Route::delete('/gms/setting/event/delete/{id}', 'delete')->name('gms.setting.event.delete');
            Route::post('/gms/setting/event/store', 'store')->name('gms.setting.event.store');
        });

        Route::get('/auth/ms-signup', [VappAuthAdminController::class, 'msSignUp'])->name('auth.ms.signup');
        Route::post('/signup/ms/store', [UserController::class, 'msStore'])->name('admin.signup.ms.store');

        Route::controller(AdminUserController::class)->group(function () {
            Route::get('/vapp/admin/users/profile', 'profile')->name('vapp.admin.users.profile');
            Route::post('/vapp/admin/users/profile/update', 'update')->name('vapp.admin.users.profile.update');
            Route::post('/vapp/admin/users/profile/password/update', 'updatePassword')->name('vapp.admin.users.profile.password.update');
            Route::get('/vapp/admin/users/invite-user', 'showForm')->name('vapp.admin.users.invite.form');
            Route::post('/vapp/invite-user', 'sendInvite')->name('vapp.admin.users.invite.send');
        });

        //Applicaiton Setting
        Route::controller(AppSettingController::class)->group(function () {
            Route::get('/vapp/setting/application', 'index')->name('vapp.setting.application');
            Route::get('/vapp/setting/application/list', 'list')->name('vapp.setting.application.list');
            Route::get('/vapp/setting/application/get/{id}', 'get')->name('vapp.setting.application.get');
            Route::post('vapp/setting/application/update', 'update')->name('vapp.setting.application.update');
            Route::delete('/vapp/setting/application/delete/{id}', 'delete')->name('vapp.setting.application.delete');
            Route::post('/vapp/setting/application/store', 'store')->name('vapp.setting.application.store');
        });

        // Event Image
        Route::controller(EventImageController::class)->group(function () {
            Route::get('/vapp/setting/event/file/{file}', 'getPrivateFile')->name('gms.setting.event.file');
        });

        Route::controller(AdminUserController::class)->group(function () {
            Route::get('/vapp/admin/users/profile', 'profile')->name('admin.users.profile');
            Route::post('/vapp/admin/users/profile/update', 'update')->name('admin.users.profile.update');
            Route::post('/vapp/admin/users/profile/password/update', 'updatePassword')->name('admin.users.profile.password.update');
            Route::get('/vapp/admin/users/invite-user', 'showForm')->name('admin.users.invite.form');
            Route::post('/vapp/invite-user', 'sendInvite')->name('admin.users.invite.send');
        });
    });
    // Booking MANAGEMENT ******************************************************************** Admin All Route
    // 'roles:admin',
    // Route::middleware(['auth', 'otp', 'mutli.event', 'XssSanitizer', 'firstlogin', 'role:SuperAdmin',  'prevent-back-history', 'auth.session'])->group(function () {

    //     // Route::controller(DashboardController::class)->group(function () {
    //     //     Route::get('/cms/admin/dashboard', 'dashboard')->name('cms.admin.dashboard');
    //     // });

    //     Route::get('/auth/ms-signup', [VappAuthAdminController::class, 'msSignUp'])->name('auth.ms.signup');
    //     Route::post('/signup/ms/store', [UserController::class, 'msStore'])->name('admin.signup.ms.store');

    //     //Applicaiton Setting
    //     Route::controller(AppSettingController::class)->group(function () {
    //         Route::get('/vapp/setting/application', 'index')->name('vapp.setting.application');
    //         Route::get('/vapp/setting/application/list', 'list')->name('vapp.setting.application.list');
    //         Route::get('/vapp/setting/application/get/{id}', 'get')->name('vapp.setting.application.get');
    //         Route::post('vapp/setting/application/update', 'update')->name('vapp.setting.application.update');
    //         Route::delete('/vapp/setting/application/delete/{id}', 'delete')->name('vapp.setting.application.delete');
    //         Route::post('/vapp/setting/application/store', 'store')->name('vapp.setting.application.store');
    //     });

    //     Route::controller(BookingController::class)->group(function () {
    //         Route::get('/vapp/admin', 'index')->name('vapp.admin');
    //         Route::get('/vapp/admin/booking', 'index')->name('vapp.admin.booking');
    //         Route::get('/vapp/admin/booking/list', 'list')->name('vapp.admin.booking.list');
    //         Route::get('/vapp/admin/booking/create', 'create')->name('vapp.admin.booking.create');
    //         Route::delete('/vapp/admin/booking/delete/{id}', 'delete')->name('vapp.admin.booking.delete');
    //         Route::get('/vapp/admin/booking/request/{id}', 'showRequest')->name('vapp.admin.booking.request');
    //         Route::post('/vapp/admin/booking/request/save', 'saveRequest')->name('vapp.admin.booking.request.save');
    //         Route::get('vapp/admin/booking/edit/{id}', 'edit')->name('vapp.admin.booking.edit');
    //         Route::post('/vapp/admin/request/store', 'store')->name('vapp.admin.request.store');
    //         Route::post('/vapp/admin/booking/export', 'export')->name('vapp.admin.booking.export');

    //         // for event switching
    //         Route::get('/vapp/admin/events/{id}/switch',  'switch')->name('vapp.admin.booking.switch');
    //         Route::get('/vapp/admin/dashboard', 'dashboard')->name('vapp.admin.dashboard');

    //         // update booking status
    //         Route::post('/vapp/admin/booking/status/update', 'updateStatus')->name('vapp.admin.booking.status.update');
    //         Route::get('/vapp/admin/booking/status/edit/{id}', 'editStatus')->name('vapp.admin.booking.status.edit');
    //     });

    //     Route::controller(VappSizeController::class)->group(function () {
    //         // Vehicle Type
    //         Route::get('/vapp/setting/vapp_size', 'index')->name('vapp.setting.vapp_size');
    //         Route::get('/vapp/setting/vapp_size/list', 'list')->name('vapp.setting.vapp_size.list');
    //         Route::get('/vapp/setting/vapp_size/get/{id}', 'get')->name('vapp.setting.vapp_size.get');
    //         Route::post('vapp/setting/vapp_size/update', 'update')->name('vapp.setting.vapp_size.update');
    //         Route::delete('/vapp/setting/vapp_size/delete/{id}', 'delete')->name('vapp.setting.vapp_size.delete');
    //         Route::post('/vapp/setting/vapp_size/store', 'store')->name('vapp.setting.vapp_size.store');
    //     });

    //     Route::controller(VehicleTypeController::class)->group(function () {
    //         // Vehicle Type
    //         Route::get('/vapp/setting/vehicle_type', 'index')->name('vapp.setting.vehicle_type');
    //         Route::get('/vapp/setting/vehicle_type/list', 'list')->name('vapp.setting.vehicle_type.list');
    //         Route::get('/vapp/setting/vehicle_type/get/{id}', 'get')->name('vapp.setting.vehicle_type.get');
    //         Route::post('vapp/setting/vehicle_type/update', 'update')->name('vapp.setting.vehicle_type.update');
    //         Route::delete('/vapp/setting/vehicle_type/delete/{id}', 'delete')->name('vapp.setting.vehicle_type.delete');
    //         Route::post('/vapp/setting/vehicle_type/store', 'store')->name('vapp.setting.vehicle_type.store');
    //     });

    //     // Parking Master
    //     Route::controller(ParkingMasterController::class)->group(function () {
    //         Route::get('/vapp/setting/parking/master', 'index')->name('vapp.setting.parking.master');
    //         Route::get('/vapp/setting/parking/master/list', 'list')->name('vapp.setting.parking.master.list');
    //         Route::get('/vapp/setting/parking/master/get/{id}', 'get')->name('vapp.setting.parking.master.get');
    //         Route::post('vapp/setting/parking/master/update', 'update')->name('vapp.setting.parking.master.update');
    //         Route::delete('/vapp/setting/parking/master/delete/{id}', 'delete')->name('vapp.setting.parking.master.delete');
    //         Route::post('/vapp/setting/parking/master/store', 'store')->name('vapp.setting.parking.master.store');
    //         Route::get('/vapp/setting/parking/master/mv/get/{id}', 'getView')->name('vapp.setting.parking.master.get.mv');
    //     });

    //     // VAPP Variation
    //     Route::controller(VappVariationController::class)->group(function () {
    //         Route::get('/vapp/setting/parking/variation', 'index')->name('vapp.setting.parking.variation');
    //         Route::get('/vapp/setting/parking/variation/list', 'list')->name('vapp.setting.parking.variation.list');
    //         Route::get('/vapp/setting/parking/variation/get/{id}', 'get')->name('vapp.setting.parking.variation.get');
    //         Route::post('vapp/setting/parking/variation/update', 'update')->name('vapp.setting.parking.variation.update');
    //         Route::delete('/vapp/setting/parking/variation/delete/{id}', 'delete')->name('vapp.setting.parking.variation.delete');
    //         Route::post('/vapp/setting/parking/variation/store', 'store')->name('vapp.setting.parking.variation.store');
    //         // add inventroy to this variation
    //         Route::post('/vapp/setting/inventory/variation/store', 'inventory_store')->name('vapp.setting.inventory.variation.store');
    //         Route::get('/vapp/setting/inventory/variation/get/{id}', 'get_inventory_variation_info')->name('vapp.setting.inventory.variation.get');
    //         // end inventroy to this variation

    //         Route::get('/vapp/setting/parking/variation/mv/get/{id}', 'getView')->name('vapp.setting.parking.variation.get.mv');

    //         // functional areas and vapp sizes associated with parking code
    //         Route::get('/vapp/setting/parking/code/functional_areas/{id}', 'getAssicatedFunctionalAreas')->name('vapp.setting.parking.code.functional_areas');
    //         Route::get('vapp_get_parking_code_from_event/{id}', 'getParkingCodeFromEvent')->name('vapp.setting.parking.code.get_from_event');
    //     });

    //     // VAPP Print Batch
    //     Route::controller(VappPrintBatchConroller::class)->group(function () {
    //         Route::get('/vapp/setting/print/batch', 'index')->name('vapp.setting.print.batch');
    //         Route::get('/vapp/setting/print/batch/list', 'list')->name('vapp.setting.print.batch.list');
    //         Route::get('/vapp/setting/print/batch/get/{id}', 'get')->name('vapp.setting.print.batch.get');
    //         Route::post('vapp/setting/print/batch/update', 'update')->name('vapp.setting.print.batch.update');
    //         Route::delete('/vapp/setting/print/batch/delete/{id}', 'delete')->name('vapp.setting.print.batch.delete');
    //         Route::post('/vapp/setting/print/batch/store', 'store')->name('vapp.setting.print.batch.store');
    //         Route::get('/vapp/setting/print/batch/mv/get/{id}', 'getView')->name('vapp.setting.print.batch.get.mv');

    //         // functional areas associated with parking code
    //         Route::get('/vapp/setting/print/batch/vapp_sizes/{id}', 'getAssicatedVaapSizes')->name('vapp.setting.print.batch.vapp_sizes');
    //     });

    //     // Parking Capacity
    //     Route::controller(ParkingCapacityController::class)->group(function () {
    //         Route::get('/vapp/setting/parking', 'index')->name('vapp.setting.parking');
    //         Route::get('/vapp/setting/parking/list', 'list')->name('vapp.setting.parking.list');
    //         Route::get('/vapp/setting/parking/get/{id}', 'get')->name('vapp.setting.parking.get');
    //         Route::post('vapp/setting/parking/update', 'update')->name('vapp.setting.parking.update');
    //         Route::delete('/vapp/setting/parking/delete/{id}', 'delete')->name('vapp.setting.parking.delete');
    //         Route::post('/vapp/setting/parking/store', 'store')->name('vapp.setting.parking.store');
    //         Route::get('/vapp/setting/parking/mv/get/{id}', 'getParkingView')->name('vapp.setting.parking.get.mv');
    //     });

    //     // Matches
    //     Route::controller(MatchController::class)->group(function () {
    //         Route::get('/vapp/setting/match', 'index')->name('vapp.setting.match');
    //         Route::get('/vapp/setting/match/list', 'list')->name('vapp.setting.match.list');
    //         Route::get('/vapp/setting/match/get/{id}', 'get')->name('vapp.setting.match.get');
    //         Route::post('vapp/setting/match/update', 'update')->name('vapp.setting.match.update');
    //         Route::delete('/vapp/setting/match/delete/{id}', 'delete')->name('vapp.setting.match.delete');
    //         Route::post('/vapp/setting/match/store', 'store')->name('vapp.setting.match.store');
    //         Route::get('/vapp/setting/match/mv/get/{id}', 'getMatchView')->name('vapp.setting.match.get.mv');
    //     });

    //     // VAPP Inventory
    //     Route::controller(VappInventoryController::class)->group(function () {
    //         Route::get('/vapp/setting/inventory', 'index')->name('vapp.setting.inventory');
    //         Route::get('/vapp/setting/inventory/list', 'list')->name('vapp.setting.inventory.list');
    //         Route::get('/vapp/setting/inventory/get/{id}', 'get')->name('vapp.setting.inventory.get');
    //         Route::post('vapp/setting/inventory/update', 'update')->name('vapp.setting.inventory.update');
    //         Route::delete('/vapp/setting/inventory/delete/{id}', 'delete')->name('vapp.setting.inventory.delete');
    //         Route::post('/vapp/setting/inventory/store', 'store')->name('vapp.setting.inventory.store');
    //         Route::get('/vapp/setting/inventory/mv/get/{id}', 'getVappInventoryView')->name('vapp.setting.inventory.get.mv');
    //     });


    //     // Venue
    //     Route::controller(VenueController::class)->group(function () {
    //         Route::get('/vapp/setting/venue', 'index')->name('vapp.setting.venue');
    //         Route::get('/vapp/setting/venue/list', 'list')->name('vapp.setting.venue.list');
    //         Route::get('/vapp/setting/venue/get/{id}', 'get')->name('vapp.setting.venue.get');
    //         Route::post('vapp/setting/venue/update', 'update')->name('vapp.setting.venue.update');
    //         Route::delete('/vapp/setting/venue/delete/{id}', 'delete')->name('vapp.setting.venue.delete');
    //         Route::post('/vapp/setting/venue/store', 'store')->name('vapp.setting.venue.store');
    //     });

    //     // Collection Location
    //     Route::controller(CollectionDetailController::class)->group(function () {
    //         Route::get('/vapp/setting/collection', 'index')->name('vapp.setting.collection');
    //         Route::get('/vapp/setting/collection/list', 'list')->name('vapp.setting.collection.list');
    //         Route::get('/vapp/setting/collection/get/{id}', 'get')->name('vapp.setting.collection.get');
    //         Route::post('vapp/setting/collection/update', 'update')->name('vapp.setting.collection.update');
    //         Route::delete('/vapp/setting/collection/delete/{id}', 'delete')->name('vapp.setting.collection.delete');
    //         Route::post('/vapp/setting/collection/store', 'store')->name('vapp.setting.collection.store');
    //     });

    //     // Functional Area
    //     Route::controller(FunctionalAreaController::class)->group(function () {
    //         Route::get('/vapp/setting/funcareas', 'index')->name('vapp.setting.funcareas');
    //         Route::get('/vapp/setting/funcareas/list', 'list')->name('vapp.setting.funcareas.list');
    //         Route::get('/vapp/setting/funcareas/get/{id}', 'get')->name('vapp.setting.funcareas.get');
    //         Route::post('vapp/setting/funcareas/update', 'update')->name('vapp.setting.funcareas.update');
    //         Route::delete('/vapp/setting/funcareas/delete/{id}', 'delete')->name('vapp.setting.funcareas.delete');
    //         Route::post('/vapp/setting/funcareas/store', 'store')->name('vapp.setting.funcareas.store');
    //     });

    //     //Event
    //     Route::controller(EventController::class)->group(function () {
    //         Route::get('/vapp/setting/event', 'index')->name('vapp.setting.event');
    //         Route::get('/vapp/setting/event/list', 'list')->name('vapp.setting.event.list');
    //         Route::get('/vapp/setting/event/get/{id}', 'get')->name('vapp.setting.event.get');
    //         Route::post('vapp/setting/event/update', 'update')->name('vapp.setting.event.update');
    //         Route::delete('/vapp/setting/event/delete/{id}', 'delete')->name('vapp.setting.event.delete');
    //         Route::post('/vapp/setting/event/store', 'store')->name('vapp.setting.event.store');
    //     });


    //     Route::controller(AdminUserController::class)->group(function () {
    //         Route::get('/vapp/admin/users/profile', 'profile')->name('vapp.admin.users.profile');
    //         Route::post('/vapp/admin/users/profile/update', 'update')->name('vapp.admin.users.profile.update');
    //         Route::post('/vapp/admin/users/profile/password/update', 'updatePassword')->name('vapp.admin.users.profile.password.update');
    //         Route::get('/vapp/admin/users/invite-user', 'showForm')->name('vapp.admin.users.invite.form');
    //         Route::post('/vapp/invite-user', 'sendInvite')->name('vapp.admin.users.invite.send');
    //     });


    //     // General Settings MANAGEMENT ******************************************************************** Admin All Route
    //     // company Routes
    //     Route::controller(CompanyController::class)->group(function () {
    //         Route::get('/general/settings/company/', 'index')->name('general.settings.company');
    //         Route::post('/general/settings/update', 'update')->name('general.settings.update');
    //     });

    //     // Address Routes
    //     Route::controller(CompanyAddressController::class)->group(function () {
    //         Route::get('/general/settings/address/', 'index')->name('general.settings.address');
    //         Route::get('/general/settings/address/list/{id?}', 'list')->name('general.settings.address.list');
    //         Route::get('/general/settings/address/mv/edit/{id}', 'getAddressEditView')->name('general.settings.address.mv.edit');
    //         Route::post('/general/settings/address/update',  'update')->name('general.settings.address.update');
    //         Route::get('/general/settings/address/add', 'add')->name('general.settings.address.add');
    //         Route::post('/general/settings/address/store', 'store')->name('general.settings.address.store');
    //         Route::get('general/settings/address/delete/{id}', 'delete')->name('general.settings.address.delete');
    //     });

    //     // Currency Routes
    //     Route::controller(CurrencyController::class)->group(function () {
    //         Route::get('/general/settings/currency/', 'index')->name('general.settings.currency');
    //         Route::get('/general/settings/currency/list/{id?}', 'list')->name('general.settings.currency.list');
    //         Route::get('/general/settings/currency/get/{id}', 'get')->name('general.settings.currency.get');
    //         Route::post('/general/settings/currency/update',  'update')->name('general.settings.currency.update');
    //         Route::get('/general/settings/currency/add', 'add')->name('general.settings.currency.add');
    //         Route::post('/general/settings/currency/store', 'store')->name('general.settings.currency.store');
    //         Route::get('general/settings/currency/delete/{id}', 'delete')->name('general.settings.currency.delete');
    //     });
    // });

    // // Routes that are shared between roles used for dependencies
    // Route::middleware(['auth', 'otp', 'mutli.event', 'XssSanitizer', 'firstlogin', 'role:SuperAdmin|Customer',  'prevent-back-history', 'auth.session'])->group(function () {
    //     // used to get list dependencies
    //     Route::controller(BookingController::class)->group(function () {
    //         Route::get('/vapp/customer/booking/get/match/{id}', 'getMatchesFromVappVenue')->name('vapp.booking.get.match');
    //         // Route::get('/get-matches', 'getMatchesFromVappVenue')->name('vapp.booking.get.matches');
    //         Route::get('/get-catergory', 'getVariationsFromParkingCode')->name('vapp.booking.get.category');
    //         Route::get('/get-matches', 'getMatchesFromMatchCategory')->name('vapp.booking.get.matches');
    //         Route::get('/get-venues', 'getVenuesFromMatch')->name('vapp.booking.get.venues');
    //         Route::get('/get-variation', 'getVariation')->name('vapp.booking.get.variation');
    //         Route::get('/get-pariking-by-fa', 'getParkingCodeByFa')->name('vapp.booking.get.parking.by.fa');
    //         Route::get('/get-parking-color', 'getParkingColor')->name('vapp.booking.get.color');
    //         Route::post('/vapp/admin/booking/export', 'export')->name('vapp.admin.booking.export');

    //     });

    //     // Event Image
    //     Route::controller(EventImageController::class)->group(function () {
    //         Route::get('/vapp/setting/event/file/{file}', 'getPrivateFile')->name('vapp.setting.event.file');
    //     });
    // });

    // Route::middleware(['auth', 'otp', 'mutli.event', 'XssSanitizer', 'firstlogin', 'role:Manager',  'prevent-back-history', 'auth.session'])->group(function () {

    //     Route::controller(ManagerBookingController::class)->group(function () {
    //         Route::get('/vapp/manager', 'index')->name('vapp.manager');
    //         Route::get('/vapp/manager/booking', 'index')->name('vapp.manager.booking');
    //         Route::get('/vapp/manager/booking/list', 'list')->name('vapp.manager.booking.list');
    //         Route::get('/vapp/manager/booking/create', 'create')->name('vapp.manager.booking.create');
    //         Route::delete('/vapp/manager/booking/delete/{id}', 'delete')->name('vapp.manager.booking.delete');
    //         Route::post('/vapp/manager/request/store', 'store')->name('vapp.manager.request.store');
    //         Route::get('/vapp/manager/booking/request/{id}', 'showRequest')->name('vapp.manager.booking.request');
    //         Route::post('/vapp/manager/booking/request/save', 'saveRequest')->name('vapp.manager.booking.request.save');

    //         // for event switching
    //         Route::get('/vapp/manager/events/{id}/switch',  'switch')->name('vapp.manager.booking.switch');
    //         // Route::get('/vapp/manager/dashboard', 'dashboard')->name('vapp.manager.dashboard');
    //     });
    // });
    // Route::middleware(['auth', 'otp', 'mutli.event', 'XssSanitizer', 'firstlogin', 'role:Customer',  'prevent-back-history', 'auth.session'])->group(function () {

    //     // Route::controller(DashboardController::class)->group(function () {
    //     //     Route::get('/cms/admin/dashboard', 'dashboard')->name('cms.admin.dashboard');
    //     // });

    //     Route::controller(CustomerBookingController::class)->group(function () {
    //         Route::get('/vapp/customer', 'index')->name('vapp.customer');
    //         Route::get('/vapp/customer/booking', 'index')->name('vapp.customer.booking');
    //         Route::get('/vapp/customer/booking/list', 'list')->name('vapp.customer.booking.list');
    //         Route::get('/vapp/customer/booking/create', 'create')->name('vapp.customer.booking.create');
    //         Route::delete('/vapp/customer/booking/delete/{id}', 'delete')->name('vapp.customer.booking.delete');
    //         Route::post('/vapp/customer/request/store', 'store')->name('vapp.customer.request.store');

    //         // for event switching
    //         Route::get('/vapp/customer/events/{id}/switch',  'switch')->name('vapp.customer.booking.switch');
    //         // Route::get('/vapp/customer/dashboard', 'dashboard')->name('vapp.customer.dashboard');
    //     });
    // });
    // Route::middleware(['auth', 'otp', 'mutli.event', 'XssSanitizer', 'firstlogin', 'role:Operator',  'prevent-back-history', 'auth.session'])->group(function () {

    //     Route::controller(OperatorBookingController::class)->group(function () {
    //         Route::get('/vapp/operator', 'index')->name('vapp.operator');
    //         Route::get('/vapp/operator/booking', 'index')->name('vapp.operator.booking');
    //         Route::get('/vapp/operator/booking/list', 'list')->name('vapp.operator.booking.list');
    //         Route::post('/vapp/operator/rfc/status', 'updateStatus')->name('vapp.operator.rfc.status');
    //         Route::post('/generate-pdf', 'generate')->name('vapp.pdf.receipt');
    //         Route::post('/mark-as-collected', 'markAsCollected')->name('vapp.mark.collected');

    //         // for event switching
    //         Route::get('/vapp/operator/events/{id}/switch',  'switch')->name('vapp.operator.booking.switch');
    //         // Route::get('/vapp/operator/dashboard', 'dashboard')->name('vapp.operator.dashboard');
    //     });
    // });
// });


// ****************** ADMIN *********************
Route::group(['middleware' => 'prevent-back-history'], function () {

    // Add User
    Route::get('/vapp/auth/signup', [VappAuthAdminController::class, 'signUp'])->name('auth.signup')->middleware('signed');;
    Route::post('/signup/store', [UserController::class, 'store'])->name('admin.signup.store');

    Route::middleware(['auth', 'prevent-back-history'])->group(function () {

        Route::get('auth/otp', [VappAuthAdminController::class, 'showOtp'])->name('otp.get');
        Route::post('verify-otp', [VappAuthAdminController::class, 'verifyOtpAndLogin'])->name('auth.otp.post');
        Route::get('auth/resend', [VappAuthAdminController::class, 'resendOTP'])->name('otp.resend.get');

        //used to show images in private folder
        Route::get('/doc/{file}', [UtilController::class, 'showImage'])->name('a');

        /*************************************** Play ground */
        // Route::get('/a/{GlobalAttachment}', [UtilController::class, 'serve'])->name('a');
        Route::get('/doc/{file}', [UtilController::class, 'showImage'])->name('a');
        Route::get('/a', function () {
            return response()->file(storage_path('app/private/users/502828276250308124600avatar-2.png'));
        })->name('b');
        /*************************************** End Play ground */

        // Admin Booking Pick an event
        Route::get('/vapp/admin/booking/pick', function () {
            return view('/vapp/admin/booking/pick');
        })->name('vapp.admin.booking.pick')->middleware('role:SuperAdmin');
        Route::post('/vapp/admin/events/switch', [BookingController::class, 'pickEvent'])->name('vapp.admin.booking.event.switch')->middleware('role:SuperAdmin');

        // Customer Booking Pick an event
        Route::get('/vapp/customer/booking/pick', function () {
            return view('/vapp/customer/booking/pick');
        })->name('vapp.customer.booking.pick')->middleware('role:Customer');
        Route::post('/vapp/customer/events/switch', [CustomerBookingController::class, 'pickEvent'])->name('vapp.customer.booking.event.switch')->middleware('role:Customer');

        // Operator Booking Pick an event
        Route::get('/vapp/operator/booking/pick', function () {
            return view('/vapp/operator/booking/pick');
        })->name('vapp.operator.booking.pick')->middleware('role:Operator');
        Route::post('/vapp/operator/events/switch', [OperatorBookingController::class, 'pickEvent'])->name('vapp.operator.booking.event.switch')->middleware('role:Operator');


        Route::get('/vapp/logout', [VappAuthAdminController::class, 'logout'])->name('vapp.logout');
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
