<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HostController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\OtherController;
use App\Http\Controllers\Seller\MainController;
use App\Http\Controllers\Seller\ManagementController;
use App\Http\Controllers\SendController;
use App\Http\Middleware\Admin;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('auth')->middleware('guest')->group(function() {
    Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/signin', [AuthController::class, 'signin'])->name('auth.signin');
    Route::get('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/signup', [AuthController::class, 'signup'])->name('auth.signup');
    Route::get('/forgot_password', [AuthController::class, 'forgot_password'])->name('auth.forgot_password');
    Route::post('/reset_password', [AuthController::class, 'reset_password'])->name('auth.reset_password');
    Route::get('/resetpassword', [AuthController::class, 'reset'])->name('password.reset');
    Route::post('/reset', [AuthController::class, 'reset_post'])->name('auth.reset');
});

Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::post('ipn/deposit/coinpayments', [App\Http\Controllers\IpnController::class, 'coinpayments']);


Route::middleware(['auth'])->group(function() {
    Route::get('/', function(){
        return redirect('/home');
    });
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/get_infos', [HomeController::class, 'get_infos']);

    Route::get('/ticket', [HomeController::class, 'ticket']);
    Route::post('/ticket/save', [HomeController::class, 'ticket_save']);
    Route::post('/ticket/reply', [HomeController::class, 'ticket_reply']);
    Route::post('/ticket/get', [HomeController::class, 'get_ticket']);
    Route::post('/ticket/close', [HomeController::class, 'ticket_close']);

    Route::get('/balance', [HomeController::class, 'balance']);
    Route::post('/balance/save', [HomeController::class, 'balance_save']);
    Route::get('/coinpayments/details/{id}', [HomeController::class, 'coinpayments']);
    Route::get('/coinpayments/status', [HomeController::class, 'coinpayments_status']);

    Route::get('/orders', [HomeController::class, 'orders'])->name('orders');
    Route::post('/orders/get_order', [HomeController::class, 'get_order']);
    Route::post('/orders/get_report', [HomeController::class, 'get_report']);
    Route::post('/orders/report', [HomeController::class, 'report_save']);

    Route::get('/report', [HomeController::class, 'reports'])->name('report');
    Route::post('/report/get', [HomeController::class, 'get_report']);
    Route::post('/report/reply', [HomeController::class, 'report_save']);
    Route::post('/report/close', [HomeController::class, 'report_close']);

    Route::get('/setting', [HomeController::class, 'setting'])->name('setting');
    Route::post('/setting/save', [HomeController::class, 'setting_save']);

    Route::prefix('hosts')->group(function() {
        Route::get('rdps', [HostController::class, 'rdps'])->name('hosts.rdps');
        Route::post('rdps/check', [HostController::class, 'rdp_check']);
        Route::post('rdps/filter', [HostController::class, 'rdp_filter']);

        Route::get('cpanels', [HostController::class, 'cpanels'])->name('hosts.cpanels');
        Route::post('cpanels/filter', [HostController::class, 'cpanel_filter']);
        Route::post('cpanels/check', [HostController::class, 'cpanel_check']);

        Route::get('shells', [HostController::class, 'shells'])->name('hosts.shells');
        Route::post('shells/filter', [HostController::class, 'shell_filter']);
        Route::post('shells/check', [HostController::class, 'shell_check']);
    });

    Route::prefix('send')->group(function() {
        Route::get('mailers', [SendController::class, 'mailers'])->name('send.mailers');
        Route::post('mailers/check', [SendController::class, 'mailer_check']);
        Route::post('mailers/change_test_email', [SendController::class, 'change_test_email']);
        Route::post('mailers/filter', [SendController::class, 'mailer_filter']);

        Route::get('smtps', [SendController::class, 'smtps'])->name('send.smtps');
        Route::post('smtps/filter', [SendController::class, 'smtp_filter']);
        Route::post('smtps/check', [SendController::class, 'smtp_check']);
    });

    Route::prefix('leads')->group(function() {
        Route::get('checked_list', [LeadController::class, 'checked_list'])->name('leads.checked_list');
        Route::post('checked_list/filter', [LeadController::class, 'checked_list_filter']);

        Route::get('email_list', [LeadController::class, 'email_list'])->name('leads.email_list');
        Route::post('email_list/filter', [LeadController::class, 'email_list_filter']);

        Route::get('combo_list', [LeadController::class, 'combo_list'])->name('leads.combo_list');
        Route::post('combo_list/filter', [LeadController::class, 'combo_list_filter']);
    });

    Route::prefix('accounts')->group(function() {
        Route::get('marketing', [AccountController::class, 'marketing'])->name('accounts.marketing');
        Route::get('hosting', [AccountController::class, 'hosting'])->name('accounts.hosting');
        Route::get('games', [AccountController::class, 'games'])->name('accounts.games');
        Route::get('vpn', [AccountController::class, 'vpn'])->name('accounts.vpn');
        Route::get('shopping', [AccountController::class, 'shopping'])->name('accounts.shopping');
        Route::get('stream', [AccountController::class, 'stream'])->name('accounts.stream');
        Route::get('dating', [AccountController::class, 'dating'])->name('accounts.dating');
        Route::get('learning', [AccountController::class, 'learning'])->name('accounts.learning');
        Route::get('voip', [AccountController::class, 'voip'])->name('accounts.voip');

        Route::post('filter/{type}', [AccountController::class, 'marketing_filter']);
    });

    Route::prefix('others')->group(function() {
        Route::get('scampage', [OtherController::class, 'scampage'])->name('others.scampage');
        Route::post('scampage/filter', [OtherController::class, 'scam_filter']);

        Route::get('tutorials', [OtherController::class, 'tutorials'])->name('others.tutorials');
        Route::post('tutorials/filter', [OtherController::class, 'tutorial_filter']);
    });

    Route::post('/buy', [HostController::class, 'buy']);
});

