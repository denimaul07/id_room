<?php
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | API Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register API routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | is assigned the "api" middleware group. Enjoy building your API!
 * |
 */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/auth/verify-email/{id}/{hash}', [UserController::class, 'verifyEmail'])
    ->middleware('signed')
    ->name('verification.verify');
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->get('/', function () {
        return 'Hello Api';
    });
    $api->group(['prefix' => 'auth'], function ($api) {
        $api->post('/signup', 'App\Http\Controllers\UserController@store');
        $api->post('/register', 'App\Http\Controllers\UserController@register');
        $api->post('/contactAgent', 'App\Http\Controllers\UserController@contactAgent');
        $api->group(['middleware' => 'login.attempt.limiter'], function ($api) {
            $api->post('/login', 'App\Http\Controllers\Auth\AuthController@login');
            $api->post('/login_apps', 'App\Http\Controllers\Auth\AuthController@loginApps');
        });

        $api->post('/token/refresh', 'App\Http\Controllers\Auth\RefreshTokenController@refresh');
        $api->post('/token/refresh_apps', 'App\Http\Controllers\Auth\RefreshTokenController@refreshApps');
        $api->post('/logout', 'App\Http\Controllers\Auth\RefreshTokenController@logout');
        $api->post('/forgot-password', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail');
        $api->post('/reset_password', 'App\Http\Controllers\Auth\ResetPasswordController@reset');
    });

    $api->group(['prefix' => 'member','middleware' => ['jwt.auth', 'role:users']], function ($api) {
        $api->post('/update-profile', 'App\Http\Controllers\Admin\AdminUserController@updateProfile');
    });

    $api->group(['prefix' => 'myAccount','middleware' => ['jwt.auth', 'role:superAdmin|admin']], function ($api) {
        $api->put('/update_profile', 'App\Http\Controllers\Admin\AdminUserController@update_Profile');
        $api->put('/update_wa', 'App\Http\Controllers\Admin\AdminUserController@updateWa');
        $api->post('/send_wa', 'App\Http\Controllers\Admin\AdminUserController@sendWa');
    });

    $api->group(['prefix' => 'public'], function ($api) {
        $api->get('/info', 'App\Http\Controllers\PublicController@info');
        $api->post('/contact-me', 'App\Http\Controllers\PublicController@contactMe');
        $api->get('/province', 'App\Http\Controllers\PublicController@getProvince');
        $api->get('/city', 'App\Http\Controllers\PublicController@getCity');
        $api->get('/list-city', 'App\Http\Controllers\PublicController@listCity');
        $api->get('/properties', 'App\Http\Controllers\PublicController@listProperties');
        $api->get('/properties-sewa', 'App\Http\Controllers\PublicController@listPropertiesSewa');
        $api->get('/properties-jual', 'App\Http\Controllers\PublicController@listPropertiesJual');
        $api->get('/properties-facilities', 'App\Http\Controllers\PublicController@listPropertiesFacilities');
        $api->get('/property-detail', 'App\Http\Controllers\PublicController@propertyDetail');
        $api->get('/property-detail-sell', 'App\Http\Controllers\PublicController@propertyDetailSell');
        $api->get('/popular-city', 'App\Http\Controllers\PublicController@popularCity');
        $api->get('/kode-negara', 'App\Http\Controllers\PublicController@kodeNegara');
        $api->get('/properties_booking', 'App\Http\Controllers\PublicController@properties_booking');

        $api->group(['middleware' => ['jwt.auth', 'role:users']], function ($api) {
            $api->post('/proses_booking', 'App\Http\Controllers\PublicController@prosesBooking');
            $api->get('/coupons_booking', 'App\Http\Controllers\PublicController@couponsBooking');
            $api->get('/cek_coupon', 'App\Http\Controllers\PublicController@cekCoupon');
            $api->post('/tukar_point', 'App\Http\Controllers\PublicController@tukarPoint');
            $api->get('/me', 'App\Http\Controllers\PublicController@me');
        });
    });

    $api->group(['prefix' => 'master', 'middleware' => ['jwt.auth']], function ($api) {
        $api->get('/get_department', 'App\Http\Controllers\Admin\DepartmentController@index');
        $api->get('/department', 'App\Http\Controllers\Admin\DepartmentController@getDepartment');
        $api->get('/roles', 'App\Http\Controllers\Admin\RoleController@index');
    });

    $api->group(['prefix' => 'activity_logs', 'middleware' => ['jwt.auth', 'role:superAdmin']], function ($api) {
        $api->get('/index', 'App\Http\Controllers\ActivityLogController@index');
        $api->get('/statistics', 'App\Http\Controllers\ActivityLogController@statistics');
        $api->get('/user_logs', 'App\Http\Controllers\ActivityLogController@userLogs');
        $api->get('/show', 'App\Http\Controllers\ActivityLogController@show');
        $api->get('/by_log_name', 'App\Http\Controllers\ActivityLogController@byLogName');
        $api->get('/critical', 'App\Http\Controllers\ActivityLogController@critical');
        $api->delete('/destroy', 'App\Http\Controllers\ActivityLogsController@destroy');
        $api->delete('/clear_all', 'App\Http\Controllers\ActivityLogsController@clearAll');
    });

    $api->group(['prefix' => 'membership', 'middleware' => ['jwt.auth']], function ($api) {
        $api->group(['middleware' => ['jwt.auth', 'role:users']], function ($api) {
            $api->get('/my-membership', 'App\Http\Controllers\MembershipController@myMembership');
            $api->get('/my-transactions', 'App\Http\Controllers\MembershipController@myTransactions');
            $api->get('/my-booking', 'App\Http\Controllers\MembershipController@myBooking');
            $api->get('/list-membership', 'App\Http\Controllers\MembershipController@listMembership');
            $api->get('/list-transactions', 'App\Http\Controllers\MembershipController@listTransactions');
            $api->get('/list-booking', 'App\Http\Controllers\MembershipController@listBooking');
            $api->get('/printInvoice', 'App\Http\Controllers\MembershipController@printInvoice');
            $api->post('/subscribe', 'App\Http\Controllers\MembershipController@subscribe');
            $api->post('/webhook', 'App\Http\Controllers\MembershipController@webhook');

        });

        $api->group(['middleware' => ['jwt.auth', 'role:superAdmin|admin']], function ($api) {
            $api->get('/index', 'App\Http\Controllers\MembershipController@index');
            $api->post('/index', 'App\Http\Controllers\MembershipController@store');
            $api->put('/index', 'App\Http\Controllers\MembershipController@update');
        });
    });

    $api->group(['prefix' => 'membership_benefit', 'middleware' => ['jwt.auth', 'role:superAdmin|admin']], function ($api) {
    
        $api->get('/index', 'App\Http\Controllers\MembershipBenefitController@index');
        $api->post('/index', 'App\Http\Controllers\MembershipBenefitController@store');
        $api->get('/getBenefit', 'App\Http\Controllers\MembershipBenefitController@show');
        $api->put('/index', 'App\Http\Controllers\MembershipBenefitController@update');
        $api->delete('/index', 'App\Http\Controllers\MembershipBenefitController@delete');
    
    });

    $api->group(['prefix' => 'properties', 'middleware' => ['jwt.auth', 'role:superAdmin|admin|properties']], function ($api) {
        $api->get('/index', 'App\Http\Controllers\PropertiesController@index');
        $api->post('/store', 'App\Http\Controllers\PropertiesController@store');
        $api->post('/update', 'App\Http\Controllers\PropertiesController@update');
        $api->delete('/index', 'App\Http\Controllers\PropertiesController@destroy');
        $api->get('/city', 'App\Http\Controllers\PropertiesController@getCity');
        $api->post('/updatePopularCity', 'App\Http\Controllers\PropertiesController@updatePopularCity');
        $api->get('/get_properties', 'App\Http\Controllers\PropertiesController@getProperties');
    });

    $api->group(['prefix' => 'rooms', 'middleware' => ['jwt.auth']], function ($api) {
        $api->get('/index', 'App\Http\Controllers\RoomController@index');
        $api->post('/store', 'App\Http\Controllers\RoomController@store');
        $api->post('/update', 'App\Http\Controllers\RoomController@update');
        $api->delete('/index', 'App\Http\Controllers\RoomController@destroy');
        $api->get('/getSubRoom', 'App\Http\Controllers\RoomController@getSubRoom');
        $api->post('/storeSubRoom', 'App\Http\Controllers\RoomController@storeSubRoom');
        $api->put('/updateSubRoom', 'App\Http\Controllers\RoomController@updateSubRoom');
    });

    $api->group(['prefix' => 'promotions', 'middleware' => ['jwt.auth', 'role:superAdmin|admin']], function ($api) {
        $api->get('/index', 'App\Http\Controllers\PromoController@index');
        $api->post('/store', 'App\Http\Controllers\PromoController@store');
        $api->post('/update', 'App\Http\Controllers\PromoController@update');
        $api->delete('/index', 'App\Http\Controllers\PromoController@destroy');
    });

    $api->group(['prefix' => 'property_facilities', 'middleware' => ['jwt.auth', 'role:superAdmin|admin|properties']], function ($api) {
        $api->get('/index', 'App\Http\Controllers\PropertyFacilitiesController@index');
        $api->post('/store', 'App\Http\Controllers\PropertyFacilitiesController@store');
        $api->post('/update', 'App\Http\Controllers\PropertyFacilitiesController@update');
        $api->delete('/index', 'App\Http\Controllers\PropertyFacilitiesController@destroy');
    });

    $api->group(['prefix' => 'property_gallery', 'middleware' => ['jwt.auth', 'role:superAdmin|admin|properties']], function ($api) {
        $api->get('/index', 'App\Http\Controllers\PropertyGalleryController@index');
        $api->post('/store', 'App\Http\Controllers\PropertyGalleryController@store');
        $api->post('/update', 'App\Http\Controllers\PropertyGalleryController@update');
        $api->delete('/index', 'App\Http\Controllers\PropertyGalleryController@destroy');
    });

    $api->group(['prefix' => 'room_facilities', 'middleware' => ['jwt.auth', 'role:superAdmin|admin']], function ($api) {
        $api->get('/index', 'App\Http\Controllers\RoomFacilitiesController@index');
        $api->post('/store', 'App\Http\Controllers\RoomFacilitiesController@store');
        $api->post('/update', 'App\Http\Controllers\RoomFacilitiesController@update');
        $api->delete('/index', 'App\Http\Controllers\RoomFacilitiesController@destroy');
    });

    $api->group(['prefix' => 'facilities', 'middleware' => ['jwt.auth', 'role:superAdmin|admin|properties']], function ($api) {
        $api->get('/index', 'App\Http\Controllers\FacilitiesController@index');
        $api->post('/store', 'App\Http\Controllers\FacilitiesController@store');
        $api->post('/update', 'App\Http\Controllers\FacilitiesController@update');
        $api->delete('/index', 'App\Http\Controllers\FacilitiesController@destroy');
    });

    $api->group(['prefix' => 'setting', 'middleware' => ['jwt.auth']], function ($api) {
        // Akses Admin
        $api->group(['middleware' => ['jwt.auth', 'role:superAdmin']], function ($api) {
            $api->get('/site_setting', 'App\Http\Controllers\SettingController@index');
            $api->post('/site_setting', 'App\Http\Controllers\SettingController@update');
            $api->post('/site_setting_contact_me', 'App\Http\Controllers\SettingController@update_contact_me');
            $api->post('/site_setting_about_me', 'App\Http\Controllers\SettingController@update_about_me');
            $api->post('/site_setting_renovasi', 'App\Http\Controllers\SettingController@update_renovasi');
            $api->post('/site_setting_jual_sewa', 'App\Http\Controllers\SettingController@update_jual_sewa');

            $api->get('/faq', 'App\Http\Controllers\FaqController@index');
            $api->post('/faq', 'App\Http\Controllers\FaqController@store');
            $api->put('/faq', 'App\Http\Controllers\FaqController@update');
            $api->delete('/faq', 'App\Http\Controllers\FaqController@delete');

            $api->get('/mitra', 'App\Http\Controllers\MitraController@index');
            $api->post('/mitra_store', 'App\Http\Controllers\MitraController@store');
            $api->post('/mitra_update', 'App\Http\Controllers\MitraController@update');
            $api->delete('/mitra', 'App\Http\Controllers\MitraController@delete');

            $api->get('/social_media', 'App\Http\Controllers\SocialMediaController@index');
            $api->post('/social_media', 'App\Http\Controllers\SocialMediaController@store');
            $api->put('/social_media', 'App\Http\Controllers\SocialMediaController@update');
            $api->delete('/social_media', 'App\Http\Controllers\SocialMediaController@delete');

            $api->get('/services', 'App\Http\Controllers\ServiceMeController@index');
            $api->post('/services', 'App\Http\Controllers\ServiceMeController@store');
            $api->put('/services', 'App\Http\Controllers\ServiceMeController@update');
            $api->delete('/services', 'App\Http\Controllers\ServiceMeController@delete');

            $api->get('/portofolio', 'App\Http\Controllers\PortofolioController@index');
            $api->post('/portofolio_store', 'App\Http\Controllers\PortofolioController@store');
            $api->post('/portofolio_update', 'App\Http\Controllers\PortofolioController@update');
            $api->delete('/portofolio', 'App\Http\Controllers\PortofolioController@delete');

            $api->get('/process', 'App\Http\Controllers\ProcessWorkController@index');
            $api->post('/process', 'App\Http\Controllers\ProcessWorkController@store');
            $api->put('/process', 'App\Http\Controllers\ProcessWorkController@update');
            $api->delete('/process', 'App\Http\Controllers\ProcessWorkController@delete');

            $api->get('/testimoni', 'App\Http\Controllers\TestimoniController@index');
            $api->post('/testimoni_store', 'App\Http\Controllers\TestimoniController@store');
            $api->post('/testimoni_update', 'App\Http\Controllers\TestimoniController@update');
            $api->delete('/testimoni', 'App\Http\Controllers\TestimoniController@destroy');

            $api->get('/users', 'App\Http\Controllers\Admin\AdminUserController@index');
            $api->post('/users', 'App\Http\Controllers\Admin\AdminUserController@store');
            $api->put('/users', 'App\Http\Controllers\Admin\AdminUserController@update');

            $api->get('/store', 'App\Http\Controllers\Admin\DepartmentController@index');
            $api->post('/store', 'App\Http\Controllers\Admin\DepartmentController@store');
            $api->put('/store', 'App\Http\Controllers\Admin\DepartmentController@update');

            $api->get('/referral', 'App\Http\Controllers\ReferralSettingController@index');
            $api->post('/referral', 'App\Http\Controllers\ReferralSettingController@update');

            $api->get('/coupons', 'App\Http\Controllers\CouponController@index');
            $api->post('/coupons', 'App\Http\Controllers\CouponController@store');
            $api->put('/coupons', 'App\Http\Controllers\CouponController@update');

            $api->get('/ppn_tax_point', 'App\Http\Controllers\PpnTaxPointController@index');
            $api->post('/ppn_tax_point', 'App\Http\Controllers\PpnTaxPointController@update');
        });
    });

    $api->group(['prefix' => 'dashboard', 'middleware' => ['jwt.auth']], function ($api) {
        $api->get('/booking-detail', 'App\Http\Controllers\DashboardController@bookingDetail');
        
        $api->group(['middleware' => ['jwt.auth', 'role:properties|receptionis']], function ($api) {
            $api->get('/summary_properties', 'App\Http\Controllers\DashboardController@overviewProperties');
            $api->post('/checkin_booking', 'App\Http\Controllers\DashboardController@checkinBooking');
            $api->post('/checkout_booking', 'App\Http\Controllers\DashboardController@checkoutBooking');
            $api->post('/block_room', 'App\Http\Controllers\DashboardController@blockRoom');
            $api->post('/prepare_room', 'App\Http\Controllers\DashboardController@prepareRoom');
            $api->post('/open_room', 'App\Http\Controllers\DashboardController@openRoom');
        });

                // Akses Admin
        $api->group(['middleware' => ['jwt.auth', 'role:superAdmin|admin']], function ($api) {
            $api->get('/summary', 'App\Http\Controllers\DashboardController@overview');
            $api->get('/booking-availability', 'App\Http\Controllers\DashboardController@bookingAvailability');
            
        });
    });

    $api->group(['prefix' => 'transactions', 'middleware' => ['jwt.auth', 'role:superAdmin|admin']], function ($api) {
        $api->get('/wallet', 'App\Http\Controllers\TransactionController@index_wallet');
        $api->get('/booking_transactions', 'App\Http\Controllers\TransactionController@index_booking_transactions');
        $api->get('/top_up_transactions', 'App\Http\Controllers\TransactionController@index_top_up_transactions');
        $api->get('/membership_transactions', 'App\Http\Controllers\TransactionController@index_membership_transactions');
        $api->get('/point_transactions', 'App\Http\Controllers\TransactionController@index_point_transactions');
        $api->get('/all_transactions', 'App\Http\Controllers\TransactionController@index_all_transactions');
        $api->get('/detail_transaction', 'App\Http\Controllers\TransactionController@detail_transaction');
        $api->get('/detail', 'App\Http\Controllers\TransactionController@detail');
        $api->put('/cancel_booking_transactions', 'App\Http\Controllers\TransactionController@cancel_booking_transactions');
        $api->get('/get_booking', 'App\Http\Controllers\TransactionController@get_booking');
        $api->get('/membership_list', 'App\Http\Controllers\TransactionController@membership_list');
    });

    $api->group(['prefix' => 'member', 'middleware' => ['jwt.auth', 'role:superAdmin|admin']], function ($api) {
        $api->get('/index', 'App\Http\Controllers\MemberController@index');
    });

    $api->group(['prefix' => 'contacts', 'middleware' => ['jwt.auth', 'role:superAdmin|admin']], function ($api) {
        $api->get('/index', 'App\Http\Controllers\ContactController@index');
    });

    $api->group(['prefix' => 'crm', 'middleware' => ['jwt.auth', 'role:superAdmin|admin']], function ($api) {
        $api->get('/index', 'App\Http\Controllers\CrmController@index');
        $api->post('/index', 'App\Http\Controllers\CrmController@store');
        $api->put('/index', 'App\Http\Controllers\CrmController@update');
        $api->put('/process', 'App\Http\Controllers\CrmController@process');
        $api->put('/process_followup', 'App\Http\Controllers\CrmController@process_followup');

        $api->get('/source', 'App\Http\Controllers\CrmController@get_source');
        $api->get('/remark', 'App\Http\Controllers\CrmController@get_remark');
        $api->get('/history', 'App\Http\Controllers\CrmController@get_history');
    });

    $api->group(['prefix' => 'points','middleware' => ['jwt.auth', 'role:users']], function ($api) {
        $api->get('/myPoints', 'App\Http\Controllers\PointsController@myPoints');
    });
});
