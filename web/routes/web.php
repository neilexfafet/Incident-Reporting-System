<?php

use Illuminate\Support\Facades\Route;

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

/* Auth::routes(); */

Route::get('/', 'LoginController@userLoginForm');
Route::post('/user/login', 'LoginController@userLogin');
Route::post('/user/register', 'RegisterController@userRegister');
Route::post('user/logout/', 'LoginController@logout');

Route::get('/PNPCDO', 'LoginController@LoginForm');
Route::post('/PNP/login', 'LoginController@Login');
Route::post('/PNP/logout', 'LoginController@logout');

Route::post('/PNP/register-officer', 'RegisterController@registerofficer');

// ============================================ SETUP =======================================
Route::post('/PNP/setup/admin', 'RegisterController@setupadmin');
Route::post('/PNP/setup/officer', 'RegisterController@setupofficer');
// =========================================== END SETUP ====================================

// ============================================= ADMIN ===============================================
Route::get('admin/dashboard', 'AdminController@dashboard');
Route::get('admin/dashboard/reports', 'AdminController@dashboardreports');
/* PROFILE */
Route::get('admin/profile', 'AdminController@profile');
Route::post('admin/profile/updateusername/{id}', 'AdminController@updateusername');
Route::post('admin/profile/updateprofile/{id}', 'AdminController@updateprofile');
Route::post('admin/profile/updatepassword/{id}', 'AdminController@updatepassword');
/* NOTIFICATIONS */
Route::get('admin/notifications/header', 'AdminController@notificationheader');
Route::get('admin/notifications/count', 'AdminController@notificationcount');
Route::get('admin/notifications/sidebar', 'AdminController@notificationsidebar');
Route::get('admin/notifications/status/read', 'AdminController@notificationread');
Route::get('admin/notifications/view/announcement/{id}', 'AdminController@notificationviewannouncement');
/* NEWS AND ANNOUNCEMENTS */
Route::get('admin/post/news', 'AdminController@news');
Route::post('admin/post/addnews', 'AdminController@addnews');
Route::get('admin/post/viewnews/{id}', 'AdminController@viewnews');
Route::post('admin/post/updatenews/{id}', 'AdminController@updatenews');
Route::post('admin/post/removenews/{id}', 'AdminController@removenews');
Route::get('admin/post/announcements', 'AdminController@announcements');
Route::post('admin/post/addannouncement', 'AdminController@addannouncement');
Route::get('admin/post/viewannouncement/{id}', 'AdminController@viewannouncement');
Route::post('admin/post/updateannouncement/{id}', 'AdminController@updateannouncement');
Route::post('admin/post/removeannouncement/{id}', 'AdminController@removeannouncement');
/* USERS */
Route::get('admin/users', 'AdminController@users');
Route::post('admin/users/addadmin', 'AdminController@addadmin');
Route::post('admin/users/unauthorizeofficer/{id}', 'AdminController@unauthorizeofficer');
Route::post('admin/users/removeadmin', 'AdminController@removeadmin');
/* STATIONS */
Route::get('admin/station', 'AdminController@station');
Route::get('admin/station/view/{id}', 'AdminController@viewstation');
Route::get('admin/station/officers/view/{id}', 'AdminController@viewofficers');
Route::post('admin/station/addstation', 'AdminController@addstation');
Route::post('admin/station/updatestation/{id}', 'AdminController@updatestation');
Route::post('admin/station/resetpassword/confirm', 'AdminController@resetpasswordconfirm');
Route::post('admin/station/resetpassword/reset/{id}', 'AdminController@resetpassword');
Route::post('admin/station/remove/{id}', 'AdminController@removestation');
/* OFFICERS */
Route::get('admin/officers', 'AdminController@officers');
Route::get('admin/officers/unassign', 'AdminController@unassignofficers');
Route::get('admin/officers/pending', 'AdminController@pendingofficers');
Route::get('admin/officers/unassigncount', 'AdminController@unassigncount');
Route::get('admin/officers/view/{id}', 'AdminController@viewassign');
Route::post('admin/officers/addofficer', 'AdminController@addofficer');
Route::post('admin/officers/updateofficer/{id}', 'AdminController@updateofficer');
Route::post('admin/officers/assignstation/{id}', 'AdminController@assignstation');
Route::post('admin/officers/accept-officer/{id}', 'AdminController@acceptofficer');
Route::post('admin/officers/deny-officer/{id}', 'AdminController@denyofficer');
Route::post('admin/officers/transferofficer/{id}', 'AdminController@transferofficer');
Route::post('admin/officers/removeofficer/{id}', 'AdminController@removeofficer');
/* INCIDENT/CRIMES */
Route::get('admin/incidents', 'AdminController@incidents');
Route::post('admin/incidents/add', 'AdminController@addincident');
Route::get('admin/incidents/view/{id}', 'AdminController@viewincident');
Route::post('admin/incidents/update/{id}', 'AdminController@updateincident');
Route::post('admin/incidents/remove/{id}', 'AdminController@removeincident');
Route::get('admin/incident-reports', 'AdminController@incidentreports');
Route::get('admin/incident-reports/view/{id}', 'AdminController@incidentsviewdispatch');
/* STATISTICAL REPORTS */
Route::get('admin/statistical-reports', 'AdminController@statisticalreports');
Route::get('admin/statistical-reports/report-activity', 'AdminController@statisticalreportsactivity');
Route::post('admin/statistical-reports/report-activity/search-by-date', 'AdminController@statisticalreportsactivitysearch');
Route::post('admin/statistical-reports/report-activity-station', 'AdminController@statisticalreportsstation');
Route::get('admin/statistical-reports/stations-activity', 'AdminController@stationsactivity');
Route::post('admin/statistical-reports/stations-activity/search', 'AdminController@stationsactivitysearch');
Route::get('admin/statistical-reports/reports-list', 'AdminController@reportslist');
Route::get('admin/statistical-reports/reports-list/dispatch/view/{id}', 'AdminController@viewdispatch');
Route::post('admin/statistical-reports/reports-list/search-by-date', 'AdminController@reportslistsearchbydate');
Route::get('admin/statistical-reports/officers-list', 'AdminController@officerslist');
Route::get('admin/statistical-reports/users-activity', 'AdminController@usersactivity');
Route::get('admin/statistical-reports/users-list', 'AdminController@userslist');
Route::post('admin/statistical-reports/users-list/search-by-date', 'AdminController@userslistsearch');
Route::get('admin/statistical-reports/report-logs', 'AdminController@reportlogs');
Route::post('admin/statistical-reports/map-search', 'AdminController@mapsearch');
/* PRINTS */
Route::get('admin/print/report/dg1234d2sadfg214{id}sdfgsdfdf22g3214sdaf', 'AdminController@printreport');
Route::get('admin/print/report/asdsfd462sdaf4134234hgh{id}dfsfddsfasdg23572412f', 'AdminController@printreportstation');
Route::get('admin/print/reports-activity', 'AdminController@printreportsactivity');
Route::post('admin/print/reports-activity/search', 'AdminController@printreportsactivitysearch');
Route::get('admin/print/stations-activity', 'AdminController@printstationsactivity');
/* LOGS */
Route::get('admin/account-logs', 'AdminController@logs');
/* TRASH */
Route::get('admin/trash/officers', 'AdminController@trashofficers');
Route::get('admin/trash/officers/view/{id}', 'AdminController@viewtrashofficer');
Route::post('admin/trash/officers/restore/{id}', 'AdminController@restoreofficer');
Route::get('admin/trash/news', 'AdminController@trashnews');
Route::get('admin/trash/news/view/{id}', 'AdminController@viewtrashnews');
Route::post('admin/trash/news/restore/{id}', 'AdminController@restorenews');
Route::get('admin/trash/announcements', 'AdminController@trashannouncements');
Route::get('admin/trash/announcements/view/{id}', 'AdminController@viewtrashannouncements');
Route::post('admin/trash/announcements/restore/{id}', 'AdminController@restoreannouncement');
Route::get('admin/trash/crimes', 'AdminController@trashcrimes');
Route::get('admin/trash/crimes/view/{id}', 'AdminController@viewtrashcrimes');
Route::post('admin/trash/crimes/restore/{id}', 'AdminController@restoretrashcrimes');
Route::get('admin/trash/accounts', 'AdminController@trashaccounts');
Route::get('admin/trash/accounts/admin', 'AdminController@trashaccountsadmin');
Route::get('admin/trash/accounts/station', 'AdminController@trashaccountsstation');
Route::get('admin/trash/accounts/admin/view/{id}', 'AdminController@viewtrashaccountsadmin');
Route::get('admin/trash/accounts/station/view/{id}', 'AdminController@viewtrashaccountsstation');
Route::post('admin/trash/accounts/admin/restore/{id}', 'AdminController@restoreadmin');
Route::post('admin/trash/accounts/station/restore/{id}', 'AdminController@restorestation');
Route::get('admin/trash/blocked-users', 'AdminController@blockedusers');
Route::get('admin/trash/blocked-users/view/{id}', 'AdminController@viewblockeduser');
Route::post('admin/trash/blocked-users/unblock/{id}', 'AdminController@unblockuser');
// ============================================= END ADMIN =======================================