Route::prefix('seller')->middleware(['auth', 'seller'])->group(function() {
    Route::get('/', function() {
        return redirect()->route('seller.main.dashboard');
    });

    Route::prefix('main')->group(function() {
        Route::get('/dashboard', [MainController::class, 'dashboard'])->name('seller.main.dashboard');
        Route::get('/sales',[MainController::class, 'sales'])->name('seller.main.sales');
        Route::get('/withdraw', [MainController::class, 'withdraw'])->name('seller.main.withdraw');
        Route::post('/withdraw/chage_btc_address', [MainController::class, 'change_btc_address']);
        Route::post('/withdraw/request', [MainController::class, 'request']);
        Route::get('/myreports', [MainController::class, 'myreports'])->name('seller.main.myreports');
        Route::get('/myreports/{id}', [MainController::class, 'report_view']);
        Route::post('/myreports/refund', [MainController::class, 'refund']);
        Route::post('/myreports/reply', [MainController::class, 'report_reply']);
    });

    Route::prefix('management')->group(function() {
        Route::get('/rdp', [ManagementController::class, 'rdp_show'])->name('seller.management.rdp');
        Route::post('/rdp_save', [ManagementController::class, 'rdp_save']);
        Route::post('/rdp_mass_save', [ManagementController::class, 'rdp_mass_save']);
        Route::post('/rdp_delete', [ManagementController::class, 'rdp_delete']);

        Route::get('/shell', [ManagementController::class, 'shell_show'])->name('seller.management.shell');
        Route::post('/shell_save', [ManagementController::class, 'shell_save']);
        Route::post('/shell_mass_save', [ManagementController::class, 'shell_mass_save']);
        Route::post('/shell_delete', [ManagementController::class, 'shell_delete']);

        Route::get('/cpanel', [ManagementController::class, 'cpanel_show'])->name('seller.management.cpanel');
        Route::post('/cpanel_save', [ManagementController::class, 'cpanel_save']);
        Route::post('/cpanel_mass_save', [ManagementController::class, 'cpanel_mass_save']);
        Route::post('/cpanel_delete', [ManagementController::class, 'cpanel_delete']);

        Route::get('/phpmailer', [ManagementController::class, 'phpmailer_show'])->name('seller.management.phpmailer');
        Route::post('/mailer_save', [ManagementController::class, 'mailer_save']);
        Route::post('/mailer_delete', [ManagementController::class, 'mailer_delete']);

        Route::get('/smtp', [ManagementController::class, 'smtp_show'])->name('seller.management.smtp');
        Route::post('/smtp_save', [ManagementController::class, 'smtp_save']);
        Route::post('/smtp_delete', [ManagementController::class, 'smtp_delete']);

        Route::get('/leads', [ManagementController::class, 'leads_show'])->name('seller.management.leads');
        Route::post('/lead_save', [ManagementController::class, 'lead_save']);
        Route::post('/lead_delete', [ManagementController::class, 'lead_delete']);

        Route::get('/account', [ManagementController::class, 'accounts_show'])->name('seller.management.account');
        Route::post('/account_save', [ManagementController::class, 'account_save']);
        Route::post('/account_delete', [ManagementController::class, 'account_delete']);

        Route::get('/tutorial', [ManagementController::class, 'tutorials_show'])->name('seller.management.tutorial');
        Route::post('/tutorial_save', [ManagementController::class, 'tutorial_save']);
        Route::post('/tutorial_delete', [ManagementController::class, 'tutorial_delete']);

        Route::get('/scam', [ManagementController::class, 'scams_show'])->name('seller.management.scam');
        Route::post('/scam_save', [ManagementController::class, 'scam_save']);
        Route::post('/scam_delete', [ManagementController::class, 'scam_delete']);
    });

});

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function() {
    Route::get('/', function() {
        return redirect()->route('admin.main');
    });

    Route::get('/main', [AdminController::class, 'index'])->name('admin.main');
    Route::get('/financial', [AdminController::class, 'financial'])->name('admin.financial');
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');

    Route::get('/news', [AdminController::class, 'news'])->name('admin.news');
    Route::post('/news_save', [AdminController::class, 'news_save']);
    Route::post('/news_delete', [AdminController::class, 'news_delete']);

    Route::get('/tools', [AdminController::class, 'tools'])->name('admin.tools');
    Route::post('/tools/rdp_delete', [AdminController::class, 'rdp_delete']);
    Route::post('/tools/shell_delete', [AdminController::class, 'shell_delete']);
    Route::post('/tools/cpanel_delete', [AdminController::class, 'cpanel_delete']);
    Route::post('/tools/mailer_delete', [AdminController::class, 'mailer_delete']);
    Route::post('/tools/smtp_delete', [AdminController::class, 'smtp_delete']);
    Route::post('/tools/lead_delete', [AdminController::class, 'lead_delete']);
    Route::post('/tools/account_delete', [AdminController::class, 'account_delete']);
    Route::post('/tools/tutorial_delete', [AdminController::class, 'tutorial_delete']);
    Route::post('/tools/scam_delete', [AdminController::class, 'scam_delete']);

    Route::get('/tickets', [AdminController::class, 'tickets'])->name('admin.tickets');
    Route::post('/tickets/insert', [AdminController::class, 'ticket_insert']);
    Route::post('/tickets/get', [AdminController::class, 'get_ticket']);
    Route::post('/tickets/reply', [AdminController::class, 'reply']);
    Route::post('/tickets/close', [AdminController::class, 'close']);

    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/reports/{id}', [AdminController::class, 'report_view']);
    Route::post('/report/reply', [AdminController::class, 'report_reply']);
    Route::post('/report/refund', [AdminController::class, 'refund']);

    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/make_seller', [AdminController::class, 'make_seller']);

    Route::get('/sellers', [AdminController::class, 'sellers'])->name('admin.sellers');
    Route::post('/sellers/get', [AdminController::class, 'get']);
    Route::post('/sellers/save', [AdminController::class, 'save']);
    Route::post('/sellers/delete', [AdminController::class, 'delete']);

    Route::prefix('withdraw')->group(function() {
        Route::get('/withdraw_approval', [AdminController::class, 'withdraw_approval'])->name('admin.withdraw.withdraw_approval');
        Route::post('/get_detail', [AdminController::class, 'get_detail']);
        Route::post('/pay', [AdminController::class, 'pay']);
        Route::post('/manual_pay', [AdminController::class, 'manual_pay']);

        Route::get('/withdraw_history', [AdminController::class, 'withdraw_history'])->name('admin.withdraw.withdraw_history');
    });
});