// ============================================ STATION ===============================================
Route::get('station/dashboard', 'StationController@dashboard');
Route::get('station/dashboard/reports', 'StationController@dashboardreports');
/* PROFILE */
Route::get('station/profile', 'StationController@profile');
Route::post('station/profile/updateusername/{id}', 'StationController@updateusername');
Route::post('station/profile/updateprofile/{id}', 'StationController@updateprofile');
Route::post('station/profile/updatepassword/{id}', 'StationController@updatepassword');
/* OFFICERS */
Route::get('station/officers', 'StationController@officers');
Route::get('station/officers/view/{id}', 'StationController@viewofficer');
/* NEWS AND ANNOUNCEMENTS */
Route::get('station/news', 'StationController@news');
Route::get('station/announcements', 'StationController@announcements');
Route::get('station/viewannouncement/{id}', 'StationController@viewannouncement');
Route::post('station/addannouncement', 'StationController@addannouncement');
Route::get('station/viewannouncement/{id}', 'StationController@viewannouncement');
Route::post('station/updateannouncement/{id}', 'StationController@updateannouncement');
Route::post('station/removeannouncement/{id}', 'StationController@removeannouncement');
/* REPORTS */
Route::get('station/incident-reports', 'StationController@incidentreports');
Route::get('station/incident-reports/responded', 'StationController@respondedincidentreports');
Route::get('station/incident-reports/verified', 'StationController@verifiedreports');
Route::get('station/incident-reports/unresponded/count', 'StationController@unrespondedcount');
Route::get('station/incident-reports/responded/count', 'StationController@respondedcount');
Route::get('station/incident-reports/station-details/{id}', 'StationController@stationdetails');
Route::get('station/incident-reports/reporter/view/{id}', 'StationController@viewreporter');
Route::get('station/incident-reports/responded/view/{id}', 'StationController@viewrespondedincidentreport');
Route::get('station/incident-reports/report/{id}', 'StationController@viewincidentreport');
Route::get('station/incident-reports/officers-to-dispatch', 'StationController@officerstodispatch');
Route::get('station/incident-reports/view-dispatched-officers/{id}', 'StationController@officersdispatched');
Route::post('station/incident-reports/dispatch-officers', 'StationController@dispatchofficers');
Route::post('station/incident-reports/transfer/{id}', 'StationController@transferreport');
Route::post('station/incident-reports/verify', 'StationController@confirmincidentreport');
Route::get('station/incident-reports/dispatch/view/{id}', 'StationController@viewdispatch');
/* NOTIFICATIONS */
Route::get('station/notifications/header', 'StationController@notificationheader');
Route::get('station/notifications/count', 'StationController@notificationcount');
Route::get('station/notifications/sidebar', 'StationController@notificationsidebar');
Route::get('station/notifications/status/read', 'StationController@notificationread');
Route::get('station/notifications/view/announcement/{id}', 'StationController@notificationviewannouncement');
/* STATISTICAL REPORTS */
Route::get('station/statistical-reports', 'StationController@statisticalreports');
Route::get('station/statistical-reports/report-activity', 'StationController@statisticalreportsactivity');
Route::get('station/statistical-reports/report-activity/station', 'StationController@stationstatisticalreportsactivity');
Route::post('station/statistical-reports/report-activity/search-by-date', 'StationController@statisticalreportsactivitysearch');
Route::post('station/statistical-reports/report-activity/station/search-by-date', 'StationController@stationstatisticalreportsactivitysearch');
Route::get('station/statistical-reports/reports-list', 'StationController@reportslist');
Route::post('station/statistical-reports/reports-list/station/search-by-date', 'StationController@stationreportslistsearchbydate');
Route::get('station/statistical-reports/reports-list/station', 'StationController@stationreportslist');
Route::post('station/statistical-reports/reports-list/search-by-date', 'StationController@reportslistsearchbydate');
Route::get('station/statistical-reports/officers-list', 'StationController@officerslist');
Route::post('station/statistical-reports/map-search', 'StationController@mapsearch');
/* PRINTS */
Route::get('station/print/report/dg1234dfg214{id}sdfgsdfg3214sdaf', 'StationController@printreport');
Route::get('station/print/reports-activity', 'StationController@printreportsactivity');
Route::post('station/print/reports-activity/search', 'StationController@printreportsactivitysearch');
Route::get('station/print/reports-activity/all', 'StationController@printreportsactivityall');
Route::get('station/print/stations-activity', 'StationController@printstationsactivity');
// ============================================ END STATION ===============================================


// =========================================== USER ====================================================
Route::get('user/dashboard', 'UserController@dashboard');
Route::get('user/stations', 'UserController@stations');
Route::get('user/incidenttype', 'UserController@incidenttype');
Route::get('user/description/{id}', 'UserController@incidenttypedesc');
Route::post('user/report-incident', 'UserController@reportincident');
/* NOTIFICATIONS */
Route::get('user/notifications', 'UserController@notifications');
Route::get('user/notifications/header', 'UserController@notificationheader');
Route::get('user/notifications/count', 'UserController@notificationcount');
Route::get('user/notifications/sidebar', 'UserController@notificationsidebar');
Route::get('user/notifications/status/read', 'UserController@notificationread');
Route::get('user/notifications/view/announcement/{id}', 'UserController@notificationviewannouncement');
/* PROFILE */
Route::get('user/profile', 'UserController@profile');
Route::post('user/profile/updateemail/{id}', 'UserController@updateemail');
Route::post('user/profile/updateprofile/{id}', 'UserController@updateprofile');
Route::post('user/profile/updatepassword/{id}', 'UserController@updatepassword');

// =========================================== MOBILE ====================================================
Route::get('mobile/incident-type', 'MobileController@incidenttype');
Route::post('mobile/report', 'MobileController@reportincident');






Route::get('admin/fortesting', 'AdminController@testing');